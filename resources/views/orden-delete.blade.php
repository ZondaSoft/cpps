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
      <!-- Form -->
      <form method="post" action="{{ asset( url('/orden/delete/'.$legajo->id) ) }}" enctype="multipart/form-data">
        
      {{ csrf_field() }}

        <div class="card">
          <div class="card-content m12 s12">

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

            <div class="row mb-12">
              <div class="col m7 s7 save">
                <h5 style="margin-top: 7px;margin-bottom: 14px;">Eliminar comprobante</h5>
              </div>

              <div class="col m2 s2 save">
                <button class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
                  <i class="material-icons mr-4">check</i>
                  <span class="btn-label"><i class="fa fa-check"></i>
                  </span>Eliminar
                </button>
              </div>

              <div class="col m2 s2 save">
                <a href="{{ asset('/home') }}" class="btn btn-light-indigo btn-block waves-effect waves-light">
                  <span class="responsive-text">Volver</span>
                </a>
              </div>
            </div>

            <!-- header section -->
            <div class="divider mb-3"></div>
            
            <!-- Cuenta -->
            <div class="row mb-1">
              <div class="col m6 s6 input-field">
                  <select id="cuenta" name="cuenta" disabled>
                      <option value = 0 @if ( old('cuenta',$legajo->cuenta)  == 0)  selected   @endif  >Egresos en efectivo</option>
                      <option value = 1 @if ( old('cuenta',$legajo->cuenta)  == 1)  selected   @endif  >Tarjeta credito (Ingresos)</option>
                      <option value = 2 @if ( old('cuenta',$legajo->cuenta)  == 2)  selected   @endif  >Tarjeta debito (Ingresos)</option>
                      <option value = 3 @if ( old('cuenta',$legajo->cuenta)  == 3)  selected   @endif  >Transf. Bancaria Galicia (Ingresos)</option>
                      <option value = 4 @if ( old('cuenta',$legajo->cuenta)  == 4)  selected   @endif  >Transf. Bancaria Macro (Ingresos)</option>
                      <option value = 5 @if ( old('cuenta',$legajo->cuenta)  == 5)  selected   @endif  >Ingresos en efectivo</option>
                      <option value = 5 @if ( old('cuenta',$legajo->cuenta)  == 5)  selected   @endif  >Ingresos en Cheques</option>
                  </select>
                  <label>Cod.Cuenta</label>
              </div>

              <div class="col xl1 m1 input-field">
              </div>

              <div class="col xl2 m1 input-field">
                <input id="fecha" name="fecha" type="text" placeholder="dd/mm/aaaa" class="datepicker validate" 
                  value="{{ old('fecha',$legajo->fecha) }}"
                  disabled
                  maxlength="8" autocomplete='off'
                  required
                  data-error=".errorTxt2">
                <label for="fecha">Fecha</label>
                <small class="errorTxt2"></small>
              </div>

              
              <input id="fecha2" name="fecha2" type="text"
                  value="{{ old('fecha',$legajo->fecha) }}"
                  hidden
                  maxlength="8" autocomplete='off'>

              <input id="id_caja" name="id_caja" type="text"
                  value="{{ old('id_caja',$legajo->id_caja) }}"
                  maxlength="12" autocomplete='off' hidden>
            </div>
            
            <!-- concepto o tipo de movimiento -->
            <div class="row mb-1">
              <div class="col m6 s6 input-field">
                  <select id="concepto" name="concepto" disabled }}>
                      <option value = '' @if ( old('vehiculo',$legajo->vehiculo)  == '')  selected   @endif  >Seleccione el concepto</option>
                      @foreach ($conceptos as $concepto)
                          <option value = "{{ $concepto->codigo  }}" @if ( old('concepto',$legajo->concepto)  == $concepto->codigo)  selected   @endif  >{{ $concepto->detalle }}   ({{ $concepto->codigo  }})</option>
                      @endforeach
                  </select>
                  <label>Concepto</label>
              </div>
            </div>

            <!-- Tipo de comprobante -->
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                <select id="tipo" name="tipo" disabled }}>
                    <option value = 0 @if ( old('tipo',$legajo->tipo)  == 0)  selected   @endif  >Factura</option>
                    <option value = 1 @if ( old('tipo',$legajo->tipo)  == 1)  selected   @endif  >Ticket</option>
                    <option value = 2 @if ( old('tipo',$legajo->tipo)  == 2)  selected   @endif  >Nota debito</option>
                    <option value = 3 @if ( old('tipo',$legajo->tipo)  == 3)  selected   @endif  >Nota de credito</option>
                    <option value = 4 @if ( old('tipo',$legajo->tipo)  == 4)  selected   @endif  >Ticket posnet</option>
                    <option value = 5 @if ( old('tipo',$legajo->tipo)  == 5)  selected   @endif  >Transferencia Bancaria</option>
                    <option value = 6 @if ( old('tipo',$legajo->tipo)  == 6)  selected   @endif  >Otros</option>
                    <option value = 7 @if ( old('tipo',$legajo->tipo)  == 7)  selected   @endif  >Sin comprobante</option>
                </select>
                <label>Comprobante tipo</label>
              </div>

              <div class="col xl1 m1 input-field">
              </div>
            
              <div class="col xl3 m3 input-field">
                <input id="numero" name="numero" type="text" placeholder="00000000" class="validate" 
                  value="{{ old('numero',$legajo->numero) }}"
                  disabled
                  maxlength="8" autocomplete='off'
                  required
                  data-error=".errorTxt5">
                <label for="numero">Nro. comprobante</label>
                <small class="errorTxt5"></small>
              </div>
            </div>

            <!-- Importe -->
            <div class="row mb-0">
              <div class="col xl3 m3 input-field">
                <input id="importe" name="importe" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('importe',$legajo->importe) }}"
                  disabled
                  maxlength="8" autocomplete='off' step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="importe">Importe</label>
                <small class="errorTxt6"></small>
              </div>
            </div>

            <!-- Observaciones -->
            <div class="row mb-1">
              <div class="col xl10 m10 input-field">
                <input id="comentarios" name="comentarios" type="text" placeholder="Comentarios"
                  value="{{ old('comentarios',$legajo->comentarios) }}" disabled>
                <label for="comentarios">Comentarios</label>
              </div>
            </div>

          </div>
        </div>
      
        <!-- Form -->
      </form>
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
    var punto = document.getElementById("clientes").value;
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