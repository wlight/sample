<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>@yield('title', 'Sample App') - Laravel第一个程序</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._header')
    <div class="container">
      @include('shared.messages')
      @yield('content')
      @include('layouts._footer')
    </div>
  </body>
</html>
