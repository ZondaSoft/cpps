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
@section('title','Editar repuestos')

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
      <form method="post" action="{{ asset( url('/repuestos/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/repuestos/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Repuestos</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/repuestos') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/repuestos/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/repuestos/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
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
                  <div class="col m4 s4 input-field">
                    <input id="codigo" name="codigo" type="text" class="validate" 
                      value="{{ old('codigo',$legajo->codigo) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="15" autocomplete='off'
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

                  <div class="col s12 input-field">
                    <input id="descri" name="descri" type="text" class="validate" 
                      value="{{ old('descri',$legajo->descri) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      maxlength="50" autocomplete='off'
                      data-error=".errorTxt4">
                    <label for="descri">Descripción adicional</label>
                    <small class="errorTxt4"></small>
                  </div>


                  <div class="col m3 s3 input-field">
                    <select id="unidad" name="unidad" {{ $edicion?'enabled':'disabled' }}>
                      <option value="UN" @if ($legajo->unidad == "UN")  selected   @endif  >UNIDAD</option>
                      <option value="LT" @if ($legajo->unidad == "LT")  selected   @endif  >LITRO</option>
                      <option value="KG" @if ($legajo->unidad == "KG")  selected   @endif  >KILOGRAMO</option>
                      <option value="CA" @if ($legajo->unidad == "CA")  selected   @endif  >CAJA</option>
                    </select>
                    <label>Unidad de medida</label>
                  </div>


                  <div class="col m4 s4 input-field">
                    <input id="costo" name="costo" type="text" class="validate" 
                      value="{{ old('costo',$legajo->costo) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="15" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="costo">Precio costo</label>
                    <small class="errorTxt5"></small>
                  </div>


                  <div class="col m4 s4 input-field">
                    <input id="reposicion" name="reposicion" type="text" class="validate" 
                      value="{{ old('reposicion',$legajo->reposicion) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="15" autocomplete='off'
                      data-error=".errorTxt7">
                    <label for="reposicion">Precio reposicion</label>
                    <small class="errorTxt7"></small>
                  </div>
                </div>
              </div>


              @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/repuestos') ) }}" class="btn btn-labeled btn-danger mb-2">
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