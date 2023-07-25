<?php

namespace App\Http\Controllers;

use App\Models\MessageThread;
use App\Transformer\MessageThreadTransformer;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageThreadController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->authorizeResource(MessageThread::class, 'message_thread');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $message_threads = MessageThread::filter($request->all())->paginate(min($request->get('perPage', 5), 15));
        return $this->response->withPaginator($message_threads, new MessageThreadTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        throw new BadRequestHttpException();
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\MessageThread  $message_thread
     * @return \Illuminate\Http\Response
     */
    public function show(MessageThread $message_thread)
    {
        try {
            return $this->response->item($message_thread, new MessageThreadTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\MessageThread  $message_thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessageThread $message_thread)
    {
        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Missing Required Fields', $validator->errors());
        }
        try {
            $message_thread->update($request->only(["subject"]));
            return $this->response->item($message_thread, new MessageThreadTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new UpdateResourceFailedException();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\MessageThread  $message_thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageThread $message_thread)
    {
        try {
            $message_thread->delete();
            return $this->response->item($message_thread, new MessageThreadTransformer);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException;
        } catch (Exception $ex) {
            throw new DeleteResourceFailedException();
        }
    }
}
