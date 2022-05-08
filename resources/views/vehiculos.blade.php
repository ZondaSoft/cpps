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
@section('title','Editar Vehiculos')

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
      <form method="post" action="{{ asset( url('/vehiculos/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/vehiculos/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Vehiculos</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/vehiculos') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/vehiculos/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/vehiculos/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
              <a title="Dar de Baja al cliente actual" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
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
                  <div class="col m12 s12 input-field">
                    <select id="cliente" name="cliente" {{ $edicion?'enabled':'disabled' }}>
                        @foreach ($clientes as $cliente)
                            <option value = "{{ $cliente->codigo  }}" @if ( old('cliente',$legajo->cliente)  == $cliente->codigo)  selected   @endif  >{{ $cliente->detalle }}   ({{ $cliente->codigo  }})</option>
                        @endforeach
                    </select>
                    <label>Cliente</label>
                  </div>
                  
                  <div class="col m6 s6 input-field">
                    <input id="codigo" name="codigo" type="text" class="validate" 
                      value="{{ old('codigo',$legajo->codigo) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt2">
                    <label for="codigo">Dominio / Patente *</label>
                    <small class="errorTxt2"></small>
                  </div>

                  <div class="col m6 s6 input-field">
                    <input id="detalle" name="detalle" type="text" class="validate" 
                      value="{{ old('detalle',$legajo->detalle) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="35" autocomplete='off'
                      data-error=".errorTxt3">
                    <label for="detalle">Vehiculo *</label>
                    <small class="errorTxt3"></small>
                  </div>

                  <div class="col m6 s6 input-field">
                    <input id="modelo" name="modelo" type="text" class="validate" 
                      value="{{ old('modelo',$legajo->modelo) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt4">
                    <label for="modelo">Modelo *</label>
                    <small class="errorTxt4"></small>
                  </div>

                  
                  <div class="col m4 s4 input-field">
                    <input id="anio" name="anio" type="text" class="validate" 
                      value="{{ old('anio',$legajo->anio) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="4" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="anio">Año</label>
                    <small class="errorTxt5"></small>
                  </div>

                  
                  <div class="col m6 s6 input-field">
                    <input id="motor" name="motor" type="text" class="validate" 
                      value="{{ old('motor',$legajo->motor) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="35" autocomplete='off'
                      data-error=".errorTxt6">
                    <label for="motor">Motor</label>
                    <small class="errorTxt6"></small>
                  </div>

                  <div class="col m6 s6 input-field">
                    <input id="chasis" name="chasis" type="text" class="validate" 
                      value="{{ old('chasis',$legajo->chasis) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="35" autocomplete='off'
                      data-error=".errorTxt7">
                    <label for="chasis">Chasis</label>
                    <small class="errorTxt7"></small>
                  </div>
                </div>
              </div>


              
              <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                <ul class="collapsible collapsible-accordion">
                   <li>
                      <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                         <i class="material-icons">toll</i> Equipo / Acoplado
                      </div>
                      <div class="collapsible-body purple lighten-5">
                          <div class="col m6 s6 input-field">
                            <input id="acop_det" name="acop_det" type="text" class="validate" 
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('acop_det',$legajo->acop_det) }}"
                              maxlength="35" autocomplete='off'
                              data-error=".errorTxt8">
                            <label for="acop_det">Detalle</label>
                            <small class="errorTxt8"></small>
                          </div>
                          
                          <div class="col m4 s4 input-field">
                            <input id="acop_dom" name="acop_dom" type="text" class="validate" 
                              value="{{ old('acop_dom',$legajo->acop_dom) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="7" autocomplete='off'
                              data-error=".errorTxt9">
                            <label for="acop_dom">Dominio</label>
                            <small class="errorTxt9"></small>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="acop_mod" name="acop_mod" type="text" class="validate" 
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('acop_mod',$legajo->acop_mod) }}"
                              maxlength="40" autocomplete='off'
                              data-error=".errorTxt10">
                            <label for="acop_mod">Modelo</label>
                            <small class="errorTxt10"></small>
                          </div>
                          
                          <div class="col m4 s4 input-field">
                            <input id="anio" name="anio" type="text" class="validate" 
                              value="{{ old('anio',$legajo->anio) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="40" autocomplete='off'
                              data-error=".errorTxt11">
                            <label for="anio">Año</label>
                            <small class="errorTxt11"></small>
                          </div>

                          <p><br></p>
                          <p><br></p>
                          <p><br></p>
                          <p><br></p>
                          <p><br></p>
                          <p><br></p>
                          <p><br></p>
      
                      </div>
                   </li>
                </ul>
             </div>
             
              @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/vehiculos') ) }}" class="btn btn-labeled btn-danger mb-2">
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