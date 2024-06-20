<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('landing/img/fav.png') }}">
    <!-- Author Meta -->
    <meta name="author" content="codepixer">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>{{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <!--
   CSS
   ============================================= -->
    <link rel="stylesheet" href="{{ asset('landing/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.css') }} ">
    <link rel="stylesheet" href="{{ asset('landing/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/main.css') }}">
</head>

<body>

    <header id="header" id="home">
        <div class="container">
            <div class="row header-top align-items-center">
                <div class="col-lg-4 col-sm-4 menu-top-left">
                    <span>
                        We believe we help people <br>
                        for happier lives
                    </span>
                </div>
                <div class="col-lg-4 menu-top-middle justify-content-center d-flex">
                    <a href="{{ url('/') }}">
                        <span>
                            {{ env('APP_NAME') }}
                        </span>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-4 menu-top-right">
                    @auth
                        <a href="{{ route('logout') }}"><span class="lnr lnr-exit"><span>Dashboard</span></span></a>
                        <a href="{{ route('logout') }}"><span class="lnr lnr-exit"><span>Logout</span></span></a>
                    @else
                        <a href="{{ route('login') }}">
                            <span class="lnr lnr-enter">
                                <span>Login-Register</span>
                            </span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="row align-items-center justify-content-center d-flex">
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li class="menu-active"><a href="#home">Home</a></li>
                        <li><a href="#offer">we offer</a></li>
                        <li><a href="#top-timelines">Top Timelines</a></li>
                        <li><a href="#trainer">Trainer</a></li>
                    </ul>
                </nav><!-- #nav-menu-container -->
            </div>
        </div>
    </header><!-- #header -->

    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row fullscreen d-flex align-items-center justify-content-start">
                <div class="banner-content col-lg-12 col-md-12">
                    <h1 class="text-uppercase">
                        Real Fitness <br>
                        Depends on Exercise
                    </h1>
                    <p class="text-white text-uppercase pt-20 pb-20">
                        Shape your body well.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start offer Area -->
    <section class="offer-area section-gap" id="offer">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class=" mb-10">We care about what we offer</h1>
                        <p>We are in extremely love with eco friendly system.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="single-offer">
                        <img class="img-fluid" src="{{ asset('landing/img/o1.png') }}" alt="">
                        <h4>Coach Based Timelines</h4>
                        <p>
                            Our expert coaches provide personalized timelines to help you achieve your goals
                            effectively. Experience a structured and supportive approach tailored to your needs.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-offer">
                        <img class="img-fluid" src="{{ asset('landing/img/o3.png') }}" alt="">
                        <h4>Public Conversation</h4>
                        <p>
                            Engage in dynamic public conversations and share insights with the community. Participate in
                            discussions, ask questions, and learn from others' experiences. Build connections and grow

                        </p>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="single-offer">
                        <img class="img-fluid" src="{{ asset('landing/img/o2.png') }}" alt="">
                        <h4>Chat With Coach(Pro)</h4>
                        <p>
                            Connect with your coach anytime for personalized advice and guidance. Our chat feature
                            ensures you get the support you need to stay on track and achieve your goals.
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- End offer Area -->

    <!-- Start top-course Area -->
    <section class="top-course-area section-gap" id="top-timelines">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-9">
                    <div class="title text-center">
                        <h1 class="mb-10">Top Timelines That are open for Trainees</h1>
                        <p>Who are in extremely love with our eco friendly system.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($timelines as $timeline)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-course">
                            <div class="thumb">
                                <img class="img-fluid" src="{{ asset('landing/img/' . $timeline->image) }}"
                                    alt="">
                            </div>
                            <span class="course-status">{{ $timeline->timeline_trainees_count }} Trainees</span>
                            <a href="#">
                                <h4>{{ $timeline->name }} <span>{{ $timeline->coach->fullname }}</span></h4>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End top-course Area -->




    <!-- Start team Area -->
    <section class="team-area section-gap" id="trainer">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Our Experienced Coaches</h1>
                        <p>Who are in extremely love with our eco friendly system.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center d-flex align-items-center">
                @foreach ($coaches as $coach)
                    <div class="col-md-3 single-team">
                        <div class="thumb">
                            <img src="{{ $coach->avatar ?? asset('/img/team-1.jpg') }}" class="img-fluid"
                                alt="">
                        </div>
                        <div class="meta-text mt-30 text-center">
                            <h4>{{ $coach->fullname }}</h4>
                            <p>{{ $coach->timeline_trainees_count }} Trainnes</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End team Area -->

    <!-- Start callto Area -->
    <section class="callto-area section-gap relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <h1 class="text-white">Become a Member</h1>
                    <p class="text-white pt-20 pb-20">
                        Download the mobile app to become a member
                    </p>
                    <a class="primary-btn" href="{{ asset('/storage/payment_qr/app.apk') }}" download>Download
                        Now</a>
                    <p class="text-white pt-20 pb-20">
                        Scan QR to become VIP memeber
                    </p>
                    <img src="{{ asset('/storage/payment_qr/QR.jpg') }}" width="300">
                </div>
            </div>
        </div>
    </section>
    <!-- End callto Area -->


    <!-- start footer Area -->
    <footer class="footer-area section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h4>About Us</h4>
                        <p>
                            This is a graduation project
                        </p>
                    </div>
                </div>
                <div class="col-lg-4  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h4>Contact Us</h4>
                        <p>
                            you can contact us on the phone numbers
                        </p>
                        <p class="number">
                            012-6532-568-9746 <br>
                            012-6532-569-9748
                        </p>
                    </div>
                </div>

            </div>
            <div class="footer-bottom row">
                <p class="footer-text m-0 col-lg-6">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved | Made with <i class="icon-heart3"
                        aria-hidden="true"></i> by <a href="#">{{ env('APP_NAME') }}</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>

            </div>
        </div>
    </footer>
    <!-- End footer Area -->

    <script src="{{ asset('landing/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="{{ asset('landing/js/vendor/bootstrap.min.js') }}"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
    <script src="{{ asset('landing/js/easing.min.js') }}"></script>
    <script src="{{ asset('landing/js/hoverIntent.js') }}"></script>
    <script src="{{ asset('landing/js/superfish.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('landing/js/parallax.min.js') }}"></script>
    <script src="{{ asset('landing/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('landing/js/mail-script.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>

</html>
