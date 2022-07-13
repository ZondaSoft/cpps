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
@section('title','Editar profesionales')

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
      <form method="post" action="{{ asset( url('/obras-admin/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/obras-admin/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Administración de Obras Sociales</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/profesionales') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/obras-admin/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/obras-admin/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
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
                    <input id="cod_os" name="cod_os" type="number" step="1" class="validate" 
                      value="{{ old('cod_os',$legajo->cod_os) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="15" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="cod_os">Código *</label>
                    <small class="errorTxt1"></small>
                  </div>
                  
                  <div class="col s8 input-field">
                    <input id="desc_os" name="desc_os" type="text" class="validate" 
                      value="{{ old('desc_os',$legajo->desc_os) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt2">
                    <label for="desc_os">Nombre obra social *</label>
                    <small class="errorTxt2"></small>
                  </div>

                  <div class="col m3 s3 input-field">
                    <select id="estado_os" name="estado_os" {{ $edicion?'enabled':'disabled' }}>
                      <option value="0" @if ($legajo->estado_os == "0")  selected   @endif  >Activa</option>
                      <option value="1" @if ($legajo->estado_os == "1")  selected   @endif  >Inactiva</option>
                    </select>
                    <label>Estado</label>
                  </div>

                  <div class="col m3 s3 input-field">
                    <input id="fcha_alta" name="fcha_alta" type="date" placeholder="dd/mm/aaaa" class="" 
                      value="{{ old('fcha_alta',$legajo->fcha_alta) }}"
                      maxlength="10" autocomplete='off'
                      data-error=".errorTxt4"
                      {{ $edicion?'':'disabled' }}>
                    <label for="fcha_alta">Fecha alta</label>
                    <small class="errorTxt4"></small>
                  </div>

                  <div class="col m5 s5 input-field">
                    <input id="contacto" name="contacto" type="text" step="1" class="validate" 
                      value="{{ old('contacto',$legajo->contacto) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="40" autocomplete='off' required
                      data-error=".errorTxt5">
                    <label for="contacto">Contacto</label>
                    <small class="errorTxt5"></small>
                  </div>

                  <div class="col m5 s5 input-field">
                    <input id="direccion_os" name="direccion" type="text" step="1" class="validate" 
                      value="{{ old('direccion',$legajo->direccion) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="40" autocomplete='off' required
                      data-error=".errorTxt6">
                    <label for="direccion">Domicilio</label>
                    <small class="errorTxt6"></small>
                  </div>

                  
                  <div class="col m4 s4 input-field">
                    <input id="cp" name="cp" type="text" class="validate" 
                      value="{{ old('cp',$legajo->cp) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="cp">Código Postal</label>
                    <small class="errorTxt5"></small>
                  </div>


                  <div class="col m5 s5 input-field">
                    <input id="localidad" name="localidad" type="text" class="validate" 
                      value="{{ old('localidad',$legajo->localidad) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="localidad">Localidad</label>
                    <small class="errorTxt5"></small>
                  </div>

                  <div class="col m4 s4 input-field">
                    <input id="provincia" name="provincia" type="text" class="validate" 
                      value="{{ old('provincia',$legajo->provincia) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="provincia">Provincia</label>
                    <small class="errorTxt5"></small>
                  </div>

                  <div class="col m3 s4 input-field">
                    <input id="telefono1" name="telefono1" type="text" class="validate" 
                      value="{{ old('telefono1',$legajo->telefono1) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="telefono1">Telefono 1</label>
                    <small class="errorTxt5"></small>
                  </div>

                  <div class="col m3 s4 input-field">
                    <input id="telefono2" name="telefono2" type="text" class="validate" 
                      value="{{ old('telefono2',$legajo->telefono2) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="telefono2">Telefono 2</label>
                    <small class="errorTxt5"></small>
                  </div>

                  <div class="col m3 s4 input-field">
                    <input id="telefono3" name="telefono3" type="text" class="validate" 
                      value="{{ old('telefono3',$legajo->telefono3) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="telefono3">Celular</label>
                    <small class="errorTxt5"></small>
                  </div>



                  <div class="col m7 s7 input-field">
                    <input id="observacion" name="observacion" type="text" class="validate" 
                      value="{{ old('observacion',$legajo->observacion) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="30" autocomplete='off'
                      data-error=".errorTxt5">
                    <label for="observacion">Observaciones adicionales</label>
                    <small class="errorTxt5"></small>
                  </div>
                  
                  <!-- FACTURACION -->
                  <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-light-blue-cyan lightrn-1 white-text">
                            <i class="material-icons">toll</i> Facturación - Cálculos
                          </div>
                          <div class="collapsible-body ">

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="req_paciente" name="req_paciente" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="No" @if ($legajo->req_paciente == "No")  selected   @endif  >No</option>
                                  <option value="Si" @if ($legajo->req_paciente == "Si")  selected   @endif  >Si</option>
                                </select>
                                <label>Categoria del profesional</label>
                              </div>

                              
                            </div>

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="forma_cobro" name="forma_cobro" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->forma_cobro == "0")  selected   @endif  >Contado Efectivo</option>
                                  <option value="1" @if ($legajo->forma_cobro == "1")  selected   @endif  >Transferencia con envio</option>
                                  <option value="2" @if ($legajo->forma_cobro == "2")  selected   @endif  >Transferencia con AR</option>
                                </select>
                                <label>Forma de cobro</label>
                              </div>
                              
                              <div class="col m5 s5 input-field">
                                <select id="cod_banco" name="cod_banco" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="00007" @if ($legajo->cod_banco == "00007")  selected   @endif  >BANCO DE GALICIA Y BUENOS AIRES S.A.U.</option>
                                  <option value="00011" @if ($legajo->cod_banco == "00011")  selected   @endif  >BANCO DE LA NACION ARGENTINA</option>
                                  <option value="00285" @if ($legajo->cod_banco == "00285")  selected   @endif  >BANCO MACRO S.A.</option>
                                  <option value="00017" @if ($legajo->cod_banco == "00017")  selected   @endif  >BANCO BBVA ARGENTINA S.A.</option>
                                </select>
                                <label>Banco</label>
                              </div>
                            </div>
                                
                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <input id="cta_bancaria" name="cta_bancaria" type="text" class="validate" 
                                  value="{{ old('cta_bancaria',$legajo->cta_bancaria) }}"
                                  {{ $edicion?'enabled':'disabled' }}
                                  maxlength="30" autocomplete='off'
                                  data-error=".errorTxt5">
                                <label for="mat2">Nro.Cuenta Bancaria</label>
                                <small class="errorTxt5"></small>
                              </div>

                              <div class="col m4 s4 input-field">
                                <input id="cbu" name="cbu" type="number" class="validate" 
                                  value="{{ old('cbu',$legajo->cbu) }}"
                                  {{ $edicion?'enabled':'disabled' }}
                                  maxlength="22" autocomplete='off'
                                  data-error=".errorTxt5">
                                <label for="mat2">CBU - Clave Bancaria Uniforme</label>
                                <small class="errorTxt5"></small>
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
                
                <a href="{{ asset( url('/profesionales') ) }}" class="btn btn-labeled btn-danger mb-2">
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