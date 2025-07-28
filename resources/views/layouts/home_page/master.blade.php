<!DOCTYPE html>
@php
$lang = Session::get('language');
@endphp
@if ($lang)
@if ($lang->is_rtl)
<html lang="en" dir="rtl">
@else
<html lang="en">
@endif
@else
<html lang="en">
@endif

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
  (function(d,t) {
    var BASE_URL="https://app.chatwoot.com";
    var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=BASE_URL+"/packs/js/sdk.js";
    g.defer = true;
    g.async = true;
    s.parentNode.insertBefore(g,s);
    g.onload=function(){
      window.chatwootSDK.run({
        websiteToken: 'dyzyo7PePwNDoW7KwBhtyUM5',
        baseUrl: BASE_URL
      })
    }
  })(document,"script");
</script>
    @include('layouts.home_page.include')
    @include('layouts.include')
    @yield('css')
</head>

<body class="sidebar-fixed">
    <div class="container-scroller">

        @yield('content')

    </div>
    @include('layouts.home_page.footer_js')
    @yield('js')
    @yield('script')
</body>

</html>