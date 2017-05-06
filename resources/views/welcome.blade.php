<!DOCTYPE html>
<html>
    <head>
        @include('partials._head')
    </head>
    <body>
        @include('partials._nav')
        <div>
            @yield('content')
            @include('partials._footer')
        </div>
        @include('partials._javascript')
        @yield('script')
    </body>
</html>
