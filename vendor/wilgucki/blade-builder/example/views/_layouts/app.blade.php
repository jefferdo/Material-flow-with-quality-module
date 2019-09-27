<!DOCTYPE html>
<html lang="en">
<head>
    @include('_partials.head')
</head>
<body>
@include('_partials.top')
<div class="container">
    @yield('content')
</div><!-- /.container -->
@include('_partials.scripts')
</body>
</html>
