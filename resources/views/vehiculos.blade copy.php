<?php
    # Iniciando la variable de control que permitirá mostrar o no el modal
    if (isset($_GET['exibirModal'])) {
      $exibirModal = $_REQUEST['exibirModal'];

    }

    if(!isset($exibirModal)) {
      $exibirModal = "false";
    }

    if (isset($_GET['dni']) == true) {
        $dni=$_GET['dni'];
    }

    if(!isset($dni)) {
        $dni = "";
    }

    if (isset($_GET['cod_nov2']) == true) {
        $cod_nov=$_GET['cod_nov2'];
    }

    if(!isset($cod_nov)) {
        $cod_nov = "";
    }
?>

<!-- Vista de Legajos Activos -->

@extends('layouts.app')

@section('styles')
@endsection

@section('content')

@if($agregar == true)
    <form method="post" action="{{ asset( url('/vehiculos/add') ) }}" enctype="multipart/form-data">
@else
    <form method="post" action="{{ asset( url('/vehiculos/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
@endif

{{ csrf_field() }}

<div class="content-heading" style="height: 67px;max-height: 90px">
   <div class="col-md-3" style="max-width: 180px;padding-right: 0px;">Vehiculos
      <small></small>
   </div>

  <div class="col-md-6">
        <a href="{{ asset('/vehiculos') }}/{{ $legajo?$legajo->id:'' }}/-1" title="Legajo anterior" style="padding-top: 10px">
            <i class="icon-arrow-left" style="font-size: 90%"></i>
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ asset('/vehiculos') }}/{{ $legajo?$legajo->id:'' }}/1" title="Legajo siguiente">
           <i class="icon-arrow-right" style="font-size: 90%"></i>
        </a>

        &nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;
        
        <a href="{{ asset('/tarja') }}/{{ $legajo?$legajo->id:'' }}" title="Tarja de fichadas" style="padding-top: 10px">
          <i class="icon-clock" style="font-size: 19px"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/novedadesind') }}/{{ $legajo?$legajo->id:'' }}" title="Novedades individuales">
           <i class="icon-calendar" style="font-size: 19px"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/novedadeslist') }}/?codsector=&dni2={{ $legajo?$legajo->codigo:'' }}" title="Novedades por lista">
           <i class="icon-list" style="font-size: 19px"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/historicoacc') }}/?codsector=&dni2={{ $legajo?$legajo->codigo:'' }}&fecha5=&codsector=&order=" title="Historial de accidentes">
           <i class="fa fa-ambulance" style="font-size: 19px;"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/historicovac') }}/?codsector=&dni2={{ $legajo?$legajo->codigo:'' }}&fecha5=&codsector=&order=" title="Historial de Vacaciones">
          <i class="icon-plane" style="font-size: 19px;"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/historicoen') }}/?codsector=&dni2={{ $legajo?$legajo->codigo:'' }}&fecha5=&codsector=&order=" title="Historial de enfermedades">
           <i class="fa fa-stethoscope" style="font-size: 19px;"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/embargos') }}/?codsector=&dni2={{ $legajo?$legajo->codigo:'' }}&fecha5=&codsector=&order=" title="Embargos">
           <i class="icon-docs" style="font-size: 19px;"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ asset('/estadisticas') }}/{{ $legajo?$legajo->id:'' }}" title="Estadisticas del legajo">
           <i class="icon-chart" style="font-size: 19px;"></i>
        </a>
   </div>
   <div class="col-md-4" style="text-align: center;">
       @if($edicion == true)
           <!-- <button class="btn btn-success">Grabar nueva categoría !</button> -->

           <button class="btn btn-labeled btn-success mb-2">
             <span class="btn-label"><i class="fa fa-check"></i>
             </span>Grabar
           </button>

           <a href="{{ asset( url('/vehiculos') ) }}" class="btn btn-labeled btn-danger mb-2">
              <span class="btn-label"><i class="fa fa-times"></i>
              </span>Cancelar
           </a>
      @else
           <a class="btn btn-oval btn-success" href="{{ asset('/vehiculos/add') }}" >Agregar</a>
           <a class="btn btn-oval btn-success" href="{{ asset('/vehiculos/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
           <a title="Dar de Baja al legajo actual" class="btn btn-oval btn-danger" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
               <em class="icon-trash" style="color: white"></em> &nbsp;Borrar
           </a>
      @endif
    </div>
</div>

<div class="col-md-12">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    

    <div class="card mb-3 border-danger">
       <div class="card-header text-white bg-danger">Tractor</div>
       <div class="row">
          <div class="card-body">
              <div class="col-xl-12 col-md-12">
                  <div class="col-md-12">
                       <div class="form-row">
                           
                          <div class="col-lg-4 mb-3">
                            <label class="col-form-label">Cliente</label>
                            <select class="form-control @if ($errors->first('banco')) parsley-error @endif" id="cliente" name="cliente" {{ $edicion?'enabled':'disabled' }}>
                              @foreach ($clientes as $cliente)
                                  <option value = "{{ $cliente->codigo }}"
                                    @if ( old('cliente',$legajo->cliente)  == $cliente->codigo)  selected   @endif  >
                                    {{ $cliente->detalle }}   ({{ $cliente->codigo  }})
                                  </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-1 mb-1">
                          </div>
                          <div class="col-lg-2 mb-2">
                              <label class="col-form-label">Dominio / Patente *</label>
                              <input class="form-control"
                                type="text" name="codigo" id="codigo"
                                {{ $edicion?'':'disabled' }}
                                {{ $agregar?'enabled autofocus=""':'disabled' }}
                                value="{{ old('codigo',$legajo->codigo) }}" maxlength="7" required autocomplete="off">
                          </div>

                          @if ($errors->has('cuil'))
                              <div class="alert alert-danger">
                                  <ul>
                                    <li>{{ $errors->first('cuil') }}</li>
                                  </ul>
                              </div>
                          @endif
                          <div class="col-lg-4 mb-4">
                              &nbsp;
                          </div>
                          <!--
                          <div class="col-lg-3 mb-3">
                              <img class="img-fluid circle" src="/img/personal/5051.jpg" alt="Image"
                                  style="width: 85.99306px; height: 80.99306px;">
                          </div> -->
                     </div>
                  </div>

                  <div class="col-xl-12 col-md-12">
                    <div class="form-row">
                        <div class="col-md-5">
                          <label class="col-form-label">Vehiculo *</label>
                          <input class="form-control" type="text" name="detalle" id="detalle"
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('detalle',$legajo->detalle) }}" required maxlength="35" autocomplete='off'>

                          @if ($errors->has('detalle'))
                              <div class="alert alert-danger">
                                  <ul>
                                      <li>{{ $errors->first('detalle') }}</li>
                                  </ul>
                              </div>
                          @endif
                        </div>

                        <div class="col-md-5">
                            <label class="col-form-label">Modelo *</label>
                            <input class="form-control" type="text" name="modelo"
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('modelo',$legajo->modelo) }}" required maxlength="35" autocomplete='off'>

                            @if ($errors->has('modelo'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $errors->first('modelo') }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                      </div>
                  </div>


                  <div class="col-xl-12 col-md-12">
                    <div class="form-row">
                        <div class="col-md-2">
                          <label class="col-form-label">Año</label>
                          <input class="form-control" type="text" name="anio" id="anio"
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('anio',$legajo->anio) }}" maxlength="4" autocomplete='off'>

                          @if ($errors->has('anio'))
                              <div class="alert alert-danger">
                                  <ul>
                                      <li>{{ $errors->first('anio') }}</li>
                                  </ul>
                              </div>
                          @endif
                        </div>

                        
                      </div>
                  </div>

                  <div class="col-xl-12 col-md-12">
                    <div class="form-row">
                        <div class="col-md-5">
                          <label class="col-form-label">Motor</label>
                          <input class="form-control" type="text" name="motor" id="motor"
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('motor',$legajo->motor) }}" maxlength="35" autocomplete='off'>

                          @if ($errors->has('motor'))
                              <div class="alert alert-danger">
                                  <ul>
                                      <li>{{ $errors->first('motor') }}</li>
                                  </ul>
                              </div>
                          @endif
                        </div>

                        <div class="col-md-5">
                            <label class="col-form-label">Chasis</label>
                            <input class="form-control" type="text" name="chasis"
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('chasis',$legajo->chasis) }}" maxlength="35" autocomplete='off'>

                            @if ($errors->has('chasis'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $errors->first('chasis') }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                      </div>
                  </div>
              
               
                </div>
          </div>


          <!-- </div de widget de imagenes > -->
       </div>
    </div>


    <div class="card mb-3 border-danger">
        <div class="card-header text-white bg-danger">Equipo / Acoplado</div>
        <div class="row">
           <div class="card-body">
               <div class="col-xl-12 col-md-12">
                   <div class="col-xl-12 col-md-12">
                     <div class="form-row">
                         <div class="col-md-5">
                           <label class="col-form-label">Detalle</label>
                           <input class="form-control" type="text" name="acop_det" id="acop_det"
                               {{ $edicion?'enabled':'disabled' }}
                               value="{{ old('acop_det',$legajo->acop_det) }}" maxlength="35" autocomplete='off'>
 
                           @if ($errors->has('acop_det'))
                               <div class="alert alert-danger">
                                   <ul>
                                       <li>{{ $errors->first('acop_det') }}</li>
                                   </ul>
                               </div>
                           @endif
                         </div>
 
                         <div class="col-md-2">
                             <label class="col-form-label">Dominio</label>
                             <input class="form-control" type="text" name="acop_dom"
                               {{ $edicion?'enabled':'disabled' }}
                               value="{{ old('acop_dom',$legajo->acop_dom) }}" maxlength="7" autocomplete='off'>
 
                             @if ($errors->has('acop_dom'))
                                 <div class="alert alert-danger">
                                     <ul>
                                         <li>{{ $errors->first('acop_dom') }}</li>
                                     </ul>
                                 </div>
                             @endif
                         </div>
                       </div>
                   </div>
 
 
                   <div class="col-xl-12 col-md-12">
                     <div class="form-row">
                         <div class="col-md-5">
                            <label class="col-form-label">Modelo</label>
                            <input class="form-control" type="text" name="acop_mod" id="acop_mod"
                                {{ $edicion?'enabled':'disabled' }}
                                value="{{ old('acop_mod',$legajo->acop_mod) }}" maxlength="35" autocomplete='off'>
  
                            @if ($errors->has('acop_mod'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $errors->first('acop_mod') }}</li>
                                    </ul>
                                </div>
                            @endif
                         </div>

                         <div class="col-md-2">
                           <label class="col-form-label">Año</label>
                           <input class="form-control" type="text" name="anio" id="anio"
                               {{ $edicion?'enabled':'disabled' }}
                               value="{{ old('anio',$legajo->anio) }}" maxlength="4" autocomplete='off'>
 
                           @if ($errors->has('anio'))
                               <div class="alert alert-danger">
                                   <ul>
                                       <li>{{ $errors->first('anio') }}</li>
                                   </ul>
                               </div>
                           @endif
                         </div>
 
                         
                       </div>
                   </div>
 
                   <div class="col-xl-12 col-md-12">
                     <div class="form-row">
                     </div>
                   </div>
               
                
                 </div>
           </div>
 
 
           <!-- </div de widget de imagenes > -->
        </div>
     </div>




    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <!-- Formulario de carga de legajos -->
    <div class="row">
       <div class="col-lg-12">
           <div class="card-footer" style="text-align: right;">
              <div class="col-md-11" style="text-align: right;">
                  @if($edicion == true)
                      <button class="btn btn-labeled btn-success mb-2">
                        <span class="btn-label"><i class="fa fa-check"></i>
                        </span>Grabar
                      </button>

                      <a href="{{ url('/vehiculos') }}" class="btn btn-labeled btn-danger mb-2">
                        <span class="btn-label"><i class="fa fa-times"></i>
                        </span>Cancelar
                      </a>
                @else
                      <a class="btn btn-oval btn-success" href="/vehiculos/add" >Agregar</a>
                      <a class="btn btn-oval btn-success" href="/vehiculos/edit/{{ $legajo->id }}">Editar</a>
                      <a title="Borrar" class="btn btn-oval btn-danger" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                          <em class="fa fa-trash" style="color: white"></em>
                          Dar de Baja
                      </a>
                @endif
              </div>


          </div>

        </div>
        <!-- END card-->

       </div>
    </div>
</div>
</form>

@endsection

@section('scripts')
  <script src="{{ asset('js/sweetalert.min.js') }}"></script>

  <script type="text/javascript">

      function disableClocks(punto) {
        //var punto = document.getElementById("nid").value;

        var lenabled = document.getElementById('reloj_ignora').disabled;

        if (lenabled == false) {
            document.getElementById('reloj_ignora').disabled = true;
            document.getElementById('reloj_ignext').disabled = true;
            document.getElementById('pago_asist').disabled = true;
            document.getElementById('pago_prese').disabled = true;
            document.getElementById('cod_fichad').disabled = true;
            document.getElementById('tomar_sect').disabled = true;
            document.getElementById('tipo_horar').disabled = true;
            document.getElementById('cod_horar').disabled = true;
            document.getElementById('nec_aut_ex').disabled = true;
            document.getElementById('ultima_act').disabled = true;
        } else {
          document.getElementById('reloj_ignora').disabled = false;
          document.getElementById('reloj_ignext').disabled = false;
          document.getElementById('pago_asist').disabled = false;
          document.getElementById('pago_prese').disabled = false;
          document.getElementById('cod_fichad').disabled = false;
          document.getElementById('tomar_sect').disabled = false;
          document.getElementById('tipo_horar').disabled = false;
          document.getElementById('cod_horar').disabled = false;
          document.getElementById('nec_aut_ex').disabled = false;
          document.getElementById('ultima_act').disabled = false;
        }
      }


      function disableClocks2(punto) {
        //var punto = document.getElementById("nid").value;

        var lenabled = document.getElementById('reloj_ignora').disabled;

        if (lenabled == false) {
            document.getElementById('tipo_horar').disabled = true;
            document.getElementById('cod_horar').disabled = true;
            document.getElementById('nec_aut_ex').disabled = true;
            document.getElementById('ultima_act').disabled = true;
        } else {
            document.getElementById('tipo_horar').disabled = false;
            document.getElementById('cod_horar').disabled = false;
            document.getElementById('nec_aut_ex').disabled = false;
            document.getElementById('ultima_act').disabled = false;
        }
      }

      function showModalBorrar(e) {
        var punto = e;
        var id = e;

        $.ajax({
            url: "/vehiculos/delete/" + punto,
            data: "id="+punto+"&_token={{ csrf_token()}}",
            dataType: "json",
            method: "GET",
            success: function(result)
            {
              if (result['result'] == 'cancel') {
                swal("El legajo no puede darse de baja!", "No se encontro el registro asociado...")
              } else {
                var $id = result.id;

                $("#nid").val(result.id);
                $("#legajoEdit").val(result.codigo);
                $("#ApynomEdit").val(result.detalle + ' ' + result.nombres);
                $("#fec_baja").val(result.baja);

                $("#myModalEdit").attr("action","/vehiculos/baja/" + punto);
                $('#myModalEdit').modal('show');
              }
            },
            fail: function(){
                swal("Error !", "El legajo no puede darse de baja...");
            },
            beforeSend: function(){

            }
        });
      }


      
      function showModalMap(e) {
        var punto = e;
        var id = e;

        $("#myModelMap").attr("action","/vehiculos/map/" + punto);
        $('#myModelMap').modal('show');
        
      }

  </script>

  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXsY8Pw5OiJ8fKroVgYHlB9kOe42LunTM"></script>

  <script>
    var textLatitud = $("#latitud").val();
    var textLongitud = $("#longitud").val();

    if (textLatitud == '') {
      textLatitud = -24.789194;
    }
    if (textLongitud == '') {
      textLongitud = -65.410252;
    }
    
    var vMarker
          var map
              map = new google.maps.Map(document.getElementById('map_canvas'), {
                  zoom: 14,
                  center: new google.maps.LatLng(textLatitud, textLongitud),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              });
              vMarker = new google.maps.Marker({
                  position: new google.maps.LatLng(textLatitud, textLongitud),
                  draggable: true
              });
              google.maps.event.addListener(vMarker, 'dragend', function (evt) {
                  $("#latitud").val(evt.latLng.lat().toFixed(6));
                  $("#longitud").val(evt.latLng.lng().toFixed(6));

                  map.panTo(evt.latLng);
              });
              map.setCenter(vMarker.position);
              vMarker.setMap(map);

              //$("#txtCiudad, #txtEstado, #txtDireccion").change(function () {
              //    movePin();
              //});

              $("#btnSearch").click(function () {
                  movePin();
              });

              function movePin() {
              var geocoder = new google.maps.Geocoder();
              var textSelectM = $("#txtCiudad").text();
              var textSelectE = $("#txtEstado").val();
              var inputAddress = $("#txtDireccion").val() + ' ' + textSelectM + ' ' + textSelectE;

              geocoder.geocode({
                  "address": inputAddress
              }, function (results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                      vMarker.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
                      map.panTo(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
                      $("#latitud").val(results[0].geometry.location.lat());
                      $("#longitud").val(results[0].geometry.location.lng());
                  }

              });
          }  
  </script>
@endsection

@include('bajas.modal-confirm')
@include('map')