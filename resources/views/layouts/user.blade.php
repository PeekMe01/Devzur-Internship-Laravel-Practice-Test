<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Colo Shop')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Colo Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/bootstrap4/bootstrap.min.css') }}">
    <link href="{{ asset('user/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/main_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/responsive.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/bootstrap4/bootstrap.min.css') }}">
    <link href="{{ asset('user/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/main_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/plugins/jquery-ui-1.12.1.custom/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/categories_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/categories_responsive.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        } 
    </style>
</head>

<body>

    <div class="super_container">

        @include('components.userNavbar')
        <!-- Content Start -->
        <div class="content">
            <!-- Main Content -->
            @yield('content')

            @include('components.userFooter')
        </div>

    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':

                    toastr.options.timeOut = 10000;
                    toastr.info("{{ Session::get('message') }}");
                    var audio = new Audio('audio.mp3');
                    audio.play();
                    break;
                case 'success':

                    toastr.options.timeOut = 10000;
                    toastr.success("{{ Session::get('message') }}");
                    var audio = new Audio('audio.mp3');
                    audio.play();

                    break;
                case 'warning':

                    toastr.options.timeOut = 10000;
                    toastr.warning("{{ Session::get('message') }}");
                    var audio = new Audio('audio.mp3');
                    audio.play();

                    break;
                case 'error':

                    toastr.options.timeOut = 10000;
                    toastr.error("{{ Session::get('message') }}");
                    var audio = new Audio('audio.mp3');
                    audio.play();

                    break;
            }
        @endif
    </script>
    
    <script src="{{ asset('user/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('user/styles/bootstrap4/popper.js') }}"></script>
    <script src="{{ asset('user/styles/bootstrap4/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('user/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
    <script src="{{ asset('user/plugins/easing/easing.js') }}"></script>
    <script src="{{ asset('user/js/custom.js') }}"></script>
    <script src="{{ asset('user/plugins/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
    <script src="{{ asset('user/js/categories_custom.js') }}"></script>

</body>
</html>