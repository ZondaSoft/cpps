{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page Title --}}
@section('title','Agregar Orden de Trabajo')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- app invoice View Page -->
<section class="invoice-edit-wrapper section">
  <div class="row">
    <!-- invoice view page -->
    <div class="col xl12 m12 s12">
      <div class="card">
        <div class="card-content m12 s12">

          <div class="row mb-12">
            <div class="col m5 s5 save">
              <h5 style="margin-top: 7px;margin-bottom: 14px;">Nuevo registro</h5>
            </div>

            <div class="col m3 s3 save">
              <a class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
                <i class="material-icons mr-4">check</i>
                <span class="responsive-text">Grabar</span>
              </a>
            </div>

            <div class="col m3 s3 save">
              <a class="btn btn-light-indigo btn-block waves-effect waves-light">
                <span class="responsive-text">Cancelar</span>
              </a>
            </div>
          </div>

          <div class="divider mb-3"></div>

          <!-- header section -->
          <!-- Cuenta -->
          <div class="row mb-6">
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
          </div>
          
          <div class="row mb-3">
            
              
            <div class="col xl3 m3 input-field">
              <input id="numero" name="numero" type="text" placeholder="00000000" class="validate" 
                value="{{ old('numero',$legajo->numero) }}"
                {{ $edicion?'':'disabled' }}
                {{ $agregar?'enabled autofocus=""':'disabled' }}
                maxlength="8" autocomplete='off'
                required
                data-error=".errorTxt1">
              <label for="numero">Nro. comprobante</label>
              <small class="errorTxt1"></small>
            </div>

            <div class="col xl3 m3 input-field">
              <input id="fecha" name="fecha" type="text" placeholder="dd/mm/aaaa" class="datepicker validate" 
                value="{{ old('fecha',$legajo->fecha) }}"
                disabled
                maxlength="10" autocomplete='off'
                required
                data-error=".errorTxt1">
              <label for="fecha">Fecha</label>
              <small class="errorTxt1"></small>
            </div>
          </div>
          
          <!-- concepto o tipo de movimiento -->
          <div class="row mb-6">
            <div class="col m6 s6 input-field">
                <select id="cliente" name="cliente" {{ $edicion?'enabled':'disabled' }} onchange="changeVehiculos()">
                    <option value = 0 @if ( old('vehiculo',$legajo->vehiculo)  == 0)  selected   @endif  >Seleccione el concepto</option>
                    @foreach ($clientes as $cliente)
                        <option value = "{{ $cliente->codigo  }}" @if ( old('cliente',$legajo->cliente)  == $cliente->codigo)  selected   @endif  >{{ $cliente->detalle }}   ({{ $cliente->codigo  }})</option>
                    @endforeach
                </select>
                <label>Concepto</label>
            </div>
          </div>


          <!-- invoice address and contact -->
          <div class="row mb-3">
            <div class="col m6 s6 input-field">
                <select id="cliente" name="cliente" {{ $edicion?'enabled':'disabled' }} onchange="changeVehiculos()">
                    <option value = 0 @if ( old('vehiculo',$legajo->vehiculo)  == 0)  selected   @endif  >Seleccione el cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value = "{{ $cliente->codigo  }}" @if ( old('cliente',$legajo->cliente)  == $cliente->codigo)  selected   @endif  >{{ $cliente->detalle }}   ({{ $cliente->codigo  }})</option>
                    @endforeach
                </select>
                <label>Cliente</label>
            </div>

            <div class="col m6 s6 input-field">
              <select id="selectVehiculos">
                  <option value = 0 @if ( old('vehiculo',$legajo->vehiculo)  == 0)  selected   @endif  >Seleccione Proveedor</option>
                  @foreach ($vehiculos as $vehiculo)
                      <option value = "{{ $vehiculo->codigo  }}" @if ( old('vehiculo',$legajo->vehiculo)  == $vehiculo->codigo)  selected   @endif  >{{ $vehiculo->detalle }}   ({{ $vehiculo->codigo  }})</option>
                  @endforeach
              </select>
              <label>Proveedor</label>
          </div>
          </div>

          <div class="col s12" style="padding-right: 0px;padding-left: 0px">
            <ul class="collapsible collapsible-accordion">
               <li>
                  <div class="collapsible-header waves-light gradient-45deg-purple-deep-orange lightrn-1 white-text">
                     <i class="material-icons">toll</i> Detalle de fallo y diagnostico
                  </div>
                  <div class="collapsible-body ">
                      <div class="row">
                        <form class="col s12">
                          <div class="row">
                            <div class="input-field col s12">
                              <textarea id="textarea2" class="materialize-textarea"></textarea>
                              <label for="textarea2">Detalle de las fallas detectadas</label>
                            </div>
                          </div>
                        </form>
                      </div>
                      
                      <div class="row">
                        <form class="col s12">
                          <div class="row">
                            <div class="input-field col s12">
                              <textarea id="textarea3" class="materialize-textarea"></textarea>
                              <label for="textarea3">Detalle de las soluciones requeridas</label>
                            </div>
                          </div>
                        </form>
                      </div>
  
                  </div>
               </li>

               <!--       Trabajos realizados        -->
               <li>
                  <div class="collapsible-header gradient-45deg-red-pink accent-2 white-text">
                     <i class="material-icons">timeline</i> Trabajos realizados
                  </div>
                  <div class="collapsible-body">

                      <!-- product details table-->
                      <div class="invoice-product-details mb-3">
                        <form class="form invoice-item-repeater">
                          <div data-repeater-list="group-a">
                            <div class="mb-2" data-repeater-item>
                              <!-- invoice Titles -->
                              <div class="row mb-1">
                                <div class="col s3 m4">
                                  <h6 class="m-0">Item</h6>
                                </div>
                                <div class="col s3">
                                  <h6 class="m-0">Costo</h6>
                                </div>
                                <div class="col s3">
                                  <h6 class="m-0">Cantidad</h6>
                                </div>
                                <div class="col s3 m2">
                                  <h6 class="m-0">Total</h6>
                                </div>
                              </div>
                              <div class="invoice-item display-flex mb-1">
                                <div class="invoice-item-filed row pt-1">
                                  <div class="col s12 m4 input-field">
                                    <select class="invoice-item-select browser-default">
                                      <option value="Frest Admin Template">Repuesto 1</option>
                                      <option value="Stack Admin Template">Repuesto 2</option>
                                      <option value="Robust Admin Template">Repuesto 3</option>
                                      <option value="Apex Admin Template">Repuesto 4</option>
                                      <option value="Modern Admin Template">Repuesto 5</option>
                                    </select>
                                  </div>
                                  <div class="col m3 s12 input-field">
                                    <input type="text" placeholder="0">
                                  </div>
                                  <div class="col m3 s12 input-field">
                                    <input type="text" placeholder="0">
                                  </div>
                                  <div class="col m2 s12 input-field">
                                    <input type="text" placeholder="$00" disabled>
                                  </div>
                                  <div class="col m4 s12 input-field">
                                    <input type="text" class="invoice-item-desc"
                                      value="Detalle del arreglo">
                                  </div>
                                </div>
                                <div class="invoice-icon display-flex flex-column justify-content-between">
                                  <span data-repeater-delete class="delete-row-btn">
                                    <i class="material-icons">clear</i>
                                  </span>
                                  <div class="dropdown">
                                    <i class="material-icons dropdown-button" data-target="dropdown-discount">brightness_low</i>
                                    <div class="dropdown-content" id="dropdown-discount">
                                      <div class="row mr-0 ml-0">
                                        <div class="col s12 input-field">
                                          <label for="discount">Discount(%)</label>
                                          <input type="number" id="discount" placeholder="0">
                                        </div>
                                        <div class="col s6">
                                          <select id="Tax1" class="invoice-tax browser-default">
                                            <option value="0%" selected disabled>Tax1</option>
                                            <option value="1%">1%</option>
                                            <option value="10%">10%</option>
                                            <option value="18%">18%</option>
                                            <option value="40%">40%</option>
                                          </select>
                                        </div>
                                        <div class="col s6">
                                          <select id="Tax2" class="invoice-tax browser-default">
                                            <option value="0%" selected disabled>Tax2</option>
                                            <option value="1%">1%</option>
                                            <option value="10%">10%</option>
                                            <option value="18%">18%</option>
                                            <option value="40%">40%</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="display-flex justify-content-between mt-4">
                                        <button type="button" class="btn invoice-apply-btn">
                                          <span>Apply</span>
                                        </button>
                                        <button type="button" class="btn invoice-cancel-btn ml-1 indigo">
                                          <span>Cancel</span>
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="input-field">
                            <button class="btn invoice-repeat-btn" data-repeater-create type="button">
                              <i class="material-icons left">add</i>
                              <span>Agregar Item 2</span>
                            </button>
                          </div>
                        </form>
                      </div>

                      
                      <p>.
                      </p>
                      <p><br>
                      </p>
                      <p><br>
                      </p>
                  </div>
               </li>

               <!--       Trabajos realizados        -->
               <li>
                <div class="collapsible-header gradient-45deg-indigo-light-blue white-text">
                   <i class="material-icons">account_balance_wallet</i> Otra información
                </div>
                <div class="collapsible-body">

                    <!-- product details table-->
                    <div class="invoice-product-details mb-3">
                      <form class="form invoice-item-repeater">
                        <div data-repeater-list="group-a">
                          <div class="mb-2" data-repeater-item>
                            <!-- invoice Titles -->
                            <div class="row mb-1">
                              <div class="col s3 m4">
                                <h6 class="m-0">Item</h6>
                              </div>
                              <div class="col s3">
                                <h6 class="m-0">Costo</h6>
                              </div>
                              <div class="col s3">
                                <h6 class="m-0">Cantidad</h6>
                              </div>
                              <div class="col s3 m2">
                                <h6 class="m-0">Total</h6>
                              </div>
                            </div>
                            <div class="invoice-item display-flex mb-1">
                              <div class="invoice-item-filed row pt-1">
                                <div class="col s12 m4 input-field">
                                  <select class="invoice-item-select browser-default">
                                    <option value="Frest Admin Template">Repuesto 1</option>
                                    <option value="Stack Admin Template">Repuesto 2</option>
                                    <option value="Robust Admin Template">Repuesto 3</option>
                                    <option value="Apex Admin Template">Repuesto 4</option>
                                    <option value="Modern Admin Template">Repuesto 5</option>
                                  </select>
                                </div>
                                <div class="col m3 s12 input-field">
                                  <input type="text" placeholder="0">
                                </div>
                                <div class="col m3 s12 input-field">
                                  <input type="text" placeholder="0">
                                </div>
                                <div class="col m2 s12 input-field">
                                  <input type="text" placeholder="$00" disabled>
                                </div>
                                <div class="col m4 s12 input-field">
                                  <input type="text" class="invoice-item-desc"
                                    value="Detalle del arreglo">
                                </div>
                              </div>
                              <div class="invoice-icon display-flex flex-column justify-content-between">
                                <span data-repeater-delete class="delete-row-btn">
                                  <i class="material-icons">clear</i>
                                </span>
                                <div class="dropdown">
                                  <i class="material-icons dropdown-button" data-target="dropdown-discount">brightness_low</i>
                                  <div class="dropdown-content" id="dropdown-discount">
                                    <div class="row mr-0 ml-0">
                                      <div class="col s12 input-field">
                                        <label for="discount">Discount(%)</label>
                                        <input type="number" id="discount" placeholder="0">
                                      </div>
                                      <div class="col s6">
                                        <select id="Tax1" class="invoice-tax browser-default">
                                          <option value="0%" selected disabled>Tax1</option>
                                          <option value="1%">1%</option>
                                          <option value="10%">10%</option>
                                          <option value="18%">18%</option>
                                          <option value="40%">40%</option>
                                        </select>
                                      </div>
                                      <div class="col s6">
                                        <select id="Tax2" class="invoice-tax browser-default">
                                          <option value="0%" selected disabled>Tax2</option>
                                          <option value="1%">1%</option>
                                          <option value="10%">10%</option>
                                          <option value="18%">18%</option>
                                          <option value="40%">40%</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="display-flex justify-content-between mt-4">
                                      <button type="button" class="btn invoice-apply-btn">
                                        <span>Apply</span>
                                      </button>
                                      <button type="button" class="btn invoice-cancel-btn ml-1 indigo">
                                        <span>Cancel</span>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="input-field">
                          <button class="btn invoice-repeat-btn" data-repeater-create type="button">
                            <i class="material-icons left">add</i>
                            <span>Agregar Item 2</span>
                          </button>
                        </div>
                      </form>
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

          <!-- invoice subtotal -->
          <div class="invoice-subtotal">
            <div class="row">
              <div class="col m5 s12">
                <div class="input-field">
                  <input type="text" placeholder="Comentarios al pie">
                </div>
                
              </div>
              <div class="col xl4 m7 s12 offset-xl3">
                <ul>
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title">Subtotal</span>
                    <h6 class="invoice-subtotal-value">$00.00</h6>
                  </li>
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title">Descuento</span>
                    <h6 class="invoice-subtotal-value">- $ 00.00</h6>
                  </li>
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title">Impuestos</span>
                    <h6 class="invoice-subtotal-value">21%</h6>
                  </li>
                  <li>
                    <div class="divider mt-2 mb-2"></div>
                  </li>
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title">Total</span>
                    <h6 class="invoice-subtotal-value">$ 00.00</h6>
                  </li>
                  
                  <li class=" mt-2">
                    <a href="{{asset('app-invoice-view')}}" class="btn btn-block waves-effect waves-light">Preview</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- invoice action  -->
    {{-- <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
        <div class="card-content">
          <div class="invoice-action-btn">
            <a class="btn indigo waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-4">check</i>
              <span class="responsive-text">Grabar</span>
            </a>
          </div>
          <div class="invoice-action-btn">
            <a class="btn btn-light-indigo btn-block waves-effect waves-light">
              <span class="responsive-text">Descargar</span>
            </a>
          </div>
          <div class="row invoice-action-btn">
            <div class="col s6 preview">
              <a class="btn btn-light-indigo btn-block waves-effect waves-light">
                <span class="responsive-text">Preview</span>
              </a>
            </div>
            <div class="col s6 save">
              <a class="btn btn-light-indigo btn-block waves-effect waves-light">
                <span class="responsive-text">Cancelar</span>
              </a>
            </div>
          </div>
          <div class="invoice-action-btn">
            <a class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-3">attach_money</i>
              <span class="responsive-text">Facturar</span>
            </a>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/form_repeater/jquery.repeater.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/app-invoice.js')}}"></script>

<script>
  function changeVehiculos() {
    var punto = document.getElementById("cliente").value;
    var i = 0

    // Blank the select
    while (selectVehiculos.options.length > 0) {
        selectVehiculos.remove(0);
    }
    
    // create option using DOM
    var x = document.getElementById("selectVehiculos");

    $.ajax({
      url: "/api/vehiculos/" + punto,
      data: "",
      dataType: "json",
      method: "GET",
      success: function(result)
      {
        console.log(result)
        if (result == null) {
          swal("La novedad no puede editarse !", "No se encontro el registro asociado...")
        } else {
          result.forEach(element => {
            x.add(new Option(result[i].detalle, result[i].codigo));
            i++;
          });
          
          console.log('Elementos creados : ' + i)
          if (i == 0) {
            x.add(new Option("No existen vehiculos", ""));
          }
          
          $("#selectVehiculos").formSelect(); // Refrescar
        }
      },
      fail: function(){
          alert("Error buscando vehiculos...");
      },
      beforeSend: function(){

      }
    });

    
    $("#selectVehiculos").formSelect(); // Refrescar
    
  }
</script>
@endsection