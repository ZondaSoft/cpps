{{-- layout --}}    {{-- @extends('layouts.app') --}} {{-- Extend Vue Suport --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Carga de ordenes')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css?v2')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content" style="padding-top: 0px;">
      <!-- users edit account form start -->
      @if($agregar == true)
      <form action="#" enctype="multipart/form-data">
      @else
      <form action="#" enctype="multipart/form-data">
      @endif

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;">Carga de ordenes</h5>
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
          
          <div class="row" id="app">
            <div class="s12 m12">
              <div class="form-row">
                <div class="col m2 s2 input-field" style="width: 100px;margin-bottom: 0px;">
                  <input id="periodo" name="periodo" type="text" class="validate" 
                    value="{{ old('periodo',$legajo->periodo) }}"
                    maxlength="7"
                    required
                    autofocus
                    data-error=".errorTxt1">
                  <label for="periodo">Periodo</label>
                  <small class="errorTxt1"></small>
                </div>

                
                <input id="cod_os_original" name="cod_os_original" type="text" autocomplete="off" maxlength="10" value="{{ old('cod_os',$legajo->cod_os) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                <search-ooss class="active" style="width: 100px;margin-bottom: 0px;" onchange="changeObra(this)"></search-ooss>
                
                
                <div class="col m4 s4 input-field" style="padding-right: 0px;margin-bottom: 0px;">
                  <select id="det_os" name="det_os" onchange="changeObra2(this)" >
                      <option value = "" @if ( old('obra',$legajo->cod_os)  == "")  selected   @endif  >Seleccione una Obra Social</option>
                      @foreach ($obras as $obra)
                        <option value = "{{ $obra->cod_os  }}" @if ( old('obra',$legajo->cod_os)  == $obra->cod_os)  selected   @endif  >{{ $obra->desc_os }}</option>
                      @endforeach
                  </select>
                  <label>Obra Social</label>
                </div>

                <div class="col m2 s2 input-field" style="margin-bottom: 0px;">
                  <input id="ordenes2" name="ordenes2" type="number" step="1" class="validate" 
                    value="{{ old('ordenes2',$legajo->ordenes2 ) }}"
                    disabled
                    maxlength="8" autocomplete='off' required
                    data-error=".errorTxt3">
                  <label for="ordenes">Ordenes</label>
                  <small class="errorTxt3"></small>
                </div>

                <div class="col m2 s2 input-field" style="margin-bottom: 0px;">
                  <input id="total" name="total" type="number" step="0.10" class="validate"
                    value="{{ old('total ',$legajo->total ) }}"
                    disabled
                    maxlength="12" autocomplete='off'
                    data-error=".errorTxt4">
                  <label for="total">Importe</label>
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

                              <input id="matricula_original" name="matricula_original" type="text" autocomplete="off" maxlength="10" value="{{ old('mat_prov_cole',$legajo->mat_prov_cole) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                              <search-professional onchange="changeProfessional(this)" value="{{ old('mat_prov_cole') }}"></search-professional>

                              <div class="col m7 s7 input-field">
                                <select id="profesional" name="profesional" 
                                    onchange="changeProfessional2(this)" >
                                    <option value = "" @if ( old('profesional',$legajo->mat_prov_cole)  == "")  selected   @endif  >Seleccione un profesional</option>
                                    @foreach ($profesionales as $profesional)
                                      <option value = "{{ $profesional->mat_prov_cole  }}" @if ( old('mat_prov_cole',$legajo->mat_prov_cole)  == $profesional->mat_prov_cole)  selected  @endif  >{{ $profesional->nom_ape }} - ({{ $profesional->mat_prov_cole }})
                                      </option>
                                    @endforeach
                                </select>
                                <label>Apellido y Nombre</label>

                                {{-- <vselect-prof></vselect-prof> --}}
                              </div>

                              <div class="col m3 s3 input-field">
                                <a type="submit" class="waves-effect light-blue darken-4 btn mb-1 mr-1" onclick="verOrdenes()">
                                  Ver ordenes</a>
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
                          <i class="material-icons">toll</i> Ordenes
                        </div>
                        <div class="collapsible-body" style="display: block;padding-top: 15px;padding-top: 5px;padding-bottom: 5px;">
                          <br>
                          <div class="col m12 s12">
                            <a id="addorder1" name="addorder1" class="waves-effect waves-light btn mb-1 mr-1 modal-trigger" href="#modal1" disabled="true" onclick="addOrder()">Agregar orden</a>
                            <a id="printorder1" name="printorder1" class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/carga-ordenes/edit') }}/{{ $legajo->id }}" disabled="true" style="font-color: withe">Imprimir ...</a>
                            

                            <!------------ MODAL DE NUEVA ORDEN ----------->
                            <div id="modal1" class="modal">
                              <div class="modal-content">
                                <div class="row">
                                  <div class="col s12">
                                    <div class="form-row">
                                      <div class="col s1" style="width: 70px;">
                                        <a class="btn-floating mb-1 btn-flat waves-effect waves-light red accent-2 white-text" href="#"><i class="material-icons">person_add</i></a>
                                      </div>
                                      <div class="col m10 s10">
                                        <h4>Agregar orden</h4>
                                      </div>
                                      <br>
                                    </div>

                                    <br><hr><br>
                                    
                                    <div class="row">
                            
                                      <div class="row">
                                        <div class="col m2 s2 input-field">
                                          <input id="ordennro2" name="ordennro2" type="number" step="1" class="validate" 
                                            value="{{ old('ordennro2',$legajo->ordennro) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt8" required>
                                          <label for="ordennro2" class="active">Nro. Orden</label>
                                          <small class="errorTxt8"></small>
                                        </div>
                                        {{-- {{ $edicion?'enabled':'disabled' }} --}}


                                        <div class="col m2 s2 input-field">
                                          <input id="dni_afiliado2" name="dni_afiliado2" type="number" step="1" class="validate" 
                                            value="{{ old('dni_afiliado2',$legajo->dni_afiliado) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt9" required>
                                          <label for="dni_afiliado2">DNI Afiliado</label>
                                          <small class="errorTxt9"></small>
                                        </div>
                                        
                                        
                                        <div class="col m5 s5 input-field">
                                          <input id="nom_afiliado2" name="nom_afiliado2" type="text" class="validate" 
                                            value="{{ old('nom_afiliado2',$legajo->nom_afiliado) }}"
                                            maxlength="50" autocomplete='off'
                                            data-error=".errorTxt10">
                                          <label for="nom_afiliado2">Afiliado</label>
                                          <small class="errorTxt10"></small>
                                        </div>
            
                                        <div class="col m3 s3 input-field">
                                          {{-- <input id='fecha' type="text" class="datepicker mr-2 mb-1" placeholder="Elija fecha" value="{{ old('fecha',$orden->fecha) }}"> --}}
                                          <input id="fecha2" name="fecha2" type="date" placeholder="dd/mm/aaaa" class="" 
                                                value="{{ old('fecha2',$legajo->fecha) }}"
                                                maxlength="10" autocomplete='off'
                                                required
                                                data-error=".errorTxt11">
                                          <label for="fecha2">Fecha Aut.</label>
                                          <small class="errorTxt11"></small>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col m3 s3 input-field">
                                          <select id="plan2" name="plan2" onchange="changePlan2(this)">
                                            <option value="" @if ($legajo->cod_conv == "")  selected   @endif  >Seleccione...</option>
                                            @foreach ($conv_os as $plan)
                                              <option value = "{{ $plan->cod_conv  }}" @if ( old('plan',$legajo->plan)  == $plan->cod_conv)  selected @endif>{{ $plan->cod_conv }}</option>
                                            @endforeach
                                          </select>
                                          <label>Convenio (Plan)</label>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <input id="nemotecnico_original2" name="nemotecnico_original2" type="text" autocomplete="off" maxlength="10" value="{{ old('cod_nemotecnico',$legajo->cod_nemotecnico) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                                        <search-nomenclador2 onchange="changeNomenclador2(this)"></search-nomenclador2>
            
                                        <div class="col m3 s3 input-field" style="width: 130px;">
                                          <select id="cod_nomen2" name="cod_nomen2" onchange="changePrestacion(this)">
                                            <option value="" @if ($legajo->nomenclador == "")  selected   @endif  >Seleccione...</option>
                                            @foreach ($nomencladores as $nomenclador)
                                              <option value = "{{ $nomenclador->cod_nemotecnico }}" @if ( old('cod_nomen2',$legajo->cod_nemotecnico)  == $nomenclador->cod_nemotecnico)  selected @endif>{{ $nomenclador->cod_nomen }}</option>
                                            @endforeach
                                          </select>
                                          <label>Nomenclador2</label>
                                        </div>
                                        
                                        {{-- {{ $edicion?'enabled':'disabled' }}  --}}
                                        <div class="col m3 s4 input-field">
                                          <select id="prestacion2" name="prestacion2" onchange="changePrestacion2(this)">
                                            <option value="" @if ($legajo->prestacion == "")  selected   @endif  >Seleccione prestación</option>
                                            @foreach ($prestaciones as $prestacion)
                                              <option value = "{{ $prestacion->cod_nomen  }}" @if ( old('prestacion2',$legajo->cod_nomen)  == $prestacion->cod_nomen)  selected @endif>{{ $prestacion->nom_prest }}</option>
                                            @endforeach
                                          </select>
                                          <label>Prestación</label>
                                        </div>
                                        
                                        <div class="col m1 s1 input-field" style="width: 90px;">
                                          <input id="cantidad2" name="cantidad2" type="number" class="validate" step="1"
                                            value="{{ old('cantidad2',$legajo->cantidad) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt4" required onchange="calcular2()">
                                          <label for="cantidad2" id="lblCantidad" name="lblCantidad">Cantidad</label>
                                          <small class="errorTxt14"></small>
                                        </div>
            
                                        <div class="col m2 s2 input-field" style="width: 130px;">
                                          <input id="precio2" name="precio2" type="number" class="validate" step="0.10"
                                            value="{{ old('precio2',$legajo->precio) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt15" onchange="calcular2()">
                                          <label for="precio2"  id="lblPrecio2" name="lblPrecio2">Importe</label>
                                          <small class="errorTxt15"></small>
                                        </div>
            
                                        <div class="col m2 s2 input-field" style="width: 130px;">
                                          <input id="importe2" name="importe2" type="number" class="validate" 
                                            value="{{ old('importe2',$legajo->importe) }}"
                                            {{ $edicion?'enabled':'disabled' }}
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt16" disabled>
                                          <label for="importe2" class="active" id="lblTotal2" name="lblTotal2">Total</label>
                                          <small class="errorTxt16"></small>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    {{-- <img src="{{asset('images/avatar/avatar-7.png')}}" alt="" class="circle"> --}}

                                    <div class="modal-footer">
                                      <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Cancelar</a>
                                      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="saveOrder()">Grabar</a>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>



                            
                            <!------------ MODAL DE MODIFICACION ----------->
                            <div id="modal2" class="modal">
                              <div class="modal-content">
                                <div class="row">
                                  <div class="col s12">
                                    <div class="form-row">
                                      <div class="col s1" style="width: 70px;">
                                        <a class="btn-floating mb-1 btn-flat waves-effect waves-light red accent-2 white-text" href="#"><i class="material-icons">delete</i></a>
                                      </div>
                                      <div class="col m10 s10">
                                        <h4>Modificar orden</h4>
                                      </div>
                                      <br>
                                    </div>

                                    <br><hr><br>
                                    
                                    <div class="row">
                            
                                      <div class="row">
                                        <div class="col m2 s2 input-field">
                                          <input id="ordennro" name="ordennro" type="number" step="1" class="validate" 
                                            value="{{ old('ordennro',$legajo->ordennro) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt8" required>
                                          <label for="ordennro">Nro. Orden</label>
                                          <small class="errorTxt8"></small>
                                        </div>
                                        {{-- {{ $edicion?'enabled':'disabled' }} --}}


                                        <div class="col m2 s2 input-field">
                                          <input id="dni_afiliado" name="dni_afiliado" type="number" step="1" class="validate" 
                                            value="{{ old('dni_afiliado',$legajo->dni_afiliado) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt9" required>
                                          <label for="dni_afiliado">DNI Afiliado</label>
                                          <small class="errorTxt9"></small>
                                        </div>
                                        
                                        
                                        <div class="col m5 s5 input-field">
                                          <input id="nom_afiliado" name="nom_afiliado" type="text" class="validate" 
                                            value="{{ old('nom_afiliado',$legajo->nom_afiliado) }}"
                                            maxlength="50" autocomplete='off'
                                            data-error=".errorTxt10">
                                          <label for="nom_afiliado">Afiliado</label>
                                          <small class="errorTxt10"></small>
                                        </div>
            
                                        <div class="col m3 s3 input-field">
                                          {{-- <input id='fecha' type="text" class="datepicker mr-2 mb-1" placeholder="Elija fecha" value="{{ old('fecha',$orden->fecha) }}"> --}}
                                          <input id="fecha" name="fecha" type="date" placeholder="dd/mm/aaaa" class="" 
                                                value="{{ old('fecha',$legajo->fecha) }}"
                                                maxlength="10" autocomplete='off'
                                                required
                                                data-error=".errorTxt11">
                                          <label for="fecha">Fecha Aut.</label>
                                          <small class="errorTxt11"></small>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col m3 s3 input-field">
                                          <select id="plan" name="plan" onchange="changePlan(this)">
                                            <option value="" @if ($legajo->cod_conv == "")  selected   @endif  >Seleccione...</option>
                                            @foreach ($conv_os as $plan)
                                              <option value = "{{ $plan->cod_conv  }}" @if ( old('plan',$legajo->plan)  == $plan->cod_conv)  selected @endif>{{ $plan->cod_conv }}</option>
                                            @endforeach
                                          </select>
                                          <label>Convenio (Plan)</label>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <input id="nemotecnico_original" name="nemotecnico_original" type="text" autocomplete="off" maxlength="10" value="{{ old('cod_nemotecnico',$legajo->cod_nemotecnico) }}" style="width: 100px;margin-bottom: 0px;" hidden>
                                        <search-nomenclador onchange="changeNomenclador(this)"></search-nomenclador>
            
                                        <div class="col m3 s3 input-field">
                                          <select id="cod_nomen" name="cod_nomen" onchange="changePrestacion(this)">
                                            <option value="" @if ($legajo->nomenclador == "")  selected   @endif  >Seleccione...</option>
                                            @foreach ($nomencladores as $nomenclador)
                                              <option value = "{{ $nomenclador->cod_nemotecnico  }}" @if ( old('cod_nomen',$legajo->cod_nemotecnico)  == $nomenclador->cod_nemotecnico)  selected @endif>{{ $nomenclador->cod_nomen }}</option>
                                            @endforeach
                                          </select>
                                          <label>Nomenclador 1</label>
                                        </div>
                                        
                                        {{-- {{ $edicion?'enabled':'disabled' }}  --}}
                                        <div class="col m3 s4 input-field">
                                          <select id="prestacion" name="prestacion" onchange="changePrestacion2(this)">
                                            <option value="" @if ($legajo->prestacion == "")  selected   @endif  >Seleccione prestación</option>
                                            @foreach ($prestaciones as $prestacion)
                                              <option value = "{{ $prestacion->cod_nomen  }}" @if ( old('prestacion',$legajo->cod_nomen)  == $prestacion->cod_nomen)  selected @endif>{{ $prestacion->nom_prest }}</option>
                                            @endforeach
                                          </select>
                                          <label>Prestación 1</label>
                                        </div>
                                        
                                        <div class="col m1 s1 input-field" style="width: 110px;">
                                          <input id="cantidad" name="cantidad" type="number" class="validate" step="1"
                                            value="{{ old('cantidad',$legajo->cantidad) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt4" required onchange="calcular()">
                                          <label for="cantidad" id="lblCantidad" name="lblCantidad">Cantidad</label>
                                          <small class="errorTxt14"></small>
                                        </div>
            
                                        <div class="col m2 s2 input-field" style="width: 130px;">
                                          <input id="precio" name="precio" type="number" class="validate" step="0.10"
                                            value="{{ old('precio',$legajo->precio) }}"
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt15" onchange="calcular()">
                                          <label for="precio"  id="lblPrecio" name="lblPrecio">Importe</label>
                                          <small class="errorTxt15"></small>
                                        </div>
            
                                        <div class="col m2 s2 input-field" style="width: 130px;">
                                          <input id="importe" name="importe" type="number" class="validate" 
                                            value="{{ old('importe',$legajo->importe) }}"
                                            {{ $edicion?'enabled':'disabled' }}
                                            maxlength="11" autocomplete='off'
                                            data-error=".errorTxt16" disabled>
                                          <label for="importe" class="active" id="lblImporte" name="lblImporte">Total</label>
                                          <small class="errorTxt16"></small>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    {{-- <img src="{{asset('images/avatar/avatar-7.png')}}" alt="" class="circle"> --}}

                                    <div class="modal-footer">
                                      <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Cancelar</a>
                                      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="updateOrder()">Grabar</a>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                            
                            
                            <!------------ MODAL DE BORRADO ----------->
                            <div id="modal3" class="modal bottom-sheet">
                              <div class="modal-content">
                                <div class="row">
                                  <div class="col s12">
                                    <div class="form-row">
                                      <div class="col s1" style="width: 70px;">
                                        <a class="btn-floating mb-1 btn-flat waves-effect waves-light red accent-2 white-text" href="#"><i class="material-icons">delete</i></a>
                                      </div>
                                      <div class="col m5 s5">
                                        <h4>Eliminar orden ?</h4>
                                      </div>
                                      <div class="col m3 s3">
                                        <div class="input-field inline">
                                          <input id="id_order" name="id_order" type="number" autocomplete="off" maxlength="10" value="0" style="width: 100px;margin-bottom: 0px;" disabled hidden>
                                        </div>
                                        {{-- <label for="id_order">Nro.</label> --}}
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col m2 s2">
                                      </div>
                                      <div class="col m9 s9">
                                        <span class="title">Está seguro de eliminar la orden seleccionada ...</span>
                                      </div>
                                    </div>
                                    
                                    {{-- <img src="{{asset('images/avatar/avatar-7.png')}}" alt="" class="circle"> --}}

                                    <div class="modal-footer">
                                      <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Cancelar</a>
                                      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="eraseOrder()">Borrar</a>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                            
                            {{-- <a title="Borrar repuesto" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                                <em class="icon-trash" style="color: white"></em> &nbsp;Borrar
                            </a> --}}
                          </div>
                          
                          <div id="modal" class="modal">
                            <div class="modal-content">
                              <h4>Modal Header</h4>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                            </div>
                            <div class="modal-footer">
                              <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Disagree</a>
                              <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
                            </div>
                          </div>
                          
                          <br>
                          <br>
                          <br>
                          <div class="row">
                            
                          <!-- START table-responsive-->
                          <div class="col s12">
                            <table id="table-ordenes" class="bordered">
                              <thead>
                                  <tr>
                                    <th data-field="name">Nro.1</th>
                                    <th data-field="name">Fecha</th>
                                    <th data-field="name">Paciente</th>
                                    <th data-field="name">Práctica</th>
                                    <th data-field="name">Cantidad</th>
                                    <th data-field="name">Importe</th>
                                    <th data-field="name"></th>
                                    <th data-field="name"></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                              </tbody>
                            </table>
                            <br>

                            <div class="col m12 s12">
                              <a id="addorder2" name="addorder2" class="waves-effect waves-light btn mb-1 mr-1 modal-trigger" href="#modal1" disabled="true" onclick="addOrder()">Agregar orden</a>
                              <a id="printorder2" name="printorder2" class="waves-effect waves-light red green btn mb-1 mr-1" href="{{ asset('/carga-ordenes/edit') }}/{{ $legajo->id }}" disabled="true" style="font-color: withe">Imprimir ...</a>
                              {{-- <a title="Borrar repuesto" class="waves-effect waves-light red darken-1 btn mb-1 mr-1" style="color: white" onclick="showModalBorrar({{ $legajo->id }})">
                                  <em class="icon-trash" style="color: white"></em> &nbsp;Borrar
                              </a> --}}
                            </div>

                            <br>

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

{{-- page scripts --}}
@section('page-script')
<script src="{{ asset('js/app.js') }}"></script>  {{-- defer --}}
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
{{-- <script src="{{asset('js/scripts/page-users.js')}}"></script> --}}

<script>
function changeObra(e) {
  var obraSocial = document.getElementById("cod_os").value;

  document.getElementById("det_os").value = obraSocial;
  //$("det_os").val("1050");
  $("#det_os").formSelect(); // Refrescar

  var valor1 = document.getElementById("det_os").value;
}

function changeObra2(e) {
  var obraSocial = document.getElementById("det_os").value;

  document.getElementById("cod_os").value = obraSocial;
  
  $("#lblCod_os").addClass('active');
}

function changeProfessional(e) {
  //var valor1 = document.getElementById("det_os").value;
  //console.log(valor1);

  var matricula = document.getElementById("mat_prov_cole").value;

  document.getElementById("profesional").value = matricula;
  
  $("#profesional").formSelect(); // Refrescar
}

function changeProfessional2(e) {
  var ape_nomProfesional = document.getElementById("profesional").value;

  document.getElementById("mat_prov_cole").value = ape_nomProfesional;
  
  $("#lblMatricula").addClass('active');
}

// Usado en agregar orden
function changeNomenclador2(e) {
  var Nomenclador = document.getElementById("id_nomen2").value;

  document.getElementById("cod_nomen2").value = Nomenclador;
  $("#cod_nomen2").formSelect(); // Refrescar

  changePrestacion2(1)
}

// Usado en modificacion de orden
function changeNomenclador(e) {
  var Nomenclador = document.getElementById("id_nomen").value;

  document.getElementById("cod_nomen").value = Nomenclador;
  $("#cod_nomen").formSelect(); // Refrescar

  changePrestacion(1)
}

// Usado en agregar orden
function changePrestacion2(e) {
  var nomenclador = $( "#nomenclador2 option:selected" ).text();
  
  document.getElementById("prestacion2").value = nomenclador;
  $("#prestacion2").formSelect(); // Refrescar

  buscoPrecio(1)
}

// Usado en modificion de orden
function changePrestacion(e) {
  var nomenclador = $( "#nomenclador option:selected" ).text();
  
  document.getElementById("prestacion").value = nomenclador;
  $("#prestacion").formSelect(); // Refrescar

  buscoPrecio(1)
}


// function changePrestacion2(e) {
//   var detPrestacion = document.getElementById("prestacion2").value;

//   document.getElementById("nomenclador").value = detPrestacion;
// }

function changePlan2(e) {
  // var nomenclador = $( "#nomenclador option:selected" ).text();
  
  // document.getElementById("prestacion").value = nomenclador;
  // $("#prestacion").formSelect(); // Refrescar

  // buscoPrecio(1)
}


function changePlan(e) {
  // var nomenclador = $( "#nomenclador option:selected" ).text();
  
  // document.getElementById("prestacion").value = nomenclador;
  // $("#prestacion").formSelect(); // Refrescar

  // buscoPrecio(1)
}


function buscoPrecio2(e) {
  var codPrestacion = document.getElementById("prestacion2").value

  $.ajax({
      url: "/searchPrecios/" + codPrestacion,
      data: "",
      dataType: "json",
      method: "GET",
      success: function(result)
      {
        if (result != null) {
          
          document.getElementById("precio2").value = result[0].importe;

          $("#lblPrecio2").addClass('active');

        } else {
          alert('Error en la devolucion del precio');
        } 
      },
      fail: function(){
          alert("Error buscando vehiculos...");
      },
      beforeSend: function(){

      }
    });
}


function buscoPrecio(e) {
  var codPrestacion = document.getElementById("prestacion").value

  $.ajax({
      url: "/searchPrecios/" + codPrestacion,
      data: "",
      dataType: "json",
      method: "GET",
      success: function(result)
      {
        if (result != null) {
          
          document.getElementById("precio").value = result[0].importe;

          $("#lblPrecio").addClass('active');

        } else {
          alert('Error en la devolucion del precio');
        } 
      },
      fail: function(){
          alert("Error buscando vehiculos...");
      },
      beforeSend: function(){

      }
    });
}

//-------------- Calcular importe en nueva orden ---------------------
function calcular2(e) {
  var precio = 0
  var cantidad = 0

  cantidad = document.getElementById("cantidad2").value;
  precio = document.getElementById("precio2").value;

  document.getElementById("importe2").value = precio * cantidad;
  
  $("#lblTotal2").addClass('active');
}

//-------------- Calcular importe en modificar orden ---------------------
function calcular(e) {
  var precio = 0
  var cantidad = 0

  cantidad = document.getElementById("cantidad").value;
  precio = document.getElementById("precio").value;

  document.getElementById("importe").value = precio * cantidad;
  
  $("#lblTotal").addClass('active');
}


function addOrder() {
  // $('#modal1').modal({
  //   dismissible: true, // Modal can be dismissed by clicking outside of the modal
  //   opacity: .5, // Opacity of modal background
  //   inDuration: 300, // Transition in duration
  //   outDuration: 200, // Transition out duration
  //   startingTop: '4%', // Starting top style attribute
  //   endingTop: '10%', // Ending top style attribute
  //   ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
  //   alert("Ready");
  //   console.log(modal, trigger);
  //   },
  //   complete: function() { alert('Closed'); } // Callback for Modal close
  //   }
  // );

  // alert('llamo modal1')

  document.getElementById("ordennro2").focus();

  document.getElementById("ordennro2").value = ""
  document.getElementById("dni_afiliado2").value = ""
  document.getElementById("nom_afiliado2").value = ""
  document.getElementById("fecha2").value = "2022-07-01"
  document.getElementById("plan2").value = 18
  document.getElementById("nemotecnico_original2").value = 1
  document.getElementById("id_nomen").value = 1

  document.getElementById("cod_nomen2").value = 1
  $("#cod_nomen2").formSelect(); // Refrescar

  document.getElementById("prestacion2").value = 330101
  $("#prestacion2").formSelect(); // Refrescar
  
  document.getElementById("cantidad2").value = 1
  document.getElementById("precio2").value = 853
  //alert(result.importe)
  document.getElementById("importe2").value = 853
}


function saveOrder() {
  
  var periodo = document.getElementById("periodo").value
  var cod_os = document.getElementById("cod_os").value
  var mat_prov_cole = document.getElementById("mat_prov_cole").value
  var ordennro = document.getElementById("ordennro2").value
  var dni_afiliado = document.getElementById("dni_afiliado2").value
  var nom_afiliado = document.getElementById("nom_afiliado2").value
  var fecha = document.getElementById("fecha2").value
  var plan = document.getElementById("plan2").value
  var cod_nemotecnico = document.getElementById("id_nomen2").value
  
  //var cod_nomen = document.getElementById("prestacion").value
  var select = document.getElementById('cod_nomen2');
  var cod_nomen = select.options[select.selectedIndex].text;
  
  var cantidad = document.getElementById("cantidad2").value
  var precio = document.getElementById("precio2").value
  var importe = document.getElementById("importe2").value

  $.ajax({
    url: "/api/ordenessave/",
    data: "periodo="+periodo+"&cod_os="+cod_os+"&plan="+plan+"&mat_prov_cole="+mat_prov_cole+"&ordennro="+ordennro+"&dni_afiliado="+dni_afiliado+"&nom_afiliado="+nom_afiliado+"&fecha="+fecha+"&cod_nemotecnico="+cod_nemotecnico+"&cod_nomen="+cod_nomen+"&cantidad="+cantidad+"&precio="+precio+"&importe="+importe+"&_token={{ csrf_token()}}",
    dataType: "json",
    method: "post",
    success: function(result) {
      
      console.table(result)

    },
    fail: function() {
      alert("Error buscando ordenes ...");
    },
    beforeSend: function(){
      
    }
  });

  verOrdenes()
}


function editOrder(id) {
  
  document.getElementById("id_order").value = id;

  $.ajax({
    url: "/api/ordenesedit/" + id,
    data: "id="+id+"&_token={{ csrf_token()}}",
    dataType: "json",
    method: "GET",
    success: function(result) {
      
      //console.table(result)
      document.getElementById("id_order").value = result.id
      document.getElementById("ordennro").value = result.ordennro
      document.getElementById("dni_afiliado").value = result.dni_afiliado
      document.getElementById("nom_afiliado").value = result.nom_afiliado
      document.getElementById("fecha").value = result.fecha
      document.getElementById("plan").value = result.plan
      document.getElementById("nemotecnico_original").value = result.cod_nemotecnico
      document.getElementById("id_nomen").value = result.cod_nemotecnico  // ver
      
      document.getElementById("cod_nomen").value = result.cod_nemotecnico    // nomenclador
      $("#cod_nomen").formSelect(); // Refrescar
      
      document.getElementById("prestacion").value = result.cod_nomen    // nomenclador
      $("#prestacion").formSelect(); // Refrescar
      
      document.getElementById("cantidad").value = result.cantidad
      document.getElementById("precio").value = result.precio
      //alert(result.importe)
      document.getElementById("importe").value = result.importe

    },
    fail: function() {
      alert("Error buscando ordenes ...");
    },
    beforeSend: function(){
      
    }
  });
}

function updateOrder() {
  var id = document.getElementById("id_order").value
  var periodo = document.getElementById("periodo").value
  var cod_os = document.getElementById("cod_os").value
  var mat_prov_cole = document.getElementById("mat_prov_cole").value
  var ordennro = document.getElementById("ordennro").value
  var dni_afiliado = document.getElementById("dni_afiliado").value
  var nom_afiliado = document.getElementById("nom_afiliado").value
  var fecha = document.getElementById("fecha").value
  var plan = document.getElementById("plan").value
  var cod_nemotecnico = document.getElementById("id_nomen").value

  var select = document.getElementById('cod_nomen');
  var cod_nomen = select.options[select.selectedIndex].text;

  var cantidad = document.getElementById("cantidad").value
  var precio = document.getElementById("precio").value
  var importe = document.getElementById("importe").value

  //cod_nomen

  // data: "id="+id+"&_token={{ csrf_token()}}",
  $.ajax({
    url: "/api/ordenesupdate/" + id,
    data: "id="+id+"&periodo="+periodo+"&cod_os="+cod_os+"&plan="+plan+"&mat_prov_cole="+mat_prov_cole+"&ordennro="+ordennro+"&dni_afiliado="+dni_afiliado+"&nom_afiliado="+nom_afiliado+"&fecha="+fecha+"&cod_nemotecnico="+cod_nemotecnico+"&cod_nomen="+cod_nomen+"&cantidad="+cantidad+"&precio="+precio+"&importe="+importe+"&_token={{ csrf_token()}}",
    dataType: "json",
    method: "POST",
    success: function(result) {
      
      console.table(result)

    },
    fail: function(xhr, textStatus, errorThrown) {
      alert(xhr.responseText);
    },
    beforeSend: function(){
      
    }
  });

  verOrdenes()
}


function deleteOrder(id) {
  //alert(id)
  document.getElementById("id_order").value = id;
}

function eraseOrder() {
  //alert(id)
  var id = document.getElementById("id_order").value;

  $.ajax({
    url: "/api/ordenesdelete/" + id,
    data: "id="+id+"&_token={{ csrf_token()}}",
    dataType: "json",
    method: "POST",
    success: function(result) {
      
      //console.table(result)

    },
    fail: function() {
      alert("Error buscando ordenes ...");
    },
    beforeSend: function(){
      
    }
  });

  verOrdenes()
}

function verOrdenes() {
  var obra = 0
  var matricula = 0
  var periodo = ""
  var event_data = '';

  // Blank table
  const table = document.getElementById("table-ordenes");
  table.innerHTML = "";

  event_data += '<thead><tr>';
  event_data += '<th data-field="name">Nro.</th>'
  event_data += '<th data-field="name">Fecha</th>'
  event_data += '<th data-field="name">Paciente</th>'
  event_data += '<th data-field="name">Práctica</th>'
  event_data += '<th data-field="name">Cantidad</th>'
  event_data += '<th data-field="name">Importe</th>'
  event_data += '<th data-field="name"></th>'
  event_data += '<th data-field="name"></th>'
  event_data += '</tr></thead>'
  $("#table-ordenes").append(event_data);

  // <thead>
  //     <tr>
  //       <th data-field="name">Código</th>
  //       <th data-field="name">Cód.Nomenclador</th>
  //       <th data-field="name">Nombre Nomenclador</th>
  //       <th data-field="name">Importe</th>
  //     </tr>
  // </thead>

  obra = document.getElementById("cod_os").value
  matricula = document.getElementById("mat_prov_cole").value
  periodo = document.getElementById("periodo").value

  if (matricula == 0) {
    return false;
  }

  // create option using DOM
  var x = document.getElementById("table-ordenes");

  $.ajax({
    url: "/api/ordenes/" + obra + "/" + matricula + "/" + periodo,
    data: "",
    dataType: "json",
    method: "GET",
    success: function(result) {
      var event_data = '';
      $.each(result, function(index, value){
        //console.table(value);
        if (value.ordennro != null) {
          event_data += '<tr style="height: 14px;">';
          event_data += '<td style="height: 10px;">'+value.ordennro+'</td>';
          event_data += '<td style="height: 10px;">'+value.fecha+'</td>';
          event_data += '<td style="height: 10px;">'+value.nom_afiliado+'</td>';
          event_data += '<td style="height: 10px;">'+value.cod_nomen+'</td>';
          event_data += '<td style="height: 10px;">'+value.cantidad+'</td>';
          event_data += '<td style="height: 10px;">'+value.importe+'</td>';
          event_data += '<td style="height: 10px;"><a class="btn-floating mb-1 btn-flat waves-effect waves-light green accent-2 white-text modal-trigger " href="#modal2" onclick="editOrder(' + value.id + ')"><i class="material-icons">edit</i></a></td>';
          event_data += '<td style="height: 10px;"><a class="btn-floating mb-1 btn-flat waves-effect waves-light red accent-2 white-text modal-trigger " href="#modal3" onclick="deleteOrder(' + value.id + ')"><i class="material-icons">delete</i></a></td>';
          event_data += '<tr>';

        } else {
          
          document.getElementById("ordenes2").value = result.cuenta
          document.getElementById("total").value = result.suma

        }
      });
      $("#table-ordenes").append(event_data);

      //<a class="waves-effect waves-light btn modal-trigger mb-2" href="#modal3">Modal Bottom Sheet Style</a>

      const button1 = document.getElementById("addorder1")
      button1.disabled = false
      button1.removeAttribute('disabled');

      const button2 = document.getElementById("printorder1")
      button2.disabled = false
      button2.removeAttribute('disabled');

      const button3 = document.getElementById("addorder2")
      button3.disabled = false
      button3.removeAttribute('disabled');

      const button4 = document.getElementById("printorder2")
      button4.disabled = false
      button4.removeAttribute('disabled');

    },
    fail: function() {
      alert("Error buscando ordenes ...");
    },
    beforeSend: function(){
      
    }
  });

  
  $("#selectVehiculos").formSelect(); // Refrescar
}

</script>
@endsection