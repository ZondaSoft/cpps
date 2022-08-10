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
@section('title','Editar convenio')

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
      <form method="post" action="{{ asset( url('/convenios/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/convenios/edit/'.$legajo->cod_conv) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Administración de Convenios</h5>
          </div>
          <div class="col m6 s6">
            @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3 mb-1 mr-1">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/convenios') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
            @else
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/convenios/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/convenios/edit') }}/{{ $legajo->cod_conv }}" style="font-color: withe">Editar</a>
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
          
            {{-- <div class="row"> --}}
              <div class="s12 m12">
                <div class="form-row">

                  <div class="row">
                    <div class="col m6 s6 input-field">
                      <select id="cod_os" name="cod_os" 
                          {{ $edicion?'enabled':'disabled' }}>
                          <option value = "" @if ( old('obra',$legajo->cod_os)  == "")  selected   @endif  >Seleccione una Obra Social</option>
                          @foreach ($obras as $obra)
                            <option value = "{{ $obra->cod_os  }}" @if ( old('obra',$legajo->cod_os)  == $obra->cod_os)  selected   @endif  >{{ $obra->cod_os }} - {{ $obra->desc_os }}</option>
                          @endforeach
                      </select>
                      <label>Obra Social</label>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col m2 s2 input-field">
                      <input id="cod_conv" name="cod_conv" type="text" step="1" class="validate" 
                        value="{{ old('cod_conv',$legajo->cod_conv) }}"
                        {{ $edicion?'':'disabled' }}
                        {{ $agregar?'enabled autofocus=""':'disabled' }}
                        maxlength="9" autocomplete='off'
                        required
                        data-error=".errorTxt1">
                      <label for="cod_conv">Código *</label>
                      <small class="errorTxt1"></small>
                    </div>

                    <div class="col s8 input-field">
                      <input id="desc_conv" name="desc_conv" type="text" class="validate" 
                        value="{{ old('desc_conv',$legajo->desc_conv) }}"
                        {{ $edicion?'enabled':'disabled' }}
                        required maxlength="50" autocomplete='off'
                        data-error=".errorTxt3">
                      <label for="desc_conv">Nombre *</label>
                      <small class="errorTxt3"></small>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col m7 s7 input-field">
                      <input id="observacion_conv" name="observacion_conv" type="text" class="validate" 
                        value="{{ old('observacion_conv',$legajo->observacion_conv) }}"
                        {{ $edicion?'':'disabled' }}
                        maxlength="40" autocomplete='off'
                        data-error=".errorTxt4">
                      <label for="observacion_conv">Observaciones</label>
                      <small class="errorTxt4"></small>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col m2 s2 input-field">
                      <select id="estado_conv" name="estado_conv" {{ $edicion?'enabled':'disabled' }}>
                        <option value=0 @if ($legajo->estado_conv == 0)  selected   @endif  >Activo</option>
                        <option value=1 @if ($legajo->estado_conv == 1)  selected   @endif  >Inactivo</option>
                      </select>
                      <label>Estado</label>
                    </div>

                    
                    <div class="col m3 s3 input-field">
                      <input id="fecha_alta" name="fecha_alta" type="date" placeholder="dd/mm/aaaa" class="" 
                        value="{{ old('fecha_alta',$legajo->fecha_alta) }}"
                        maxlength="10" autocomplete='off'
                        data-error=".errorTxt7"
                        {{ $edicion?'':'disabled' }}>
                      <label for="fecha_alta">Fecha de alta</label>
                      <small class="errorTxt7"></small>
                    </div>

                    <div class="col m3 s3 input-field">
                      <input id="fecha_baja" name="fecha_baja" type="date" placeholder="dd/mm/aaaa" class="" 
                        value="{{ old('fecha_baja',$legajo->fecha_baja) }}"
                        maxlength="10" autocomplete='off'
                        data-error=".errorTxt7"
                        {{ $edicion?'':'disabled' }}>
                      <label for="fecha_baja">Fecha de baja</label>
                      <small class="errorTxt7"></small>
                    </div>
                  </div>




                  <!-- CONVENIOS -->
                  <div class="col s12" style="padding-right: 0px;padding-left: 0px" id="convenios" name="convenios">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                            <i class="material-icons">toll</i> Nomenclador de prácticas asociado
                          </div>
                          <div class="collapsible-body" style="display: block;">

                            <div class="row">
                              
                              <!-- START table-responsive-->
                              <div class="col s12">
                                <table class="bordered">
                                  <thead>
                                      <tr>
                                        <th data-field="name">Código</th>
                                        <th data-field="name">Cód.Nomenclador</th>
                                        <th data-field="name">Nombre Nomenclador</th>
                                        <th data-field="name">Importe</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($convNomenclador as $convenio)
                                        <tr style="height: 10px;">
                                          <td style="height: 10px;">
                                            {{ $convenio->cod_nemotecnico }}
                                          </td>

                                          <td style="height: 10px;">
                                            {{ $convenio->cod_nomenclador }}
                                          </td>

                                          <td style="height: 10px;">
                                            {{ $convenio->NomNomenclador }}
                                          </td>

                                          <td style="height: 10px;">
                                            {{ $convenio->importe }}
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


              @if($edicion == true)
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1">
                  Guardar cambios</button>
                
                <a href="{{ asset( url('/convenios') ) }}" class="btn btn-labeled btn-danger mb-2">
                    <span class="btn-label"><i class="fa fa-times"></i>
                    </span>Cancelar
                </a>
              </div>
              @endif
            </div>


          
          <!-- users edit account form ends -->
        {{-- </div> --}}
        
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