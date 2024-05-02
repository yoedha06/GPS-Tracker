<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>GPS EXPLORER</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/images/geex.png" rel="icon">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>


<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            @if ($logo)
                <a href=""><img src="{{ asset('storage/' . $logo) }}" alt="Logo"
                        style="width: 120px; height: 90px;"></a>
            @else
                <a href=""><img src="{{ asset('path/to/default/photo.jpg') }}" alt=""
                        style="width: 120px; height: 90px;"></a>
            @endif

            <h1 class="logo me-auto">
                @if ($title_pengaturan)
                    <a href="">{{ $title_pengaturan }}</a>
                @else
                    SILAHKAN ISI TAMPILAN PENGATURAN
                @endif
            </h1>
        </div>


        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">About</a></li>
                <li><a class="nav-link scrollto" href="#team">Team</a></li>
                <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero"
        style="background-image: url('{{ $background ? asset('storage/' . $background) : asset('path/to/default/photo.jpg') }}') ; background-position: right; background-size: cover; background-repeat: no-repeat;"
        class="d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>{{ $name_pengaturan ? $name_pengaturan : 'Please Edit' }}</h1>
                    <h2>Please log in below!</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="{{ route('login') }}" class="btn-get-started scrollto">Log in here</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <main id="main">

        <!-- ======= About Us Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>{{ $title_about }}</h2>
                </div>

                <div class="row content">
                    <div class="col-lg-6">
                        <p>
                            {{-- GPS Explorer is a web-based application that simplifies the management of location-based
                            data and navigation. --}} {{ $left_description }}
                        </p>
                        <ul>
                            <li><i class="ri-check-double-line"></i>{{ $feature_1 }}</li>
                            <li><i class="ri-check-double-line"></i>{{ $feature_2 }}</li>
                            <li><i class="ri-check-double-line"></i> {{ $feature_3 }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0">
                        <p>
                            {{-- GPS Explorer provides a comprehensive platform for managing and exploring geographical data.
                            It allows users to access and analyze location-related information efficiently. The digital
                            mapping system aims to enhance traditional navigation methods and provide real-time insights
                            into geographical data. --}} {{ $right_description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- End About Us Section -->

        <!-- ======= Team Section ======= -->
        <section id="team" class="team section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Team</h2>
                    <p>{{ $informasi }}</p>
                </div>


                <div class="row">
                    <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="member d-flex align-items-start">
                            <div class="pic"
                                style="width: 150px; height: 120px; overflow: hidden; border-radius: 50%;">
                                <img src="{{ asset('storage/' . $photo_1) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="member-info">
                                <h4>{{ $username_1 }}</h4>
                                <span>{{ $posisi_1 }}</span>
                                <p>{{ $deskripsi_1 }}</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="member d-flex align-items-start">
                            <div class="pic"
                                style="width: 150px; height: 120px; overflow: hidden; border-radius: 50%;">
                                <img src="{{ asset('storage/' . $photo_2) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="member-info">
                                <h4>{{ $username_2 }}</h4>
                                <span>{{ $posisi_2 }}</span>
                                <p>{{ $deskripsi_2 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="300">
                        <div class="member d-flex align-items-start">
                            <div class="pic"
                                style="width: 150px; height: 120px; overflow: hidden; border-radius: 50%;">
                                <img src="{{ asset('storage/' . $photo_3) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="member-info">
                                <h4>{{ $username_3 }}</h4>
                                <span>{{ $posisi_3 }}</span>
                                <p>{{ $deskripsi_3 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="400">
                        <div class="member d-flex align-items-start">
                            <div class="pic"
                                style="width: 150px; height: 120px; overflow: hidden; border-radius: 50%;">
                                <img src="{{ asset('storage/' . $photo_4) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="member-info">
                                <h4>{{ $username_4 }}</h4>
                                <span>{{ $posisi_4 }}</span>
                                <p>{{ $deskripsi_4 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Team Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Contact</h2>
                </div>

                <div class="row">

                    <div class="col-lg-5 d-flex align-items-stretch">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4>Location:</h4>
                                <p>{{ $name_location }}</p>
                            </div>

                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4>Email:</h4>
                                <p>{{ $email_informasi }}</p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Call:</h4>
                                <p>{{ $call_informasi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
                        <div class="info map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.9602391902004!2d107.53971757403527!3d-6.895359467475242!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e59b48322cdb%3A0x10a755b12e9aef37!2sBITC%20(Baros%20Information%2C%20Technology%2C%20%26%20Creative%20Center!5e0!3m2!1sid!2sid!4v1708416968822!5m2!1sid!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h3>{{ $title_sosmed }}</h3>
                        <p>
                            {{ $street_name }} <br>
                            {{ $ward }}<br>
                            {{ $subdistrict }} <br><br>
                            <strong>Phone:</strong>{{ $call }}<br>
                            <strong>Email:</strong>{{ $email }}<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Our Social Media</h4>
                        <div class="social-links mt-3">
                            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                            <a href="#" class="youtube"><i class="bx bxl-youtube"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container footer-bottom clearfix">
            <div class="copyright">
                &copy; Copyright <strong><span>BARUDAK CIGS</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/ -->
                {{-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> --}}
            </div>
        </div>
    </footer><!-- End Footer -->

    <!-- Vendor JS Files -->
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
