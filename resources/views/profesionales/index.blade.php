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
      <form method="post" action="{{ asset( url('/profesionales/add') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/profesionales/edit/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Profesionales activos</h5>
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
              <a class="waves-effect waves-light btn mb-1 mr-1" href="{{ asset('/profesionales/add') }}" >Agregar</a>
              <a class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/profesionales/edit') }}/{{ $legajo->id }}" style="font-color: withe">Editar</a>
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
                    <input id="mat_prov_cole" name="mat_prov_cole" type="number" step="1" class="validate" 
                      value="{{ old('mat_prov_cole',$legajo->mat_prov_cole) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="15" autocomplete='off'
                      required
                      data-error=".errorTxt1">
                    <label for="mat_prov_cole">Nro. Matricula *</label>
                    <small class="errorTxt1"></small>
                  </div>
                  
                  <div class="col s8 input-field">
                    <input id="nom_ape" name="nom_ape" type="text" class="validate" 
                      value="{{ old('nom_ape',$legajo->nom_ape) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      required maxlength="50" autocomplete='off'
                      data-error=".errorTxt3">
                    <label for="nom_ape">Apellido y Nombres *</label>
                    <small class="errorTxt3"></small>
                  </div>

                  <div class="col m3 s3 input-field">
                    <select id="sexo" name="sexo" {{ $edicion?'enabled':'disabled' }}>
                      <option value="F" @if ($legajo->sexo == "F")  selected   @endif  >Femenino</option>
                      <option value="M" @if ($legajo->sexo == "M")  selected   @endif  >Masculino</option>
                      <option value="O" @if ($legajo->sexo == "O")  selected   @endif  >Otro</option>
                    </select>
                    <label>Sexo / Genero</label>
                  </div>

                  <div class="col s6 input-field">
                    <input id="lugar_nacimiento" name="lugar_nacimiento" type="text" class="validate" 
                      value="{{ old('lugar_nacimiento',$legajo->lugar_nacimiento) }}"
                      {{ $edicion?'enabled':'disabled' }}
                      maxlength="50" autocomplete='off'
                      data-error=".errorTxt4">
                    <label for="lugar_nacimiento">Lugar nacimento</label>
                    <small class="errorTxt4"></small>
                  </div>

                  <div class="col m3 s3 input-field">
                    <select id="nacionalidad" name="nacionalidad" {{ $edicion?'enabled':'disabled' }}>
                      <option value="Argentina" @if ($legajo->nacionalidad == "Argentina")  selected   @endif  >Argentina</option>
                      <option value="Extranjera" @if ($legajo->nacionalidad == "Extranjera")  selected   @endif  >Extranjera</option>
                    </select>
                    <label>Nacionalidad</label>
                  </div>

                  <div class="col m1 s1 input-field">
                    <select id="tipo_doc" name="tipo_doc" {{ $edicion?'enabled':'disabled' }}>
                      <option value="0" @if ($legajo->tipo_doc == "0")  selected   @endif  >DNI</option>
                      <option value="1" @if ($legajo->tipo_doc == "1")  selected   @endif  >LE</option>
                      <option value="2" @if ($legajo->tipo_doc == "2")  selected   @endif  >LC</option>
                      <option value="3" @if ($legajo->tipo_doc == "3")  selected   @endif  >Otro</option>
                    </select>
                    <label>Tipo doc.</label>
                  </div>

                  
                  <div class="col m2 s2 input-field">
                    <input id="num_doc" name="num_doc" type="text" class="validate" maxlength="12"
                      value="{{ old('num_doc',$legajo->num_doc) }}"
                      {{ $edicion?'':'disabled' }}
                      {{ $agregar?'enabled autofocus=""':'disabled' }}
                      maxlength="8" autocomplete='off' required
                      data-error=".errorTxt5">
                    <label for="num_doc">Nro. documento *</label>
                    <small class="errorTxt5"></small>
                  </div>

                  
                  <div class="col m3 s3 input-field">
                    <select id="cond_iva" name="cond_iva" {{ $edicion?'enabled':'disabled' }}>
                      <option value="0" @if ($legajo->cond_iva == "0")  selected   @endif  >Resp.No Inscripto</option>
                      <option value="1" @if ($legajo->cond_iva == "1")  selected   @endif  >Resp.Inscripto</option>
                      <option value="2" @if ($legajo->cond_iva == "2")  selected   @endif  >Monotributo</option>
                      <option value="3" @if ($legajo->cond_iva == "3")  selected   @endif  >Exento</option>
                      <option value="4" @if ($legajo->cond_iva == "4")  selected   @endif  >No repsonsable</option>
                    </select>
                    <label>Condición IVA (AFIP)</label>
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

                  
                  
                  <!-- FORMACION Y ESPECIALIDADES -->
                  <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                            <i class="material-icons">toll</i> Formación y Especialidades
                          </div>
                          <div class="collapsible-body ">
                              <div class="row">
                                <div class="col m5 s5 input-field">
                                  <input id="universidad" name="universidad" type="text" class="validate" 
                                    value="{{ old('universidad',$legajo->universidad) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="50" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="universidad">Universidad de egreso</label>
                                  <small class="errorTxt5"></small>
                                </div>
                                
                                <div class="col m2 s3 input-field">
                                  <input id="fcha_egreso" name="fcha_egreso" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fcha_egreso',$legajo->fcha_egreso) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fcha_egreso">Fecha egreso</label>
                                  <small class="errorTxt7"></small>
                                </div>

                                <div class="col m2 s3 input-field">
                                  <input id="fcha_titulo" name="fcha_titulo" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fcha_titulo',$legajo->fcha_titulo) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fcha_titulo">Fecha obtención titulo</label>
                                  <small class="errorTxt7"></small>
                                </div>
                              </div>
                              
                              <div class="row">
                                <div class="col m3 s3 input-field">
                                  <select id="especialidad" name="especialidad" {{ $edicion?'enabled':'disabled' }}>
                                    <option value="0" @if ($legajo->especialidad == "0")  selected   @endif  >Si</option>
                                    <option value="1" @if ($legajo->especialidad == "1")  selected   @endif  >No</option>
                                  </select>
                                  <label>Tiene especialidad ?</label>
                                </div>
                              </div>
                              
                              <div class="row">

                                <div class="col m3 s3 input-field">
                                  <input id="mat1" name="mat1" type="text" class="validate" 
                                    value="{{ old('mat1',$legajo->mat1) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="15" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="mat1">Matricula especialidad clinica</label>
                                  <small class="errorTxt5"></small>
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
              
                                <div class="col m3 s3 input-field">
                                  <input id="mat2" name="mat2" type="text" class="validate" 
                                    value="{{ old('mat2',$legajo->mat2) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="15" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="mat2">Matricula espec. educacional</label>
                                  <small class="errorTxt5"></small>
                                </div>

                                <div class="col m2 s3 input-field">
                                  <input id="fecha_mat2" name="fecha_mat2" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fecha_mat2',$legajo->fecha_mat2) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fecha_mat2">Fecha matriculación</label>
                                  <small class="errorTxt7"></small>
                                </div>
              
                                <div class="col m3 s3 input-field">
                                  <input id="mat3" name="mat3" type="text" class="validate" 
                                    value="{{ old('mat3',$legajo->mat3) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="15" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="mat3">Matricula especialidad social</label>
                                  <small class="errorTxt5"></small>
                                </div>

                                <div class="col m2 s3 input-field">
                                  <input id="fecha_mat3" name="fecha_mat3" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fecha_mat3',$legajo->fecha_mat3) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fecha_mat3">Fecha matriculación</label>
                                  <small class="errorTxt7"></small>
                                </div>
              
                                <div class="col m3 s3 input-field">
                                  <input id="mat4" name="mat4" type="text" class="validate" 
                                    value="{{ old('mat4',$legajo->mat4) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="15" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="mat4">Matricula especialidad forense</label>
                                  <small class="errorTxt5"></small>
                                </div>

                                <div class="col m2 s3 input-field">
                                  <input id="fecha_mat4" name="fecha_mat4" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fecha_mat4',$legajo->fecha_mat4) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fecha_mat4">Fecha matriculación</label>
                                  <small class="errorTxt7"></small>
                                </div>
              
                                <div class="col m3 s3 input-field">
                                  <input id="mat5" name="mat5" type="text" class="validate" 
                                    value="{{ old('mat5',$legajo->mat5) }}"
                                    {{ $edicion?'':'disabled' }}
                                    maxlength="15" autocomplete='off'
                                    data-error=".errorTxt5">
                                  <label for="mat5">Matricula especialidad laboral</label>
                                  <small class="errorTxt5"></small>
                                </div>

                                <div class="col m2 s3 input-field">
                                  <input id="fecha_mat5" name="fecha_mat5" type="date" placeholder="dd/mm/aaaa" class="" 
                                    value="{{ old('fecha_mat5',$legajo->fecha_mat5) }}"
                                    maxlength="10" autocomplete='off'
                                    data-error=".errorTxt7"
                                    {{ $edicion?'':'disabled' }}>
                                  <label for="fecha_mat5">Fecha matriculación</label>
                                  <small class="errorTxt7"></small>
                                </div>
                                
                              </div>
          
                          </div>
                      </li>

                      

                    </ul>
                  </div>



                  <!-- FACTURACION -->
                  <div class="col s12" style="padding-right: 0px;padding-left: 0px">
                    <ul class="collapsible collapsible-accordion">
                      <li>
                          <div class="collapsible-header waves-light gradient-45deg-light-blue-cyan lightrn-1 white-text">
                            <i class="material-icons">toll</i> Facturación - Descuentos
                          </div>
                          <div class="collapsible-body ">

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="cat_soc" name="cat_soc" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->cat_soc == "0")  selected   @endif  >Activo</option>
                                  <option value="1" @if ($legajo->cat_soc == "1")  selected   @endif  >Pasivo</option>
                                  <option value="2" @if ($legajo->cat_soc == "2")  selected   @endif  >Vitalicio</option>
                                  <option value="3" @if ($legajo->cat_soc == "3")  selected   @endif  >Baja</option>
                                  <option value="4" @if ($legajo->cat_soc == "4")  selected   @endif  >Otros</option>
                                  <option value="9" @if ($legajo->cat_soc == "9")  selected   @endif  >Sin informar</option>
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

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="cuota_col_deb_auto" name="cuota_col_deb_auto" {{ $edicion?'enabled':'disabled' }}>
                                  <option value=0 @if ($legajo->cuota_col_deb_auto == 0)  selected   @endif  >Si</option>
                                  <option value=1 @if ($legajo->cuota_col_deb_auto == 1)  selected   @endif  >No</option>
                                </select>
                                <label>Débito automatico de cuota profesional</label>
                              </div>

                              <div class="col m2 s2 input-field">
                                <select id="seg_mala_prax" name="seg_mala_prax" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->seg_mala_prax == "0")  selected   @endif  >Si</option>
                                  <option value="1" @if ($legajo->seg_mala_prax == "1")  selected   @endif  >No</option>
                                </select>
                                <label>Seguro de mala praxis</label>
                              </div>

                              
                              <div class="col m3 s3 input-field">
                                <select id="seg_mala_prax_deb_auto" name="seg_mala_prax_deb_auto" {{ $edicion?'enabled':'disabled' }}>
                                  <option value=0 @if ($legajo->seg_mala_prax_deb_auto == 0)  selected   @endif  >Si</option>
                                  <option value=1 @if ($legajo->seg_mala_prax_deb_auto == 1)  selected   @endif  >No</option>
                                </select>
                                <label>Débito automático seguro mala praxis</label>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col m3 s3 input-field">
                                <select id="cuota_col" name="cuota_col" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->cuota_col == "0")  selected   @endif  >SI se descuenta</option>
                                  <option value="1" @if ($legajo->cuota_col == "1")  selected   @endif  >NO se descuenta</option>
                                </select>
                                <label>Descuento cuota colegio?</label>
                              </div>
                              
                              <div class="col m3 s3 input-field">
                                <select id="caja_SS" name="caja_SS" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->caja_SS == "0")  selected   @endif  >Si aporta a la caja</option>
                                  <option value="1" @if ($legajo->caja_SS == "1")  selected   @endif  >No aporta a la caja</option>
                                </select>
                                <label>Aporta a caja de Seg.Social</label>
                              </div>

                              <div class="col m3 s3 input-field">
                                <select id="categ_ss" name="categ_ss" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->categ_ss == "0")  selected   @endif  >Categoria A</option>
                                  <option value="1" @if ($legajo->categ_ss == "1")  selected   @endif  >Categoria B</option>
                                  <option value="2" @if ($legajo->categ_ss == "2")  selected   @endif  >Categoria C</option>
                                  <option value="3" @if ($legajo->categ_ss == "3")  selected   @endif  >Categoria D</option>
                                  <option value="4" @if ($legajo->categ_ss == "4")  selected   @endif  >Categoria E</option>
                                  <option value="5" @if ($legajo->categ_ss == "5")  selected   @endif  >Ninguno</option>
                                </select>
                                <label>Categoría Seguridad Social</label>
                              </div>
                              
                              <div class="col m3 s3 input-field">
                                <select id="caja_reg_ss" name="caja_reg_ss" {{ $edicion?'enabled':'disabled' }}>
                                  <option value="0" @if ($legajo->caja_reg_ss == "0")  selected   @endif  >Si aporta</option>
                                  <option value="1" @if ($legajo->caja_reg_ss == "1")  selected   @endif  >No aporta</option>
                                </select>
                                <label>Aporte regulatorio?</label>
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