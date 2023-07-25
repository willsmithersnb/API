<?php

namespace App\Http\Controllers;

class HealthCheckController extends Controller
{
    public function __invoke()
    {
        return response('Healthy', 200)->header('Content-Type', 'text/plain');
    }
}
