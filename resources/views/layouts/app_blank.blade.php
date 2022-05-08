<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SWNov') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Angle -->
    <meta name="description" content="SWNov (Sistema web de Novedades)">
    <meta name="keywords" content="SWNov, Novedades personal, sueldos">

    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="{{ asset('css/simple-line-icons.css') }}">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="{{ asset('css/whirl.css') }}">
    <!-- =============== PAGE VENDOR STYLES ===============-->
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" id="maincss">

    @if ($GLOBALS['cliente_id'] == 1)
      <link rel="stylesheet" href="{{ asset('css/theme-g.css') }}" id="maincss">
    @endif
    @if ($GLOBALS['cliente_id'] == 4)
      <link rel="stylesheet" href="{{ asset('css/theme-e.css') }}" id="maincss">
    @endif

</head>
<body>
    <!-- Angle -->
    <div class="wrapper">
      <!-- top navbar-->
      <header class="topnavbar-wrapper">
         <!-- START Top Navbar-->
         <nav class="navbar topnavbar">
            <!-- START navbar header-->
            <div class="navbar-header">
               <a class="navbar-brand" href="#/">
                  <div class="brand-logo">

                     @if ($GLOBALS['cliente_id'] == 1)
                        <img class="img-fluid" src="img/logo_af.bmp" alt="Agrotecnica Fueguina">
                     @endif
                     @if ($GLOBALS['cliente_id'] == 4)
                        <img class="img-fluid" src="img/logo_petro.png" alt="Grupo Petroandina">
                     @endif
                     
                  </div>
                  <div class="brand-logo-collapsed">
                     @if ($GLOBALS['cliente_id'] == 1)
                        <img class="img-fluid" src="img/logo_af.bmp" alt="Agrotecnica Fueguina">
                     @endif
                     @if ($GLOBALS['cliente_id'] == 4)
                        <img class="img-fluid" src="img/logo_af.bmp" alt="Grupo Petroandina">
                     @endif
                  </div>
               </a>
            </div>
            <!-- END navbar header-->
            <!-- START Left navbar-->

            <!-- END Left navbar-->
            <!-- START Right Navbar-->

            <!-- END Right Navbar-->
            <!-- START Search form-->
            <form class="navbar-form" role="search" action="search.html">
               <div class="form-group">
                  <input class="form-control" type="text" placeholder="Type and hit enter ...">
                  <div class="fa fa-times navbar-form-close" data-search-dismiss=""></div>
               </div>
               <button class="d-none" type="submit">Submit</button>
            </form>
            <!-- END Search form-->
         </nav>
         <!-- END Top Navbar-->
      </header>

      <!-- Main section-->
      <section class="section-container">
         <!-- Page content-->
         <div class="content-wrapper">


            <!-- END row-->
            <!-- START row-->
            <!-- <div class="row"> -->
               <!-- <div class="col-lg-12"> -->



                    <!-- Codigo pre-existente -->
                    <!-- <div id="app"> -->
                        <!-- <main class="py-4"> -->
                            @yield('content')
                        <!-- </main> -->
                    <!-- </div> -->
                    <!-- Fin:Codigo pre-existente -->


               <!-- </div> -->
            <!-- </div> -->
            <!-- END row-->
         </div>
      </section>
      <!-- Page footer-->
      <footer class="footer-container">
         <span>&copy; 2021 - v. .88 - Zonda Software</span>
      </footer>
   </div>



    <!-- =============== Angle->VENDOR SCRIPTS ===============-->
    <!-- MODERNIZR-->
    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
   <!-- JQUERY-->
   <script src="{{ asset('js/jquery.js') }}"></script>
   <!-- BOOTSTRAP-->
   <script src="{{ asset('js/popper.js') }}"></script>
   <script src="{{ asset('js/bootstrap.js') }}"></script>
   <!-- STORAGE API-->
   <script src="{{ asset('js/js.storage.js') }}"></script>
   <!-- JQUERY EASING-->
   <script src="{{ asset('js/jquery.easing.js') }}"></script>
   <!-- ANIMO-->
   <script src="{{ asset('js/animo.js') }}"></script>
   <!-- SCREENFULL-->
   <script src="{{ asset('js/screenfull.js') }}"></script>
   <!-- LOCALIZE-->
   <script src="{{ asset('js/jquery.localize.js') }}"></script>
   <!-- =============== PAGE VENDOR SCRIPTS ===============-->
   <!-- PARSLEY-->
   <script src="{{ asset('js/parsley.js') }}"></script>
   <!-- =============== APP SCRIPTS ===============-->
   <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
