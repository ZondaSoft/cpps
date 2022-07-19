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

{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Editar convenios')

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
      <form method="post" action="{{ asset( url('/nomenclador/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/nomenclador/edit/'.$legajo->id_nomen) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Administración de Nomencladores</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/nomenclador') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/nomenclador/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/nomenclador/edit') }}/{{ $legajo->id_nomen }}" style="font-color: withe">Editar</a>
              <a title="Borrar repuesto" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                  <em class="icon-trash" style="color: white"></em> &nbsp;Borrar
              </a>
            @endif
          </div>
        </div>
        </div>

      

        <div class="divider mb-3"></div>

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
          
            <div class="row">
              <div class="s12 m12">
                <div class="form-row">
                  <div class="col m2 s2 input-field">
                    <input id="cod_nomen" name="cod_nomen" type="text" step="1" class="validate" 
                      value="{{ old('cod_nomen',$legajo->cod_nomen) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="9" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="cod_nomen">Código *</label>
                    <small class="errorTxt1"></small>
                  </div>

                  <div class="col m3 s3 input-field">
                    <input id="cod_nemotecnico" name="cod_nemotecnico" type="text" step="1" class="validate" 
                      value="{{ old('cod_nemotecnico',$legajo->cod_nemotecnico) }}"
                      {{ $edicion?'autofocus':'disabled' }}
                      maxlength="9" autocomplete='off'
                      required
                      data-error=".errorTxt2">
                    <label for="cod_nemotecnico">Código adicional nemotecnico *</label>
                    <small class="errorTxt2"></small>
                  </div>
                  
                  <div class="col s8 input-field">
                    <input id="nom_prest" name="nom_prest" type="text" class="validate" 
                      value="{{ old('nom_prest',$legajo->nom_prest) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt3">
                    <label for="nom_prest">Nombre *</label>
                    <small class="errorTxt3"></small>
                  </div>

                  <div class="col m7 s7 input-field">
                    <input id="observacion" name="observacion" type="text" class="validate" 
                      value="{{ old('observacion',$legajo->observacion) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="40" autocomplete='off'
                      data-error=".errorTxt4">
                    <label for="observacion">Observaciones</label>
                    <small class="errorTxt4"></small>
                  </div>
                  
                  <div class="col m7 s7 input-field">
                    <input id="desc_variante" name="desc_variante" type="text" class="validate" 
                      value="{{ old('desc_variante',$legajo->desc_variante) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="desc_variante">Descripción de la variante</label>
                    <small class="errorTxt5"></small>
                  </div>
                </div>

                  <div class="row">
                    <div class="col m2 s2 input-field">
                      <select id="estado_nomen" name="estado_nomen" {{ $edicion?'enabled':'disabled' }}>
                        <option value=0 @if ($legajo->estado_nomen == 0)  selected   @endif  >Activo</option>
                        <option value=1 @if ($legajo->estado_nomen == 1)  selected   @endif  >Inactivo</option>
                      </select>
                      <label>Estado</label>
                    </div>

                    <div class="col m2 s2 input-field">
                      <select id="ips" name="ips" {{ $edicion?'enabled':'disabled' }}>
                        <option value=0 @if ($legajo->ips == 0)  selected   @endif  >Si</option>
                        <option value=1 @if ($legajo->ips == 1)  selected   @endif  >No</option>
                      </select>
                      <label>IPS ?</label>
                    </div>
                  </div>

                  <!-- FACTURACION -->
                  {{-- <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-light-blue-cyan lightrn-1 white-text">
                            <i class="material-icons">toll</i> Facturación - Cálculos
                          </div>
                          <div class="collapsible-body" style="display: block;">

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="req_paciente" name="req_paciente" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="No" @if ($legajo->req_paciente == "No")  selected   @endif  >No</option>
                                  <option value="Si" @if ($legajo->req_paciente == "Si")  selected   @endif  >Si</option>
                                </select>
                                <label>Solicita nombre de paciente en ordenes ?</label>
                              </div>

                              
                            </div>

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <input id="porcent_nino" name="porcent_nino" type="text" class="validate" 
                                  value="{{ old('porcent_nino',$legajo->porcent_nino) }}"
                                  {{ $edicion?'enabled':'disabled' }}
                                  maxlength="30" autocomplete='off'
                                  data-error=".errorTxt5">
                                <label for="mat2">% adicional Niños</label>
                                <small class="errorTxt5"></small>
                              </div>

                              <div class="col m3 s3 input-field">
                                <input id="cuit" name="cuit" type="text" class="validate" 
                                  value="{{ old('cuit',$legajo->cuit) }}"
                                  {{ $edicion?'':'disabled' }}
                                  maxlength="13" autocomplete='off'
                                  data-error=".errorTxt5">
                                <label for="cuit">CUIT</label>
                                <small class="errorTxt5"></small>
                              </div>
                            </div>
          
                          </div>
                      </li>

                      

                    </ul>
                  </div> --}}

                  
                
              </div>


              @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/nomenclador') ) }}" class="btn btn-labeled btn-danger mb-2">
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
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
@endsection