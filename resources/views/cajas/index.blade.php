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
@section('title','Editar clientes')

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
      <form method="post" action="{{ asset( url('/home/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/home/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Clientes activos</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/home') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/home/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/home/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
              <a title="Dar de Baja al cliente actual" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                  <em class="icon-trash" style="color: white"></em> &nbsp;Baja cliente
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
                  <div class="col m6 s6 input-field">
                    <input id="codigo" name="codigo" type="text" class="validate" 
                      value="{{ old('codigo',$legajo->codigo) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="4" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="codigo">Código</label>
                    <small class="errorTxt1"></small>
                  </div>
                  
                  <div class="col m6 s6 input-field">
                    <input id="cuit" name="cuit" type="text" class="validate" 
                      value="{{ old('cuit',$legajo->cuit) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      {{ $agregar?'autofocus=""':'autofocus=""' }}
                      maxlength="13"
                      autocomplete='off'
                      data-error=".errorTxt2">
                    <label for="cuit">CUIT *</label>
                    <small class="errorTxt2"></small>
                  </div>

                  <div class="col s12 input-field">
                    <input id="detalle" name="detalle" type="text" class="validate" 
                      value="{{ old('detalle',$legajo->detalle) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt3">
                    <label for="detalle">Razón Social *</label>
                    <small class="errorTxt3"></small>
                  </div>

                  <div class="col s12 input-field">
                    <input id="nom_com" name="nom_com" type="text" class="validate" 
                      value="{{ old('nom_com',$legajo->nom_com) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt4">
                    <label for="nom_com">Nombre de fantasia *</label>
                    <small class="errorTxt4"></small>
                  </div>
                </div>
              </div>


              
              <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                <ul class="collapsible collapsible-accordion">
                   <li>
                      <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                         <i class="material-icons">toll</i> Datos particulares
                      </div>
                      <div class="collapsible-body purple lighten-5">
                          <div class="col m6 s6 input-field">
                            <input id="localid" name="localid" type="text" class="validate" 
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('localid',$legajo->localid) }}"
                              maxlength="50" autocomplete='off'
                              data-error=".errorTxt5">
                            <label for="localid">Localidad</label>
                            <small class="errorTxt5"></small>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="codpostal" name="codpostal" type="text" class="validate" 
                              value="{{ old('codpostal',$legajo->codpostal) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="15" autocomplete='off'
                              data-error=".errorTxt6">
                            <label for="codpostal">Codigo Postal</label>
                            <small class="errorTxt6"></small>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="domic" name="domic" type="text" class="validate" 
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('domic',$legajo->domic) }}"
                              maxlength="40" autocomplete='off'
                              data-error=".errorTxt7">
                            <label for="domic">Calle/Mza</label>
                            <small class="errorTxt7"></small>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="dom_com" name="dom_com" type="text" class="validate" 
                              value="{{ old('dom_com',$legajo->dom_com) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="40" autocomplete='off'
                              data-error=".errorTxt6">
                            <label for="dom_com">Nro/Casa/Lote</label>
                            <small class="errorTxt6"></small>
                          </div>

                          <p>
                            .
                          </p>
      
                      </div>
                   </li>

                   <!--       Forma de pago        -->
                   <li>
                      <div class="collapsible-header gradient-45deg-red-pink accent-2 white-text">
                         <i class="material-icons">timeline</i> Forma de pago
                      </div>
                      <div class="collapsible-body red lighten-5">

                          <div class="col m6 s6 input-field">
                            <select id="formap" name="formap" {{ $edicion?'enabled':'disabled' }} autocomplete='country-name'>
                              <option value="E" @if ($legajo->formap == "E")  selected   @endif  >Efectivo</option>
                              <option value="D" @if ($legajo->formap == "D")  selected   @endif  >Bancario</option>
                            </select>
                            <label>Forma de pago habitual</label>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <select id="banco" name="banco" {{ $edicion?'enabled':'disabled' }} autocomplete='country-name'>
                              @foreach ($bancos as $banco)
                                  <option value = "{{ $banco->codigo  }}"
                                    @if ( old('banco',$legajo->banco)  == $banco->codigo)  selected   @endif  >
                                    {{ $banco->detalle }}   ({{ $banco->codigo  }})
                                  </option>
                              @endforeach
                            </select>
                            <label>Banco</label>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="sucursal" name="sucursal" type="text" class="validate" 
                              {{ $edicion?'enabled':'disabled' }}
                              value="{{ old('sucursal',$legajo->sucursal) }}"
                              maxlength="20" autocomplete='off'
                              data-error=".errorTxt9">
                            <label for="sucursal">Sucursal</label>
                            <small class="errorTxt9"></small>
                          </div>
                          
                          <div class="col m6 s6 input-field">
                            <input id="cuenta" name="cuenta" type="text" class="validate" 
                              value="{{ old('cuenta',$legajo->cuenta) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="20" autocomplete='off'
                              data-error=".errorTxt6">
                            <label for="cuenta"># Cuenta</label>
                            <small class="errorTxt6"></small>
                          </div>


                          <div class="col m6 s6 input-field">
                            <input id="cbu" name="cbu" type="text" class="validate" 
                              value="{{ old('cbu',$legajo->cbu) }}"
                              {{ $edicion?'enabled':'disabled' }}
                              maxlength="20" autocomplete='off'
                              data-error=".errorTxt6">
                            <label for="cbu"># CBU</label>
                            <small class="errorTxt6"></small>
                          </div>

                          
                          <p>.
                          </p>
                          <p><br>
                          </p>
                          <p><br>
                          </p>
                      </div>
                   </li>
                </ul>
              </div>
             
              @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/home') ) }}" class="btn btn-labeled btn-danger mb-2">
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