<!DOCTYPE html>
@php
    $lang = Session::get('language');
@endphp
@if($lang)
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
                    <title>@yield('title') || {{ config('app.name') }}</title>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    @include('layouts.include')
                    @yield('css')
                    @if(config('app.enable_tawk'))
                        <!--Start of Tawk.to Script-->
                        <script type="text/javascript">
                            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                            (function () {
                                var s1 = document.createElement("script"),
                                    s0 = document.getElementsByTagName("script")[0];
                                s1.async = true;
                                s1.src = 'https://embed.tawk.to/673060d64304e3196adfc748/1icafkie6';
                                s1.charset = 'UTF-8';
                                s1.setAttribute('crossorigin', '*');
                                s0.parentNode.insertBefore(s1, s0);
                            })();
                        </script>
                        <!--End of Tawk.to Script-->
                    @endif
                </head>
                <body class="sidebar-fixed">
                <div class="container-scroller">
                    {{-- header --}}
                    @include('layouts.header')
                    <div class="container-fluid page-body-wrapper">
                        {{-- siderbar --}}
                        @include('layouts.sidebar')
                        <div class="main-panel">
                            @yield('content')

                            {{-- Description modal #Bootstrap-table --}}
                            @include('description_modal')
                            {{-- footer --}}
                            @include('layouts.footer')
                        </div>
                    </div>
                </div>
                @include('layouts.footer_js')
                @yield('js')
                @yield('script')
                </body>
                </html>
