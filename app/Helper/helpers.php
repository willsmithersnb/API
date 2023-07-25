<?php

use Illuminate\Http\Request;

if (!function_exists('isAdminOriginated')) {
    function isAdminOriginated(): ?bool
    {
        return preg_match(
            '/' . config('app.admin_portal_origin') . '/',
            request()->header('referer')
        );
    }
}

if (!function_exists('isCustomerOriginated')) {
    function isCustomerOriginated(): ?bool
    {
        return !isAdminOriginated();
    }
}

if (!function_exists('requestBodyWithCustomerID')) {
    function requestBodyWithCustomerID(Request $request)
    {
        $request->request->add(['customer_id' => auth()->user()->customer_id]);
        return $request;
    }
}
