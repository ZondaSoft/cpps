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



{{-- Extend Vue Suport --}}
{{-- @extends('layouts.app') --}}

{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Carga de ordenes')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css?v2')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content" style="padding-top: 0px;">
      <!-- users edit account form start -->
      @if($agregar == true)
      <form method="post" action="{{ asset( url('/carga-ordenes/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/carga-ordenes/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Carga de ordenes</h5>
          </div>
          <div class="col m6 s6" style="padding-left: 70px;">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/carga-ordenes') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/carga-ordenes/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/carga-ordenes/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
              <a title="Borrar repuesto" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                  <em class="icon-trash" style="color: white"></em> &nbsp;Borrar
              </a>
            @endif
          </div>
        </div>
        </div>

        
        <div class="divider mb-2"></div>

        @if ($errors->any())
          <div class="card-alert card red">
            <div class="card-content white-text">
                <ul>
                    @foreach ($errors->all() as $error)
                      <p>{{ $error }}</p>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
      @endif

      <div class="row">
        <div class="col s12" id="account">
          
          <div class="row" id="app">
            <div class="s12 m12">
              <div class="form-row">
                <div class="col m2 s2 input-field" style="width: 100px;margin-bottom: 0px;">
                  <input id="periodo" name="periodo" type="text" class="validate" 
                    value="{{ old('periodo',$legajo->periodo) }}"
                    maxlength="7"
                    required
                    autofocus
                    data-error=".errorTxt1">
                  <label for="periodo">Periodo</label>
                  <small class="errorTxt1"></small>
                </div>

                
                <input id="cod_os_original" name="cod_os_original" type="text" autocomplete="off" maxlength="10" value="{{ old('cod_os',$legajo->cod_os) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                <search-ooss style="width: 100px;margin-bottom: 0px;" onchange="changeObra(this)"></search-ooss>
                
                
                <div class="col m4 s4 input-field" style="padding-right: 0px;margin-bottom: 0px;">
                  <select id="det_os" name="det_os" onchange="changeObra2(this)" >
                      <option value = "" @if ( old('obra',$legajo->cod_os)  == "")  selected   @endif  >Seleccione una Obra Social</option>
                      @foreach ($obras as $obra)
                        <option value = "{{ $obra->cod_os  }}" @if ( old('obra',$legajo->cod_os)  == $obra->cod_os)  selected   @endif  >{{ $obra->desc_os }}</option>
                      @endforeach
                  </select>
                  <label>Obra Social</label>
                </div>

                <div class="col m2 s2 input-field" style="margin-bottom: 0px;">
                  <input id="ordenes " name="ordenes" type="number" step="1" class="validate" 
                    value="{{ old('ordenes ',$legajo->ordenes ) }}"
                    disabled
                    maxlength="8" autocomplete='off' required
                    data-error=".errorTxt3">
                  <label for="ordenes">Ordenes</label>
                  <small class="errorTxt3"></small>
                </div>

                <div class="col m2 s2 input-field" style="margin-bottom: 0px;">
                  <input id="importe " name="importe" type="number" step="0.1" class="validate"
                    value="{{ old('importe ',$legajo->importe ) }}"
                    disabled
                    maxlength="8" autocomplete='off' required
                    data-error=".errorTxt4">
                  <label for="importe">Importe</label>
                  <small class="errorTxt4"></small>
                </div>
                
                <!-- FORMACION Y ESPECIALIDADES -->
                <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                  <ul class="collapsible collapsible-accordion" style="margin-bottom: 10px;">
                    <li>
                        <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text" style="padding-bottom: 10px;padding-top: 10px;" disabled>
                          <i class="material-icons">toll</i> Profesional
                        </div>
                        <div class="collapsible-body" style="display: block;padding-top: 15px;padding-bottom: 5px;">
                            <div class="row">

                              <input id="matricula_original" name="matricula_original" type="text" autocomplete="off" maxlength="10" value="{{ old('mat_prov_cole',$legajo->mat_prov_cole) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                              <search-professional onchange="changeProfessional(this)" value="{{ old('mat_prov_cole') }}"></search-professional>

                              <div class="col m7 s7 input-field">
                                <select id="profesional" name="profesional" 
                                    onchange="changeProfessional2(this)" >
                                    <option value = "" @if ( old('profesional',$legajo->mat_prov_cole)  == "")  selected   @endif  >Seleccione un profesional</option>
                                    @foreach ($profesionales as $profesional)
                                      <option value = "{{ $profesional->mat_prov_cole  }}" @if ( old('mat_prov_cole',$legajo->mat_prov_cole)  == $profesional->mat_prov_cole)  selected  @endif  >{{ $profesional->nom_ape }} - ({{ $profesional->mat_prov_cole }})
                                      </option>
                                    @endforeach
                                </select>
                                <label>Apellido y Nombre</label>

                                {{-- <vselect-prof></vselect-prof> --}}
                              </div>

                              

                            </div>
                            
                        </div>
                    </li>
                  </ul>
                </div>

                <!-- FACTURACION -->
                <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                  <ul class="collapsible collapsible-accordion" style="margin-bottom: 5px;">
                    <li>
                        <div class="collapsible-header waves-light gradient-45deg-light-blue-cyan lightrn-1 white-text" style="padding-bottom: 10px;padding-top: 10px;">
                          <i class="material-icons">toll</i> Ordenes
                        </div>
                        <div class="collapsible-body" style="display: block;padding-top: 15px;padding-top: 5px;padding-bottom: 5px;">

                          <div class="row">
                            <div class="col m2 s2 input-field">
                              <input id="ordennro" name="ordennro" type="number" step="1" class="validate" 
                                value="{{ old('ordennro',$legajo->ordennro) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="11" autocomplete='off'
                                data-error=".errorTxt5" required>
                              <label for="ordennro">Nro. Orden</label>
                              <small class="errorTxt8"></small>
                            </div>

                            <div class="col m6 s6 input-field">
                              <input id="nom_afiliado" name="nom_afiliado" type="text" class="validate" 
                                value="{{ old('nom_afiliado',$legajo->nom_afiliado) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="50" autocomplete='off'
                                data-error=".errorTxt5">
                              <label for="nom_afiliado">Afiliado</label>
                              <small class="errorTxt9"></small>
                            </div>

                            <div class="col m2 s2 input-field">
                              {{-- <input id='fecha' type="text" class="datepicker mr-2 mb-1" placeholder="Elija fecha" value="{{ old('fecha',$orden->fecha) }}"> --}}
                              <input id="fecha" name="fecha" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fecha',$legajo->fecha) }}"
                                    maxlength="10" autocomplete='off'
                                    required
                                    {{ $edicion?'enabled':'disabled' }}
                                    data-error=".errorTxt2">
                              <label for="fecha">Fecha Aut.</label>
                              <small class="errorTxt2"></small>
                            </div>
                          </div>

                          <div class="row">
                            
                            <input id="nemotecnico_original" name="nemotecnico_original" type="text" autocomplete="off" maxlength="10" value="{{ old('cod_nemotecnico',$legajo->cod_nemotecnico) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                            <search-nomenclador onchange="changeNomenclador(this)"></search-nomenclador>

                            <div class="col m3 s3 input-field">
                              <select id="nomenclador" name="nomenclador" {{ $edicion?'enabled':'disabled' }} onchange="changePrestacion(this)">
                                <option value="" @if ($legajo->nomenclador == "")  selected   @endif  >Seleccione...</option>
                                @foreach ($nomencladores as $nomenclador)
                                  <option value = "{{ $nomenclador->cod_nemotecnico  }}" @if ( old('nomenclador',$legajo->cod_nemotecnico)  == $nomenclador->cod_nemotecnico)  selected @endif>{{ $nomenclador->cod_nomen }}</option>
                                @endforeach
                              </select>
                              <label>Nomenclador</label>
                            </div>

                            <div class="col m3 s3 input-field">
                              <select id="prestacion" name="prestacion" {{ $edicion?'enabled':'disabled' }} onchange="changePrestacion2(this)">
                                <option value="" @if ($legajo->prestacion == "")  selected   @endif  >Seleccione prestación</option>
                                @foreach ($prestaciones as $prestacion)
                                  <option value = "{{ $prestacion->cod_nomen  }}" @if ( old('prestacion',$legajo->cod_nomen)  == $prestacion->cod_nomen)  selected @endif>{{ $prestacion->nom_prest }}</option>
                                @endforeach
                              </select>
                              <label>Prestación</label>
                            </div>
                            
                            <div class="col m1 s1 input-field" style="width: 110px;">
                              <input id="cantidad" name="cantidad" type="number" class="validate" step="1"
                                value="{{ old('cantidad',$legajo->cantidad) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="11" autocomplete='off'
                                data-error=".errorTxt4" required onchange="calcular()">
                              <label for="cantidad" id="lblCantidad" name="lblCantidad">Cantidad</label>
                              <small class="errorTxt14"></small>
                            </div>

                            <div class="col m2 s2 input-field" style="width: 130px;">
                              <input id="precio" name="precio" type="number" class="validate" step="0.10"
                                value="{{ old('precio',$legajo->precio) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="11" autocomplete='off'
                                data-error=".errorTxt15">
                              <label for="precio"  id="lblPrecio" name="lblPrecio">Importe</label>
                              <small class="errorTxt15"></small>
                            </div>

                            <div class="col m2 s2 input-field" style="width: 130px;">
                              <input id="total" name="total" type="number" class="validate" 
                                value="{{ old('total',$legajo->total) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="11" autocomplete='off'
                                data-error=".errorTxt16" disabled>
                              <label for="total" id="lblTotal" name="lblTotal">Total</label>
                              <small class="errorTxt16"></small>
                            </div>
                          </div>
        
                        </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>


            @if($edicion == true)
            <div class="col s12 display-flex justify-content-end mt-3">
              <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                Guardar cambios</button>
              
              <a href="{{ asset( url('/carga-ordenes') ) }}" class="btn btn-labeled btn-danger mb-2">
                  <span class="btn-label"><i class="fa fa-times"></i>
                  </span>Cancelar
              </a>
            </div>
            @endif
          </div>


          
          <!-- users edit account form ends -->
        </div>
        
      </div>
      <!-- </div> -->

      </form>
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>

<script>
function changeObra(e) {
  var obraSocial = document.getElementById("cod_os").value;

  document.getElementById("det_os").value = obraSocial;
  //$("det_os").val("1050");
  $("#det_os").formSelect(); // Refrescar

  var valor1 = document.getElementById("det_os").value;
}

function changeObra2(e) {
  var obraSocial = document.getElementById("det_os").value;

  document.getElementById("cod_os").value = obraSocial;
  
  $("#lblCod_os").addClass('active');
}


function changeProfessional(e) {
  //var valor1 = document.getElementById("det_os").value;
  //console.log(valor1);

  var matricula = document.getElementById("mat_prov_cole").value;

  document.getElementById("profesional").value = matricula;
  
  $("#profesional").formSelect(); // Refrescar
}


function changeProfessional2(e) {
  var ape_nomProfesional = document.getElementById("profesional").value;

  document.getElementById("mat_prov_cole").value = ape_nomProfesional;
  
  $("#lblMatricula").addClass('active');
}



function changeNomenclador(e) {
  var Nomenclador = document.getElementById("id_nomen").value;

  document.getElementById("nomenclador").value = Nomenclador;
  $("#nomenclador").formSelect(); // Refrescar

  changePrestacion(1)
}


function changePrestacion(e) {
  var nomenclador = $( "#nomenclador option:selected" ).text();
  
  document.getElementById("prestacion").value = nomenclador;
  $("#prestacion").formSelect(); // Refrescar

  buscoPrecio(1)
}


function changePrestacion2(e) {
  var detPrestacion = document.getElementById("prestacion").value;

  document.getElementById("nomenclador").value = detPrestacion;
  
  //$("#lblMatricula").addClass('active');
}


function buscoPrecio(e) {
  var codPrestacion = document.getElementById("prestacion").value

  $.ajax({
      url: "/searchPrecios/" + codPrestacion,
      data: "",
      dataType: "json",
      method: "GET",
      success: function(result)
      {
        if (result != null) {
          
          document.getElementById("precio").value = result[0].importe;

          $("#lblPrecio").addClass('active');

        } else {
          alert('Error en la devolucion del precio');
        } 
      },
      fail: function(){
          alert("Error buscando vehiculos...");
      },
      beforeSend: function(){

      }
    });
}


function calcular(e) {
  var precio = 0
  var cantidad = 0

  cantidad = document.getElementById("cantidad").value;
  precio = document.getElementById("precio").value;

  document.getElementById("total").value = precio * cantidad;
  
  $("#lblTotal").addClass('active');
}

</script>
@endsection
