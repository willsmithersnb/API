<?php

namespace App\Http\Controllers;

use App\Mail\Admin\Messages\AdminMessagesReceived;
use App\Mail\Customer\Messages\MessagesReceived;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\Attachment;
use App\Transformer\ModelTransformer;
use App\User;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(Message::class, 'message');
    }

    /**
     * Returns base rules for dingo validator
     *
     * @return Array rules list
     */
    private function rules()
    {
        return [
            'customer_id' => 'sometimes|exists:App\Models\Customer,id',
            'subject' => 'required_without:message_thread_id|string',
            'message_thread_id' => 'required_without:subject|exists:App\Models\MessageThread,id',
            'body' => 'required|string|max:1000',
            'attachable_type' => 'sometimes|string',
            'attachable_id' => 'sometimes|poly_exists:attachable_type'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $messages = Message::filter($request->all())->get();
        return $this->response->collection($messages, new ModelTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $from_admin = $user->isAdmin() && isAdminOriginated();
            $message_thread_id = $request->get('message_thread_id', null);
            $subject = "";
            if ($request->has('subject')) {
                $customer_id = $user->isAdmin() && $request->has('customer_id') ? $request->get('customer_id') : $user->customer_id;
                $message_thread = [
                    'subject' => $request->get('subject'),
                    'customer_id' => $customer_id
                ];
                $subject = $message_thread['subject'];
                $message_thread_id = MessageThread::create($message_thread)->id;
            } else {
                $belongs_to_query = MessageThread::where(
                    'id',
                    $message_thread_id
                );
                if (!$from_admin) {
                    $belongs_to_query = $belongs_to_query->where(
                        'customer_id',
                        $user->customer_id
                    );
                }
                if (!$belongs_to_query->exists()) {
                    throw new AccessDeniedHttpException();
                }
                $subject = "Re: " . $belongs_to_query->first()->subject;
            }
            $message_request = [
                'user_id' => $user->id,
                'body' => $request->get('body'),
                'message_thread_id' => $message_thread_id,
                'from_admin' => $from_admin,
                'customer_id' => Auth::user()->customer_id
            ];
            $message = Message::create($message_request);

            // creating attachments

            if($request->get('attachment_attachable_id'))
            {
                $attachment = Attachment::create([
                    'attachable_id' => $request->get('attachment_attachable_id'),
                    'name' => $request->get('attachment_name'),
                    'message_id' => $message->id,
                    'customer_id' => Auth::user()->customer_id,
                    'attachable_type' => $request->get('attachment_attachable_type')
                ]);
            }

            DB::commit();

            $sendTo = [];
            $mail = null;
            if ($from_admin) {
                $sendTo = User::select('email')->whereIn('id', Message::select('user_id')->where('message_thread_id', $message->message_thread_id)->where('from_admin', false))->get();
                $mail = new AdminMessagesReceived($user->full_name, $message->body, $subject, $message->message_thread_id);
            } else {
                $sendTo = config('app.admin_emails');
                $mail = new MessagesReceived($user->full_name, $message->body, $subject, $message->message_thread_id);
            }

            Mail::to($sendTo)->send($mail);
            return $this->response->item($message, new ModelTransformer);
        } catch (AccessDeniedHttpException $ex) {
            DB::rollback();
            throw new AccessDeniedHttpException;
        } catch (Exception $ex) {
            DB::rollback();
            throw new StoreResourceFailedException;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        try {
            return $this->response->item($message, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        try {
            $message->delete();
            return $this->response->item($message, new ModelTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException;
        }
    }
}
