<?php
    # Iniciando la variable de control que permitirá mostrar o no el modal
    if (isset($_GET['legajo'])) {
      $legajo = $_REQUEST['legajo'];
    }

    if(!isset($legajo)) {
      $legajo = null;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SWNov') }}</title>

    <!-- Scripts
    <script src="{{ asset('js/app.js') }}" defer></script>-->


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
    <!-- <link rel="stylesheet" href="{ asset('css/animate.css') }}"> -->
    <!-- WHIRL (spinners)-->
    <!-- <link rel="stylesheet" href=" { asset('css/whirl.css') }}"> -->
    <!-- TAGS INPUT
    <link rel="stylesheet" href="{{ asset('css/bootstrap-tagsinput.css') }}"> -->

    <!-- =============== PAGE VENDOR STYLES ===============-->

    <!-- WHIRL (spinners)
    <link rel="stylesheet" href="vendor/whirl/dist/whirl.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->
    <!-- FULLCALENDAR
    <link rel="stylesheet" href="{ { asset('css/fullcalendar.css') }}"> -->

    <!-- DATETIMEPICKER -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">
    <!-- <link rel="stylesheet" href="{ { asset('css/bootstrap-datetimepicker.min.css') }}"> -->
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" id="maincss">
    <link rel="stylesheet" href="{{ asset('css/theme-g.css') }}" id="maincss">
    {{-- <link rel="stylesheet" href="{{ asset('css/theme-e.css') }}" id="maincss"> --}}
    
    <!-- <link rel="stylesheet" href="{ asset('css/bootstrap-colorpicker.css') }}" id="maincss"> -->

    <!-- AUTOCOMPLETE-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/easy-autocomplete.css') }}">

    @yield('styles')

    <?php
      # Iniciando la variable de control que permitirá mostrar o no el modal
      $exibirModal = true;
      # Verificando si existe o no la cookie
      if(!isset($_COOKIE["mostrarModal"])) {
         # Caso no exista la cookie entra aqui
         # Creamos la cookie con la duración que queramos

         //$expirar = 3600; // muestra cada 1 hora
         //$expirar = 10800; // muestra cada 3 horas
         //$expirar = 21600; //muestra cada 6 horas
         $expirar = 43200; //muestra cada 12 horas
         //$expirar = 86400;  // muestra cada 24 horas
         setcookie('mostrarModal', 'SI', (time() + $expirar)); // mostrará cada 12 horas.
         # Ahora nuestra variable de control pasará a tener el valor TRUE (Verdadero)
         $exibirModal = true;
      }
    ?>
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
               <a class="navbar-brand" href="{{ asset('/home') }}">
                  <div class="brand-logo">
                     {{-- @if ($GLOBALS['cliente_id'] == 1) --}}
                     <img class="img-fluid" src="{{ asset('/img/logo-cpps.jpg') }}" alt="CPPS">
                     {{-- <img class="img-fluid" src="{{ asset('/img/logo-cpps.jpg') }}" alt="CPPS> --}}
                     {{-- @endif --}}
                  </div>
                  <div class="brand-logo-collapsed">
                     {{-- @if ($GLOBALS['cliente_id'] == 1) --}}
                     <img class="img-fluid" src="/img/logo-cpps.jpg" alt="CPPS">
                     {{-- @endif
                     @if ($GLOBALS['cliente_id'] == 4)
                        <img class="img-fluid" src="/img/logo-cpps.jpg" alt="CPPS">
                     @endif --}}
                  </div>
               </a>
            </div>
            <!-- END navbar header-->
            <!-- START Left navbar-->
            <ul class="navbar-nav mr-auto flex-row">
               <li class="nav-item">
                  <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                  <a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize="" data-toggle-state="aside-collapsed">
                     <em class="fa fa-navicon"></em>
                  </a>
                  <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                  <a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled" data-no-persist="true">
                     <em class="fa fa-navicon"></em>
                  </a>
               </li>
               <!-- START User avatar toggle-->
               <li class="nav-item d-none d-md-block">
                  <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                  <a class="nav-link" id="user-block-toggle" href="#user-block" data-toggle="collapse">
                     <em class="icon-user"></em>
                  </a>
               </li>
               <!-- END User avatar toggle-->
               <!-- START lock screen-->
               <li class="nav-item d-none d-md-block">
                  <a class="nav-link" href="{{ asset('/') }}" title="Cerrar sesión">
                     <em class="icon-lock"></em>
                  </a>
               </li>
               <!-- END lock screen-->

               <li class="nav-item">
                  <a class="nav-link" href="{{ url()->current() }}/search" >  <!-- data-search-open=""         url()->current()   -->
                     <em class="icon-magnifier"></em>
                  </a>
               </li>

            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="navbar-nav flex-row">
               <!-- Search icon-->
               <li class="nav-item">
                  @if(($active>=60 and $active<=64) or $active==32)
                  @else
                     <a class="nav-link" href="{{ url()->current() }}/search" >  <!-- data-search-open=""         url()->current()   -->
                        <em class="icon-magnifier"></em>
                     </a>
                  @endif
               </li>
               <!-- Fullscreen (only desktops)-->
               <li class="nav-item d-none d-md-block">
                  <a class="nav-link" href="#" data-toggle-fullscreen="">
                     <em class="fa fa-expand"></em>
                  </a>
               </li>


               <!-- START menu-->
               <!-- <li class="nav-item dropdown dropdown-list">
                  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-toggle="dropdown">
                     <em class="icon-bell"></em>
                     <span class="badge badge-danger">11</span>
                  </a>
                  <!-- START Dropdown menu-->
                  <div class="dropdown-menu dropdown-menu-right animated flipInX">
                     <div class="dropdown-item">
                        <!-- START list group-->
                        <div class="list-group">
                           <!-- list item-->
                           <div class="list-group-item list-group-item-action">
                              <div class="media">
                                 <div class="align-self-start mr-2">
                                    <em class="fa fa-twitter fa-2x text-info"></em>
                                 </div>
                                 <div class="media-body">
                                    <p class="m-0">New followers</p>
                                    <p class="m-0 text-muted text-sm">1 new follower</p>
                                 </div>
                              </div>
                           </div>
                           <!-- list item-->
                           <div class="list-group-item list-group-item-action">
                              <div class="media">
                                 <div class="align-self-start mr-2">
                                    <em class="fa fa-envelope fa-2x text-warning"></em>
                                 </div>
                                 <div class="media-body">
                                    <p class="m-0">New e-mails</p>
                                    <p class="m-0 text-muted text-sm">You have 10 new emails</p>
                                 </div>
                              </div>
                           </div>
                           <!-- list item-->
                           <div class="list-group-item list-group-item-action">
                              <div class="media">
                                 <div class="align-self-start mr-2">
                                    <em class="fa fa-tasks fa-2x text-success"></em>
                                 </div>
                                 <div class="media-body">
                                    <p class="m-0">Pending Tasks</p>
                                    <p class="m-0 text-muted text-sm">11 pending task</p>
                                 </div>
                              </div>
                           </div>
                           <!-- last list item-->
                           <div class="list-group-item list-group-item-action">
                              <span class="d-flex align-items-center">
                                 <span class="text-sm">More notifications</span>
                                 <span class="badge badge-danger ml-auto">14</span>
                              </span>
                           </div>
                        </div>
                        <!-- END list group-->
                     </div>
                  </div>
                  <!-- END Dropdown menu-->
               </li>
               <!-- END menu-->



               <!-- START Offsidebar button-->
               <li class="nav-item">
                  @if($active != 2 and $active != 5 and $active != 8 and $active != 11 and $active != 12
                                 and $active != 20 and $active!=30 and $active!=81 and $active!=82
                                 and $active!=83 and $active!=84)
                     <a class="nav-link" href="#" data-toggle-state="offsidebar-open" data-no-persist="true">
                        <em class="icon-notebook"></em>
                     </a>
                  @else
                     <a class="nav-link" href="#" data-toggle-state="" data-no-persist="true">

                     </a>
                  @endif
               </li>
               <!-- END Offsidebar menu-->
            </ul>
            <!-- END Right Navbar-->
            <!-- START Search form-->
            <form class="navbar-form" role="search" action="/home/search">
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
      <!-- sidebar-->
      <aside class="aside-container">
         <!-- START Sidebar (left)-->
         <div class="aside-inner">
            <nav class="sidebar" data-sidebar-anyclick-close="">
               <!-- START sidebar nav-->
               <ul class="sidebar-nav">
                  <!-- START user info-->
                  <li class="has-user-block">
                     <div class="collapse" id="user-block">
                        <div class="item user-block">
                           <!-- User picture-->
                           <div class="user-block-picture">
                              <div class="user-block-status">
                                 <a href="{{ asset('/users') }}">
                                 <img class="img-thumbnail rounded-circle" src="{{ asset('img/user/15.jpg') }}" alt="Usuario" width="60" height="60"></a>
                                 <div class="circle bg-success circle-lg"></div>
                              </div>
                           </div>
                           <!-- Name and Job-->
                           <div class="user-block-info">
                              <span class="user-block-name">Hola, </span>
                              {{ auth()->user()?auth()->user()->name:'Usuario' }}
                              <!-- <span class="user-block-role">Designer</span> -->
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- END user info-->
                  <!-- Iterates over all sidebar items-->
                  <li class="nav-heading">
                     <span data-localize="">Archivos</span>  <!-- data-localize="sidebar.heading.HEADER" -->
                  </li>

                  @if (auth()->user()->rol == "ADMINISTRADOR" )
                  <li class=" {{ $active==111?'active':' ' }} ">
                     <a href="{{ asset('/home') }}" title="Legajos" title="legajos">
                        <!-- <div class="float-right badge badge-success">3</div> -->
                        <em class="icon-user"></em>
                        <span>Clientes activos</span> <!-- <span data-localize="sidebar.nav.DASHBOARD">  -->
                     </a>
                  </li>
                  <li class=" {{ ($active==2)?'active':' ' }} ">
                    <a href="{{ asset('/bajas') }}" title="Bajas" title="bajas">
                        <!-- <div class="float-right badge badge-success">3</div> -->
                        <em class="icon-user-unfollow"></em>
                        <span>Clientes inactivos</span>    <!-- <span data-localize="sidebar.nav.DASHBOARD">Legajos de baja</span> -->
                     </a>
                  </li>
                  <li class=" {{ ($active==3)?'active':' ' }} ">
                     <a href="{{ asset('/vehiculos') }}" title="Vehiculos" title="vehiculos">
                         <!-- <div class="float-right badge badge-success">3</div> -->
                         <em class="icon-user-unfollow"></em>
                         <span>Vehiculos</span>    <!-- <span data-localize="sidebar.nav.DASHBOARD">Legajos de baja</span> -->
                      </a>
                   </li>
                  <li class=" ">
                     <a href="#layout" title="Layouts" data-toggle="collapse">
                        <em class="icon-layers"></em>
                        <span>Parámetros ...</span>
                     </a>
                     <ul class="sidebar-nav sidebar-subnav collapse {{ ($active>=3 and $active<=22)?'show':' ' }}" id="layout">
                        <li class="sidebar-subnav-header">Layouts</li>
                        
                        <li class=" {{ $active==8?'active':' ' }} ">
                           <a href="{{ asset('/datosempresa') }}" title="Datos de la empresa">
                              <span>Datos de la empresa</span>
                           </a>
                        </li>

                        
                        <li class=" {{ $active==22?'active':' ' }} ">
                           <a href="{{ asset('/bancos') }}" title="Bancos">
                              <span>Bancos</span>
                           </a>
                        </li>

                     </ul>
                  </li>
                  @endif
                  <li class="nav-heading">
                     <span data-localize="sidebar.heading.COMPONENTS">Novedades</span>
                     <!-- Novedades -->
                  </li>
                  <li class=" ">
                     <li class=" {{ $active==70?'active':' ' }} ">
                        <a href="{{ asset('/ordenes') }}" title="Ordenes de trabajo" data-toggle="">
                           <em class="icon-graph"></em>
                           <span data-localize="sidebar.nav.chart.CHART">Ordenes de trabajo</span>
                        </a>
                     </li>
                  </li>

                  @if (auth()->user()->rol == "ADMINISTRADOR" )
                  <li class=" {{ $active==72?'active':' ' }} ">
                     <a href="{{ asset('/facturacion') }}" title="Facturacion" data-toggle="">
                        <em class="icon-chart"></em>
                        <span data-localize="sidebar.nav.chart.CHART">Facturacion</span>
                     </a>
                  </li>

                  <li class=" {{ $active==72?'active':' ' }} ">
                     <a href="{{ asset('/ctasctes') }}" title="Cuentas Corrientes" data-toggle="">
                        <em class="icon-chart"></em>
                        <span data-localize="sidebar.nav.chart.CHART">Cuentas Corrientes</span>
                     </a>
                  </li>
                  @endif

                  <li class=" ">
                     <a href="#tables" title="Tables" data-toggle="collapse">
                        <em class="icon-printer  {{ $active==100?'active':' ' }} "></em>
                        <span data-localize="sidebar.nav.table.TABLE">Informes</span>
                     </a>
                     <ul class="sidebar-nav sidebar-subnav collapse {{ ($active>=100 and $active<=150)?'show':' ' }}" id="tables">
                        <li class=" {{ $active==100?'active':' ' }} ">
                           <a href="{{ asset('/inflegajos') }}" title="Informe de Legajos">
                              <span data-localize="sidebar.nav.table.STANDARD">Clientes</span>
                           </a>
                        </li>
                        <li class=" {{ $active==120?'active':' ' }} ">
                           <a href="{{ asset('/infnovedades') }}" title="Informe de Novedades">
                              <span data-localize="sidebar.nav.table.EXTENDED">Vehiculos</span>
                           </a>
                        </li>
                        <li class=" {{ $active==125?'active':' ' }} ">
                           <a href="{{ asset('/infpresentismo') }}" title="Informe de Presentismo">
                              <span data-localize="sidebar.nav.table.EXTENDED">Ordenes de Trabajo</span>
                           </a>
                        </li>
                        <li class=" {{ $active==130?'active':' ' }} ">
                           <a href="{{ asset('/infhistoricos') }}" title="Informe de Históricos">
                              <span data-localize="sidebar.nav.table.EXTENDED">Facturacion</span>
                           </a>
                        </li>
                        <li class=" {{ $active==140?'active':' ' }} ">
                           <a href="{{ asset('/inffichadas') }}" title="Informe de Fichadas">
                              <span data-localize="sidebar.nav.table.EXTENDED">Cuentas corrientes</span>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <!-- <li class=" ">
                     <a href="#maps" title="Maps" data-toggle="collapse">
                        <em class="icon-map"></em>
                        <span data-localize="sidebar.nav.map.MAP">Importación/Exportación</span>
                     </a>
                     <ul class="sidebar-nav sidebar-subnav collapse" id="maps">
                        <li class="sidebar-subnav-header">Maps</li>
                        <li class=" ">
                           <a href="maps-google.html" title="Google Maps">
                              <span data-localize="sidebar.nav.map.GOOGLE">Google Maps</span>
                           </a>
                        </li>
                        <li class=" ">
                           <a href="maps-vector.html" title="Vector Maps">
                              <span data-localize="sidebar.nav.map.VECTOR">Vector Maps</span>
                           </a>
                        </li>
                     </ul>
                  </li>-->

               </ul>
               <!-- END sidebar nav-->
            </nav>
         </div>
         <!-- END Sidebar (left)-->
      </aside>
      <!-- offsidebar-->
      <aside class="offsidebar d-none">
         <!-- START Off Sidebar (right)-->
         <nav>
            <div role="tabpanel">
               <!-- Nav tabs-->
               <ul class="nav nav-tabs nav-justified" role="tablist">
                  <li class="nav-item" role="presentation">
                     <a class="nav-link active" href="#app-settings" aria-controls="app-settings" role="tab" data-toggle="tab">
                        <em class="icon-equalizer fa-lg"></em>
                     </a>
                  </li>
                  <li class="nav-item" role="presentation">
                     <!-- <a class="nav-link" href="#app-chat" aria-controls="app-chat" role="tab" data-toggle="tab">
                        <em class="icon-user fa-lg"></em>
                     </a> -->
                  </li>
               </ul>
               <!-- Tab panes-->
               <div class="tab-content">
                  <div class="tab-pane fade active show" id="app-settings" role="tabpanel">
                     <h3 class="text-center text-thin mt-4">Otros comandos</h3>
                     <div class="p-2">
                        <!--
                        <h4 class="text-muted text-thin">Themes</h4>
                        <div class="row row-flush mb-2">
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="css/theme-a.css">
                                    <input type="radio" name="setting-theme" checked="checked">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-info"></span>
                                       <span class="color bg-info-light"></span>
                                    </span>
                                    <span class="color bg-white"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="css/theme-b.css">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-green"></span>
                                       <span class="color bg-green-light"></span>
                                    </span>
                                    <span class="color bg-white"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-c.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-purple"></span>
                                       <span class="color bg-purple-light"></span>
                                    </span>
                                    <span class="color bg-white"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-d.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-danger"></span>
                                       <span class="color bg-danger-light"></span>
                                    </span>
                                    <span class="color bg-white"></span>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="row row-flush mb-2">
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-e.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-info-dark"></span>
                                       <span class="color bg-info"></span>
                                    </span>
                                    <span class="color bg-gray-dark"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-f.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-green-dark"></span>
                                       <span class="color bg-green"></span>
                                    </span>
                                    <span class="color bg-gray-dark"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-g.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-purple-dark"></span>
                                       <span class="color bg-purple"></span>
                                    </span>
                                    <span class="color bg-gray-dark"></span>
                                 </label>
                              </div>
                           </div>
                           <div class="col mb-2">
                              <div class="setting-color">
                                 <label data-load-css="{{ asset('css/theme-h.css') }}">
                                    <input type="radio" name="setting-theme">
                                    <span class="icon-check"></span>
                                    <span class="split">
                                       <span class="color bg-danger-dark"></span>
                                       <span class="color bg-danger"></span>
                                    </span>
                                    <span class="color bg-gray-dark"></span>
                                 </label>
                              </div>
                           </div>
                        </div>
                     </div>
                  -->
                     <div class="p-2">
                        <!-- <h4 class="text-muted text-thin">Layout</h4> -->
                        <div class="clearfix">
                        
                           <!-- solamente visualizable desde legajos -->
                           @if($active == 1 or $active == 2)
                              @if ($legajo != null)
                                 <a href="/changenro/{{ $legajo->id }}"> <!-- /home/importar/legajos -->
                                    <p class="float-left">Cambiar N° legajo </p>
                                 </a>
                              @endif
                           @endif
                        
                        </div>

                        
                        <div class="clearfix">
                           <!-- Excluyo 11:Modalidades de contrataciones -->
                           @if($active != 2 and $active != 5 and $active != 8 and $active != 11 and $active != 12
                                 and $active != 20 and $active!=30 and $active!=81 and $active!=82
                                 and $active!=83 and $active!=84)

                              <a href="{{ Request::url() . '/importar'  }}"> <!-- /home/importar/legajos -->
                                 <p class="float-left">Importar ...</p>
                              </a>
                           @endif

                        </div>

                        <div class="clearfix">
                           @if($active == 1)
                              @if($legajo != null)
                                 <a href="{{ url('/home/ddjjdomicilio/'.$legajo->id) }}">
                                    <p class="float-left">DDJJ Domicilio...</p>
                                 </a>
                              @endif
                           @endif

                        </div>

                        <div class="clearfix">
                           @if( auth()->user()->name == 'Daniel Salazar' )
                              @if($legajo != null)
                                 <a href="{{ url('/checkmemory') }}">
                                    <p class="float-left">Check Memory...</p>
                                 </a>
                              @endif
                           @endif

                        </div>

                     </div>

                  </div>

                  <!--
                  <div class="tab-pane fade" id="app-chat" role="tabpanel">
                     <h3 class="text-center text-thin mt-4">Connections</h3>
                     <div class="list-group">
                        <!-- START list title--> <!--
                        <div class="list-group-item border-0">
                           <small class="text-muted">ONLINE</small>
                        </div>
                        <!-- END list title--> <!--
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/05.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Juan Sims</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-success circle-lg"></span>
                              </div>
                           </div>
                        </div>
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/06.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Maureen Jenkins</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-success circle-lg"></span>
                              </div>
                           </div>
                        </div>
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/07.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Billie Dunn</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-danger circle-lg"></span>
                              </div>
                           </div>
                        </div>
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/08.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Tomothy Roberts</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-warning circle-lg"></span>
                              </div>
                           </div>
                        </div>
                        <!-- START list title--> <!--
                        <div class="list-group-item border-0">
                           <small class="text-muted">OFFLINE</small>
                        </div>
                        <!-- END list title--> <!--
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/09.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Lawrence Robinson</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-warning circle-lg"></span>
                              </div>
                           </div>
                        </div>
                        <div class="list-group-item list-group-item-action border-0">
                           <div class="media">
                              <img class="align-self-center mr-3 rounded-circle thumb48" src="/img/user/10.jpg" alt="Image">
                              <div class="media-body text-truncate">
                                 <a href="#">
                                    <strong>Tyrone Owens</strong>
                                 </a>
                                 <br>
                                 <small class="text-muted">Designeer</small>
                              </div>
                              <div class="ml-auto">
                                 <span class="circle bg-warning circle-lg"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="px-3 py-4 text-center">
                        <!-- Optional link to list more users--> <!--
                        <a class="btn btn-purple btn-sm" href="#" title="See more contacts">
                           <strong>Load more..</strong>
                        </a>
                     </div>
                     <!-- Extra items--> <!--
                     <div class="px-3 py-2">
                        <p>
                           <small class="text-muted">Tasks completion</small>
                        </p>
                        <div class="progress progress-xs m-0">
                           <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                              <span class="sr-only">80% Complete</span>
                           </div>
                        </div>
                     </div>
                     <div class="px-3 py-2">
                        <p>
                           <small class="text-muted">Upload quota</small>
                        </p>
                        <div class="progress progress-xs m-0">
                           <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                              <span class="sr-only">40% Complete</span>
                           </div>
                        </div>
                     </div>
                  </div>

                  -->

               </div>
            </div>
         </nav>
         <!-- END Off Sidebar (right)-->
      </aside>
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
         <span>&copy; 2022 - v. 2.34.105 - Zonda Software</span>
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

   <!-- INPUT MASK-->
   <script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>

   <!-- JQUERY UI Carga en app.blade.php -->
   <script src="{{ asset('vendor/components-jqueryui/jquery-ui.js') }}"></script>
   <script src="{{ asset('vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js') }}"></script>
   <!-- MOMENT JS-->
   <script src="{{ asset('vendor/moment/min/moment-with-locales.js') }}"></script>
   <!-- FULLCALENDAR
   <script src="{ { asset('vendor/fullcalendar/dist/fullcalendar.js') }}"></script>
   <script src="{ { asset('vendor/fullcalendar/dist/gcal.js') }}"></script> -->
   <!-- TAGS INPUT
   <script src="{ { asset('js/bootstrap-tagsinput.js') }}"></script>   -->


   <!-- =============== VENDOR SCRIPTS ===============-->
   <!-- PARSLEY-->
   <script src="{{ asset('js/parsley.js') }}"></script>
   <!-- =============== PAGE VENDOR SCRIPTS ===============-->
   <!-- CHOSEN
   <script src="{ { asset('js/chosen.jquery.js') }}"></script> -->
   <!-- SLIDER CTRL
   <script src="{{ asset('js/bootstrap-slider.js') }}"></script>-->
   <!-- MOMENT JS-->
   <script src="{{ asset('js/moment-with-locales.js') }}"></script>
   <!-- DATETIMEPICKER-->
   <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

   <!-- =============== APP SCRIPTS ===============-->
   <script src="{{ asset('js/app.js') }}"></script>
   <script src="{{ asset('js/jquery.easy-autocomplete.js') }}"></script>

   <script src="{{ asset('js/bootstrap-colorpicker.js') }}"></script>

   <script>
      $(function () {
        $('#cp2, #cp3a, #cp3b').colorpicker();
        $('#cp4').colorpicker({"color": "#16813D"});
      });
   </script>

   <script>
       // Funcion que se ejecuta cada vez que se pulsa una tecla en cualquier input
       // Tiene que recibir el "event" (evento generado) y el siguiente id donde poner
       // el foco. Si ese id es "submit" se envia el formulario
       function saltar(e,id)
       {
        // Obtenemos la tecla pulsada
        (e.keyCode)?k=e.keyCode:k=e.which;

        // Si la tecla pulsada es enter (codigo ascii 13)
        if(k==13)
        {
          // Si la variable id contiene "submit" enviamos el formulario
          if(id=="submit")
          {
            document.forms[0].submit();
          }else{
            // nos posicionamos en el siguiente input
            document.getElementById(id).focus();
          }
        }
       }

       // Funcion que se carga al comienzo usado en easyAutocomplete
       $(document).ready(function () {
            var options = {
                url: "/autocomplete/users",
                getValue: "CONCAT(codigo)",
                template: {
                    type: "description",
                    fields: {
                        description: "detalle"
                    }
                },
                list: {
                    onSelectItemEvent: function() {
                       //var value0 = $("#dni").getSelectedItemData().codigo;
                       var value1 = $("#dni").getSelectedItemData().detalle;
                       var value2 = $("#dni").getSelectedItemData().nombres;
                       var value3 = $("#dni").getSelectedItemData().funcion;
                       var value4 = $("#dni").getSelectedItemData().cuil;
                       var value5 = $("#dni").getSelectedItemData().domici;
                       var value6 = $("#dni").getSelectedItemData().codsector;
                       var value7 = $("#dni").getSelectedItemData().id;

                       $("#ape_nom").val(value1).trigger("change");
                       $("#detalle").val(value1).trigger("change");
                       $("#razonsoc").val(value1).trigger("change");
                       $("#domici").val(value5).trigger("change");
                       $("#tarea").val(value3).trigger("change");
                       $("#calificacion").val(value3).trigger("change");
                       $("#cuil").val(value4).trigger("change");
                       $("#id_empleado").val(value7).trigger("change");
                    },
                    match: {
                        enabled: true
                    }
                },
                theme: "bootstrap",
            };
            var optionsAjax = {
                url: "/autocomplete/users",
                getValue: "CONCAT(codigo)",
                template: {
                    type: "description",
                    fields: {
                        description: "detalle"
                    }
                },
                list: {
                  onSelectItemEvent: function() {
                     //var value0 = $("#dni").getSelectedItemData().codigo;
                     var value1 = $("#dni").getSelectedItemData().detalle;
                     var value2 = $("#dni").getSelectedItemData().nombres;
                     var value3 = $("#dni").getSelectedItemData().funcion;
                     var value4 = $("#dni").getSelectedItemData().cuil;
                     var value5 = $("#dni").getSelectedItemData().codsector;
                     var value7 = $("#dni").getSelectedItemData().id;

                     //alert(value0);

                     $("#ape_nom").val(value1).trigger("change");
                     $("#detalle").val(value1).trigger("change");
                     $("#razonsoc").val(value1).trigger("change");
                     $("#domic").val(value2).trigger("change");
                     $("#tarea").val(value3).trigger("change");
                     $("#cuil").val(value4).trigger("change");
                     $("#sector").val(value5).trigger("change");
                     $("#id_empleado").val(value7).trigger("change");
                  },
                    match: {
                        enabled: true
                    }
                },
                theme: "bootstrap",
                ajaxSettings: {
                    dataType: "json",
                    method: "GET",
                    data: {
                    }
                },
                preparePostData: function(data) {
                    data.term = $("#dni").val();
                    return data;
                },
                requestDelay: 500
            };
            $("#dni").easyAutocomplete(options);

            $("#dni2").easyAutocomplete(optionsAjax); // Agregue ahora

            //-----------------------------------------
            // Busqueda de codigos de novedad
            //-----------------------------------------
            var optionsNoved = {
                url: "/autocomplete/novedades",
                getValue: "codigo",
                template: {
                    type: "description",
                    fields: {
                        description: "detalle"
                    }
                },
                list: {
                    onSelectItemEvent: function() {
                        var value0 = $("#cod_nov").getSelectedItemData().codigo;
                        var value1 = $("#cod_nov").getSelectedItemData().detalle;
                        var value2 = $("#cod_nov").getSelectedItemData().lote;

                        $("#cod_nov3").val(value0).trigger("change");
                        $("#TipoNovedad").val(value2).trigger("change");              // Cambio dinamico
                        $("#CodNovName").val(value1).trigger("change");
                    },
                    match: {
                        enabled: true
                    }
                },
                theme: "bootstrap",
            };

            var optionsNovedAjax = {
                url: "/autocomplete/novedades",
                getValue: "codigo",
                template: {
                    type: "description",
                    fields: {
                        description: "detalle"
                    }
                },
                list: {
                  onSelectItemEvent: function() {
                    var value1 = $("#cod_nov").getSelectedItemData().detalle;
                    //var value2 = $("#cod_nov").getSelectedItemData().tipo;
                    //$("#TipoNovedad").val(value2).trigger("change");
                    $("#CodNovName").val(value1).trigger("change");
                  },
                    match: {
                        enabled: true
                    }
                },
                theme: "bootstrap",
                ajaxSettings: {
                    dataType: "json",
                    method: "GET",
                    data: {
                    }
                },
                preparePostData: function(data) {
                    data.term = $("#cod_nov").val();
                    return data;
                },
                requestDelay: 500
            };

            $("#cod_nov").easyAutocomplete(optionsNoved);
            $("#cod_nov2").easyAutocomplete(optionsNovedAjax);
        });

        $(":input").inputmask();
        $("#phone").inputmask({"mask": "(999) 999-9999"});


        function calcularHoras() {
            var startTime = document.getElementById("entrada1");
            var endTime = document.getElementById("salida1");

            //startTime.value = startTime.value + ':00';
            //endTime.value = endTime.value + ':00';

            var hora1 = (endTime.value).split(":"),
                hora2 = (startTime.value).split(":"),
                t1 = new Date(),
                t2 = new Date();

            t1.setHours(hora1[0], hora1[1]);
            t2.setHours(hora2[0], hora2[1]);

            //Aquí hago la resta
            t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());

            //Imprimo el resultado

            //var diferencia = (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? " horas" : " hora") : "") + (t1.getMinutes() ? ", " + t1.getMinutes() + (t1.getMinutes() > 1 ? " minutos" : " minuto") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " y " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? " segundos" : " segundo") : "");
            if (t1.getHours() < 10) {
                var diferencia = "0" + (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? ":" : ":") : "") + (t1.getMinutes() ? "" + t1.getMinutes() + (t1.getMinutes() > 1 ? "" : "") : "00") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : "") : "");
            } else {
                var diferencia = (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? ":" : ":") : "") + (t1.getMinutes() ? "" + t1.getMinutes() + (t1.getMinutes() > 1 ? "" : "") : "00") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : "") : "");
            }

            //---------------------------------------------
            //            Turno tarde
            //---------------------------------------------

            var startTime2 = document.getElementById("entrada2");
            var endTime2 = document.getElementById("salida2");

            //startTime.value = startTime.value + ':00';
            //endTime.value = endTime.value + ':00';

            var horat1 = (endTime2.value).split(":"),
                horat2 = (startTime2.value).split(":"),
                tt1 = new Date(),
                tt2 = new Date();

            tt1.setHours(horat1[0], horat1[1]);
            tt2.setHours(horat2[0], horat2[1]);

            //Aquí hago la resta
            tt1.setHours(tt1.getHours() - tt2.getHours(), tt1.getMinutes() - tt2.getMinutes(), tt1.getSeconds() - tt2.getSeconds());

            //Imprimo el resultado

            //var diferencia = (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? " horas" : " hora") : "") + (t1.getMinutes() ? ", " + t1.getMinutes() + (t1.getMinutes() > 1 ? " minutos" : " minuto") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " y " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? " segundos" : " segundo") : "");
            if (tt1.getHours() < 10) {
                var difTarde = "0" + (tt1.getHours() ? tt1.getHours() + (tt1.getHours() > 1 ? ":" : ":") : "") + (tt1.getMinutes() ? "" + tt1.getMinutes() + (tt1.getMinutes() > 1 ? "" : "") : "00") + (tt1.getSeconds() ? (tt1.getHours() || tt1.getMinutes() ? " " : "") + tt1.getSeconds() + (tt1.getSeconds() > 1 ? "" : "") : "");
            } else {
                var difTarde = (tt1.getHours() ? tt1.getHours() + (tt1.getHours() > 1 ? ":" : ":") : "") + (tt1.getMinutes() ? "" + tt1.getMinutes() + (tt1.getMinutes() > 1 ? "" : "") : "00") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : "") : "");
            }

            if ((t1.getHours() + tt1.getHours()) < 10) {
                var totalhs = "0" + (t1.getHours() + tt1.getHours()) + ":";
            } else {
                var totalhs = (t1.getHours() + tt1.getHours()) + ":";
            }

            if (tt1.getMinutes() + t1.getMinutes() < 10) {
                totalhs = totalhs + "0" + (tt1.getMinutes() + t1.getMinutes());
            } else {
                totalhs = totalhs + (tt1.getMinutes() + t1.getMinutes());
            }

            $("#totalhs").val(totalhs).trigger("change");
        }


        //-------------------------------------------
        // Almaceno el campo fecha en la session
        //-------------------------------------------
        function save_date(e) {
           var fecha_save = e.value;

           $.ajax({
               url: "/save_date",
               data: "fecha_save="+fecha_save,
               dataType: "json",
               type: "GET", // o GET, o PUT, PATCH, etc...
               success: function(response) {
                  /*
                     Lo que quieras que se ejecute si todo estuvo bien en la ejecución PHP
                  */
               }
           })
        }


        function calcularDias() {
            var fecha1 = document.getElementById("fecha");
            var fecha2 = document.getElementById("fecha2");

            let f1 = fecha1.value
            let f2 = fecha2.value

            var aFecha1 = f1.split('/');
            var aFecha2 = f2.split('/');
            var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
            var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
            var dif = fFecha2 - fFecha1;
            var dias = Math.floor(dif / (1000 * 60 * 60 * 24));

            dias++;
            
            $("#dias").val(dias).trigger("change");
        }


        function tipoNovedad2(novedad) {

           var select = document.getElementById('cod_nov3');
           //var tipo   = document.getElementById('cod_nov3');
           var option = select.options[select.selectedIndex];
           var novedad = document.getElementById('cod_nov').value = option.value;
           
           // Llamada Ajax
           $.ajax({
               url: "/codnoved/grupo/" + novedad,
               data: "id="+novedad+"&_token={{ csrf_token()}}",
               dataType: "json",
               method: "GET",
               success: function(result) {
                  
                  if (result['result'] == 'cancel') {
                     swal("El legajo no puede darse de baja!", "No se encontro el registro asociado...")
                  } else {
                     var $id = result.id;

                     document.getElementById("TipoNovedad").value = result.tipo;
                     document.getElementById("LoteNovedad").value = result.lote;

                     switch (result.tipo) {
                        case "Ausencias Justificadas":
                           document.getElementById("div_fecha_siniestro").setAttribute("hidden","true");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           document.getElementById("btncalendar2").setAttribute("hidden","true");
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").setAttribute("hidden","true");
                           document.getElementById("divconcepto").setAttribute("hidden","true");
                           document.getElementById("lblconcepto").setAttribute("hidden","true");
                           document.getElementById("concepto").setAttribute("hidden","true");
                           document.getElementById("divconcepto").setAttribute("hidden","true");
                           document.getElementById("anio").setAttribute("hidden","true");
                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("divconcepto2").setAttribute("hidden","true");
                           document.getElementById("divdiagnostico").setAttribute("hidden","true");
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;
                           //2
                        case "Otras Licencias":
                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#lbldias").show();
                           $("#dias").show();

                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("divconcepto").removeAttribute("hidden");
                           document.getElementById("div_fecha_siniestro").setAttribute("hidden","true");
                           document.getElementById("fecha_sin").setAttribute("hidden","true");
                           $("#lblconcepto").hide();
                           $("#concepto").hide();
                           $("#anio").hide();

                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;
                        case "Vacaciones":
                           document.getElementById('lblconcepto').innerHTML= 'Año a imputar';
                           document.getElementById('lblconcepto').removeAttribute("hidden");
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("divconcepto").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");

                           document.getElementById("concepto").setAttribute("hidden","true");
                           
                           document.getElementById("anio").removeAttribute("hidden");
                           document.getElementById("anio").removeAttribute("style");      // style="display: none;"

                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();

                           break;
                        case "Jornada doble de trabajo (c/cambio)":
                           document.getElementById('lblconcepto').innerHTML= 'N° Planilla';
                           //document.getElementById("datetimepicker2").removeAttribute("hidden");
                           //document.getElementById("lblfecha2").removeAttribute("hidden");
                           //document.getElementById("fecha2").removeAttribute("hidden");
                           //document.getElementById("lbldias").removeAttribute("hidden");
                           //document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           $("#btncalendar2").hide();
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").removeAttribute("hidden");

                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").removeAttribute("hidden");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           //$("#lblfecha2").show();
                           //$("#fecha2").show();
                           //$("#btncalendar2").show();
                           //$("#lbldias").show();
                           //$("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;
                           
                        case "Jornada doble de trabajo":
                           document.getElementById('lblconcepto').innerHTML= 'N° Planilla';
                           //document.getElementById("datetimepicker2").removeAttribute("hidden");
                           //document.getElementById("lblfecha2").removeAttribute("hidden");
                           //document.getElementById("fecha2").removeAttribute("hidden");
                           //document.getElementById("lbldias").removeAttribute("hidden");
                           //document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           $("#btncalendar2").hide();
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").setAttribute("hidden","true");

                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").removeAttribute("hidden");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           //$("#lblfecha2").show();
                           //$("#fecha2").show();
                           //$("#btncalendar2").show();
                           //$("#lbldias").show();
                           //$("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;
                        
                        case "Franco compensatorio":
                           $("#divconcepto").hide();
                           $("#divconcepto2").hide();
                           $("#divdiagnostico").hide();

                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");

                           document.getElementById('lblconcepto').innerHTML= 'N° Planilla';
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           $("#btncalendar2").hide();
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").setAttribute("hidden","true");

                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").removeAttribute("hidden");

                           document.getElementById("div-historial").removeAttribute("hidden");

                           //$("#lblfecha2").show();
                           //$("#fecha2").show();
                           //$("#btncalendar2").show();
                           //$("#lbldias").show();
                           //$("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;

                        
                        case "Franco compensatorio (c/cambio)":
                           $("#divconcepto").hide();
                           $("#divconcepto2").hide();
                           $("#divdiagnostico").hide();

                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");

                           document.getElementById('lblconcepto').innerHTML= 'N° Planilla';
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           $("#btncalendar2").hide();
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").setAttribute("hidden","true");

                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").removeAttribute("hidden");

                           document.getElementById("div-historial").removeAttribute("hidden");

                           //$("#lblfecha2").show();
                           //$("#fecha2").show();
                           //$("#btncalendar2").show();
                           //$("#lbldias").show();
                           //$("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;

                        
                        case "Franco compensatorio (Domingo)":
                           $("#divconcepto").hide();
                           $("#divconcepto2").hide();
                           $("#divdiagnostico").hide();

                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");

                           document.getElementById('lblconcepto').innerHTML= 'N° Planilla';
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblfecha2").setAttribute("hidden","true");
                           document.getElementById("fecha2").setAttribute("hidden","true");
                           $("#btncalendar2").hide();
                           document.getElementById("lbldias").setAttribute("hidden","true");
                           document.getElementById("dias").setAttribute("hidden","true");

                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").removeAttribute("hidden");

                           document.getElementById("div-historial").removeAttribute("hidden");

                           //$("#lblfecha2").show();
                           //$("#fecha2").show();
                           //$("#btncalendar2").show();
                           //$("#lbldias").show();
                           //$("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");

                           break;
                        
                        case "Accidentes":
                           $("#divconcepto2").hide();
                           $("#divdiagnostico").hide();
                           document.getElementById("div_fecha_siniestro").removeAttribute("hidden");
                           document.getElementById("fecha_sin").removeAttribute("disabled");
                           document.getElementById("fecha_sin").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById('lblconcepto').innerHTML= 'Nro.Siniestro';
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           
                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#lblconcepto").show();
                           $("#concepto").show();
                           $("#anio").hide();

                           break;
                        case "Enfermedades":
                           $("#divconcepto").hide();
                           $("#divconcepto2").hide();
                           $("#div-historial").hide();

                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("divdiagnostico").removeAttribute("hidden");
                           document.getElementById("lbldiagnostico").removeAttribute("hidden");
                           document.getElementById("diagnostico").removeAttribute("hidden");

                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#divdiagnostico").show();

                           //document.getElementById('lblconcepto').innerHTML= 'Nro.Siniestro';
                           $("#lbldiagnostico").show();
                           $("#diagnostico").show();
                           $("#anio").hide();

                           break;
                        case "Suspenciones":
                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           //document.getElementById("lblconcepto").removeAttribute("hidden");
                           //document.getElementById("concepto").removeAttribute("hidden");

                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#anio").hide();

                           break;
                           //1
                        case "Suspensiones":
                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           //document.getElementById("lblconcepto").removeAttribute("hidden");
                           //document.getElementById("concepto").removeAttribute("hidden");

                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#anio").hide();

                           break;
                           //1
                        case "Guardas de Puesto":
                           document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");
                           document.getElementById("datetimepicker2").removeAttribute("hidden");
                           document.getElementById("lblfecha2").removeAttribute("hidden");
                           document.getElementById("fecha2").removeAttribute("hidden");
                           document.getElementById("lbldias").removeAttribute("hidden");
                           document.getElementById("dias").removeAttribute("hidden");
                           document.getElementById("lblconcepto").removeAttribute("hidden");
                           document.getElementById("concepto").removeAttribute("hidden");
                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("div-historial").removeAttribute("hidden");

                           $("#lblfecha2").show();
                           $("#fecha2").show();
                           $("#btncalendar2").show();
                           $("#lbldias").show();
                           $("#dias").show();
                           $("#divdiagnostico").hide();
                           $("#lblconcepto").hide();
                           $("#concepto").hide();
                           document.getElementById("div-historial").setAttribute("hidden","true");
                           $("#anio").hide();

                           break;
                        default:
                           //alert('default');
                           document.getElementById("divcantidad").setAttribute("hidden","false");
                           document.getElementById("divcantidad").removeAttribute("hidden");
                           document.getElementById("lblCantidad").setAttribute("hidden","false");
                           document.getElementById("lblCantidad").removeAttribute("hidden");
                           document.getElementById("cantidad").setAttribute("hidden","false");
                           document.getElementById("cantidad").removeAttribute("hidden");

                           document.getElementById("divconcepto2").setAttribute("hidden" , "true");
                           document.getElementById("div_fecha_siniestro").setAttribute("hidden" , "true");
                           
                           // LOTE
                           if (tipoNovedad == 1) {
                              document.getElementById("div_fecha_siniestro").setAttribute( "hidden", "true");
                              document.getElementById("datetimepicker2").removeAttribute("hidden");
                              document.getElementById("lblfecha2").removeAttribute("hidden");
                              document.getElementById("fecha2").removeAttribute("hidden");
                              document.getElementById("lbldias").removeAttribute("hidden");
                              document.getElementById("dias").removeAttribute("hidden");
                              document.getElementById("lblconcepto").removeAttribute("hidden");
                              document.getElementById("concepto").removeAttribute("hidden");
                              document.getElementById("lblCantidad").setAttribute("hidden","false");
                              document.getElementById("cantidad").setAttribute("hidden","false");
                              document.getElementById("div-historial").removeAttribute("hidden");

                              $("#lblfecha2").show();
                              $("#fecha2").show();
                              $("#btncalendar2").show();
                              $("#lbldias").show();
                              $("#dias").show();
                              $("#lblconcepto").hide();
                              $("#concepto").hide();
                              document.getElementById("div-historial").setAttribute("hidden","true");
                              $("#anio").hide();

                              break;
                           } else {
                              $("#lblfecha2").hide();
                              $("#fecha2").hide();
                           }

                           $("#btncalendar2").hide();
                           $("#lbldias").hide();
                           $("#dias").hide();
                           $("#lblconcepto").hide();
                           $("#concepto").hide();
                           $("#anio").hide();
                           document.getElementById("div-historial").setAttribute("hidden","false");

                           break;
                     }                        
                  }
               },
               fail: function(){
                  swal("Error !", "El legajo no puede darse de baja...");
               },
               beforeSend: function(){

               }
            });
        }
        
        
        function tipoNovedad(e) {
            var novedad = e.value;

            //alert(document.getElementById("cod_nov3").value);
            //alert(novedad);

            tipoNovedad2(novedad);
        }

        
   </script>


   @yield('scripts')

</body>
</html>
