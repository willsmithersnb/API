<!DOCTYPE html>
<html lang="en">

<head>
    @section('head')

    @section('meta')
    @include('includes.meta')
    @show

    <title> @section('title') {{ config('app.name') }} @show </title>

    @section('styles')
    @include('includes.styles')
    @show

    @section('head-scripts')
    @show

    @show
</head>

<body class="@section('body_classes')d-flex flex-column min-vh-100 @show" @section('body_tags')@show>
    @section('start_body')
    @show

    @section('header')
    @include('includes.header')
    @show

    @section('app-container')
    <div id="app" class="container-fluid">
        @yield('content')
    </div>
    @show

    @section('footer')
    @include('includes.footer')
    @show

    @section('body-scripts')
    @include('includes.scripts')
    @show

</body>

</html>