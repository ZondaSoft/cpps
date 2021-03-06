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
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
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
          
          <!-- users edit media object ends -->
          
          <div class="row" id="app">
            <div class="s12 m12">
              <div class="form-row">
                <div class="col m2 s2 input-field">
                  <input id="periodo" name="periodo" type="text" class="validate" 
                    value="{{ old('periodo',$legajo->periodo) }}"
                    {{ $edicion?'':'disabled' }}
                    {{ $agregar?'enabled autofocus=""':'disabled' }}
                    maxlength="7"
                    required
                    data-error=".errorTxt1">
                  <label for="periodo">Periodo</label>
                  <small class="errorTxt1"></small>
                </div>

                <search-component></search-component>
                
                <div class="col m6 s6 input-field">
                  <select id="det_os" name="det_os" 
                      {{ $edicion?'enabled':'disabled' }}>
                      <option value = "" @if ( old('obra',$legajo->cod_os)  == "")  selected   @endif  >Seleccione una Obra Social</option>
                      @foreach ($obras as $obra)
                        <option value = "{{ $obra->cod_os  }}" @if ( old('obra',$legajo->cod_os)  == $obra->cod_os)  selected   @endif  >{{ $obra->cod_os }} - {{ $obra->desc_os }}</option>
                      @endforeach
                  </select>
                  <label>Obra Social</label>
                </div>

                <div class="col m2 s2 input-field">
                  <input id="ordenes " name="ordenes" type="number" step="1" class="validate" 
                    value="{{ old('ordenes ',$legajo->ordenes ) }}"
                    {{ $edicion?'':'disabled' }}
                    {{ $agregar?'enabled autofocus=""':'disabled' }}
                    maxlength="8" autocomplete='off' required
                    data-error=".errorTxt3">
                  <label for="ordenes">Ordenes *</label>
                  <small class="errorTxt3"></small>
                </div>

                <div class="col m2 s2 input-field">
                  <input id="importe " name="importe" type="number" step="0.1" class="validate" 
                    value="{{ old('importe ',$legajo->importe ) }}"
                    {{ $edicion?'':'disabled' }}
                    {{ $agregar?'enabled autofocus=""':'disabled' }}
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
                              

                              {{-- <div class="col m2 s2 input-field">
                                <input id="matricula " name="matricula" type="number" step="1" class="validate" 
                                  value="{{ old('matricula ',$legajo->matricula ) }}"
                                  {{ $edicion?'':'disabled' }}
                                  {{ $agregar?'enabled autofocus=""':'disabled' }}
                                  maxlength="8" autocomplete='off' required
                                  data-error=".errorTxt4">
                                <label for="matricula">Mátricula *</label>
                                <small class="errorTxt4"></small>
                              </div> --}}

                              <div class="col m8 s8 input-field">
                                <select id="profesional" name="profesional" 
                                    {{ $edicion?'enabled':'disabled' }}>
                                    <option value = "" @if ( old('profesional',$legajo->profesional)  == "")  selected   @endif  >Seleccione un profesional</option>
                                    @foreach ($profesionales as $profesional)
                                      <option value = "{{ $profesional->mat_prov_cole  }}" @if ( old('profesional',$legajo->profesional)  == $profesional->profesional)         @endif  >{{ $profesional->mat_prov_cole }} - {{ $profesional->nom_ape }}</option>
                                    @endforeach
                                </select>
                                <label>Apellido y Nombre</label>
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
                          <i class="material-icons">toll</i> Orden
                        </div>
                        <div class="collapsible-body" style="display: block;padding-top: 15px;padding-bottom: 5px;">

                          <div class="row">
                            <div class="col m3 s3 input-field">
                              <input id="orden_nro" name="orden_nro" type="number" step="1" class="validate" 
                                value="{{ old('orden_nro',$legajo->orden_nro) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="30" autocomplete='off'
                                data-error=".errorTxt5">
                              <label for="orden_nro">Nro. Orden</label>
                              <small class="errorTxt8"></small>
                            </div>

                            <div class="col m6 s6 input-field">
                              <input id="afiliado" name="afiliado" type="text" class="validate" 
                                value="{{ old('afiliado',$legajo->afiliado) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="50" autocomplete='off'
                                data-error=".errorTxt5">
                              <label for="afiliado">Afiliado</label>
                              <small class="errorTxt9"></small>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col m1 s1 input-field">
                              <select id="cuota_col" name="cuota_col" {{ $edicion?'enabled':'disabled' }}>
                                <option value="0" @if ($legajo->cuota_col == "0")  selected   @endif  >SI se descuenta</option>
                                <option value="1" @if ($legajo->cuota_col == "1")  selected   @endif  >NO se descuenta</option>
                              </select>
                              <label>Código</label>
                            </div>
                            
                            <div class="col m3 s3 input-field">
                              <select id="nomenclador" name="nomenclador" {{ $edicion?'enabled':'disabled' }}>
                                <option value="" @if ($legajo->nomenclador == "")  selected   @endif  >Seleccione nomenclador</option>
                              </select>
                              <label>Nomenclador</label>
                            </div>

                            <div class="col m3 s3 input-field">
                              <select id="prestacion" name="prestacion" {{ $edicion?'enabled':'disabled' }}>
                                <option value="" @if ($legajo->prestacion == "")  selected   @endif  >Seleccione prestación</option>
                              </select>
                              <label>Prestación</label>
                            </div>
                            
                            <div class="col m2 s2 input-field">
                              <input id="cantidad" name="cantidad" type="number" class="validate" 
                                value="{{ old('cantidad',$legajo->cantidad) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="22" autocomplete='off'
                                data-error=".errorTxt4">
                              <label for="cantidad">Cantidad</label>
                              <small class="errorTxt14"></small>
                            </div>

                            <div class="col m2 s2 input-field">
                              <input id="importe" name="importe" type="number" class="validate" 
                                value="{{ old('importe',$legajo->importe) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="22" autocomplete='off'
                                data-error=".errorTxt15">
                              <label for="importe">Importe</label>
                              <small class="errorTxt15"></small>
                            </div>

                            <div class="col m2 s2 input-field">
                              <input id="total" name="total" type="number" class="validate" 
                                value="{{ old('total',$legajo->total) }}"
                                {{ $edicion?'enabled':'disabled' }}
                                maxlength="22" autocomplete='off'
                                data-error=".errorTxt16">
                              <label for="total">Total</label>
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
@endsection