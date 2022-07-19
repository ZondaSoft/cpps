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
@section('title','Editar Obras sociales')

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
                
                <a href="{{ asset( url('/obras-admin') ) }}" class="btn btn-labeled btn-danger mb-2">
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
                      maxlength="6" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="cod_os">Código *</label>
                    <small class="errorTxt1"></small>
                  </div>
                  
                  <div class="col m8 s8 input-field">
                    <input id="desc_os" name="desc_os" type="text" class="validate" 
                      value="{{ old('desc_os',$legajo->desc_os) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt2">
                    <label for="desc_os">Descripción obra social *</label>
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
                    <input id="fecha_alta" name="fecha_alta" type="date" placeholder="dd/mm/aaaa" class="" 
                      value="{{ old('fecha_alta',$legajo->fecha_alta) }}"
                      maxlength="10" autocomplete='off'
                      data-error=".errorTxt4"
                      {{ $edicion?'':'disabled' }}>
                    <label for="fecha_alta">Fecha alta</label>
                    <small class="errorTxt4"></small>
                  </div>

                  <div class="col m6 s6 input-field">
                    <input id="contacto" name="contacto" type="text" step="1" class="validate" 
                      value="{{ old('contacto',$legajo->contacto) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="40" autocomplete='off'
                      data-error=".errorTxt6">
                    <label for="contacto">Contacto</label>
                    <small class="errorTxt6"></small>
                  </div>

                  <div class="col m5 s5 input-field">
                    <input id="direccion_os" name="direccion_os" type="text" step="1" class="validate" 
                      value="{{ old('direccion_os',$legajo->direccion_os) }}"
                      {{ $edicion?'':'disabled' }}
                      maxlength="40" autocomplete='off'
                      data-error=".errorTxt6">
                    <label for="direccion_os">Domicilio</label>
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
                                <input id="porcent_nino" name="porcent_nino" type="number" step="0.01" class="validate" 
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
                  </div>


                  <!-- CONVENIOS -->
                  <div class="col s12" style="padding-right: 0px;padding-left: 0px" id="convenios" name="convenios">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                            <i class="material-icons">toll</i> Convenios asociados
                          </div>
                          <div class="collapsible-body" style="display: block;">

                            <div class="row">
                              
                              <!-- START table-responsive-->
                              <div class="col s12">
                                <table class="bordered">
                                  <thead>
                                      <tr>
                                        <th data-field="id">Código</th>
                                        <th data-field="name">Descripción</th>
                                        <th data-field="name">Observaciones</th>
                                        <th data-field="name">Categoria por antiguedad</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($conv_os as $convenio)
                                        <tr style="height: 10px;">
                                            <td style="height: 10px;">
                                              {{ $convenio->cod_conv }}
                                            </td style="height: 10px;">

                                            <td style="height: 10px;">
                                              {{ $convenio->NomConvenio }}
                                            </td>

                                            <td style="height: 10px;">
                                              {{ $convenio->ObservacionConv }}
                                            </td>

                                            <td style="height: 10px;">
                                              @if ($convenio->cod_categoria == 0)
                                                  A
                                              @endif
                                              @if ($convenio->cod_categoria == 1)
                                                  B
                                              @endif
                                              @if ($convenio->cod_categoria == 2)
                                                  C
                                              @endif
                                              
                                            </td>
                                        </tr>
                                      @endforeach
                                  </tbody>
                                </table>

                                {{-- <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/obras-admin/add') }}" >Agregar convenio</a> --}}

                            </div>
                            <!-- END table-responsive-->

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
                
                <a href="{{ asset( url('/obras-admin') ) }}" class="btn btn-labeled btn-danger mb-2">
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

      {{-- <button data-target="modal1" class="btn modal-trigger">Agregar convenio</button> --}}
      <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#modal1">Agregar convenio</a>

      <div id="modal1" class="modal">
        <div class="modal-content">
          <h5>Agregar convenio</h5>
            <!-- START table-responsive-->
            <div class="col s12">
              <table class="bordered">
                <thead>
                    <tr>
                      <th data-field="id">Código</th>
                      <th data-field="name">Descripción</th>
                      <th data-field="name">Observaciones</th>
                      <th data-field="name">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($convenios as $convenio)
                      <tr style="height: 10px;">
                          <td style="height: 10px;">
                            {{ $convenio->cod_conv }}
                          </td style="height: 10px;">

                          <td style="height: 10px;">
                            {{ $convenio->desc_conv }}
                          </td>

                          <td style="height: 10px;">
                            {{ $convenio->observacion_conv }}
                          </td>

                          <td style="height: 10px;alignment: center">
                            <p class="mb-1">
                              {{-- checked="checked" --}}
                              <label>
                                <input type="checkbox" class="filled-in">
                                <span></span>
                              </label>
                            </p>
                          </td>
                      </tr>
                    @endforeach
                </tbody>
              </table>

              {{-- <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/obras-admin/add') }}" >Agregar convenio</a> --}}

          </div>
          <!-- END table-responsive-->
        
          <p>...</p>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Cancelar</a>
          <a href="/obras-admin" class="modal-action modal-close waves-effect waves-green btn-flat ">Grabar</a>
        </div>
      </div>

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
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
@endsection