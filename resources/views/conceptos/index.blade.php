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
@section('title','Editar conceptos')

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
      <form method="post" action="{{ asset( url('/conceptos/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/conceptos/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Conceptos</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/conceptos') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/conceptos/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/conceptos/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
              <a title="Dar de Baja al cliente actual" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                  <em class="icon-trash" style="color: white"></em> &nbsp;Borrar concepto
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
              <div class="s10 m10">
                <!-- Cuenta -->
                <div class="form-row">
                    <div class="col m6 s6 input-field">
                      <select id="cuenta" name="cuenta" {{ $edicion?'enabled':'disabled' }} onchange="changeVehiculos()">
                          <option value = 0 @if ( old('cuenta',$legajo->cuenta)  == 0)  selected   @endif  >Egresos en efectivo</option>
                          <option value = 1 @if ( old('cuenta',$legajo->cuenta)  == 1)  selected   @endif  >Tarjeta credito</option>
                          <option value = 2 @if ( old('cuenta',$legajo->cuenta)  == 2)  selected   @endif  >Tarjeta debito</option>
                          <option value = 3 @if ( old('cuenta',$legajo->cuenta)  == 3)  selected   @endif  >Transferencia Bancaria (Galicia)</option>
                          <option value = 4 @if ( old('cuenta',$legajo->cuenta)  == 4)  selected   @endif  >Transferencia Bancaria (Macro)</option>
                          <option value = 5 @if ( old('cuenta',$legajo->cuenta)  == 5)  selected   @endif  >Ingresos en efectivo</option>
                          <option value = 6 @if ( old('cuenta',$legajo->cuenta)  == 6)  selected   @endif  >Ingresos en cheques</option>
                      </select>
                      <label>Cod.Cuenta</label>
                  </div>
                  
                  <div class="col m12 s12 input-field">
                  </div>

                  <div class="col m4 s4 input-field">
                    <input id="codigo" name="codigo" type="text" class="validate" 
                      value="{{ old('codigo',$legajo->codigo) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled ':'disabled' }}
                      maxlength="6" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="codigo">Código</label>
                    <small class="errorTxt1"></small>
                  </div>
                  
                  <div class="col s12 input-field">
                    <input id="detalle" name="detalle" type="text" class="validate" 
                      value="{{ old('detalle',$legajo->detalle) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt3">
                    <label for="detalle">Descripción *</label>
                    <small class="errorTxt3"></small>
                  </div>

                  <div class="col m2 s3 input-field">
                    <input id="fecha_mat1" name="fecha_mat1" type="date" placeholder="dd/mm/aaaa" class="" 
                      value="{{ old('fecha_mat1',$legajo->fecha_mat1) }}"
                      maxlength="10" autocomplete='off'
                      data-error=".errorTxt7"
                      {{ $edicion?'':'disabled' }}>
                    <label for="fecha_mat1">Fecha matriculación</label>
                    <small class="errorTxt7"></small>
                  </div>

                  
                </div>
              </div>

              {{-- @ if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/conceptos') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
              @ endif --}}
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