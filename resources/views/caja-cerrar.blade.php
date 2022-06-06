{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page Title --}}
@section('title','Cierre de Caja')

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
      <form method="post" action="{{ asset( url('/caja-cerrar/' . $id_caja) ) }}" enctype="multipart/form-data">
        
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
                <h5 style="margin-top: 7px;margin-bottom: 14px;">Cierre de Caja</h5>
              </div>

              <div class="col m2 s2 save">
                <button class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
                  <i class="material-icons mr-4">check</i>
                  <span class="btn-label"><i class="fa fa-check"></i>
                  </span>Confirmar
                </button>
              </div>

              <div class="col m2 s2 save">
                <a class="btn btn-light-indigo btn-block waves-effect waves-light" href="{{ asset('/home') }}">
                  <span class="responsive-text">Cancelar</span>
                </a>
              </div>
            </div>

            <!-- header section -->
            <div class="divider mb-3"></div>
            
            <!-- Cuenta -->
            <div class="row mb-1">
              <div class="col xl2 m2 input-field">
                <input id="numero" name="numero" type="text" placeholder="00000000" class="validate" 
                  value="{{ old('numero',$id_caja) }}"
                  disabled data-error=".errorTxt1">
                <label for="numero">Nro. de Caja</label>
                <small class="errorTxt1"></small>
              </div>

              <div class="col xl1 m1 input-field">
              </div>

              <div class="col xl2 m3 input-field">
                <input id="fecha" name="fecha" type="date" placeholder="dd/mm/aaaa" class="" 
                  value="{{ old('fecha',$fechaActual) }}"
                  disabled
                  maxlength="8" autocomplete='off'
                  required
                  data-error=".errorTxt2">
                <label for="fecha">Fecha</label>
                <small class="errorTxt2"></small>
              </div>

              
              <input id="fecha2" name="fecha2" type="text"
                  value="{{ old('fecha',$fechaActual) }}"
                  hidden
                  maxlength="8" autocomplete='off'>

              <input id="id_caja" name="id_caja" type="text"
                  value="{{ old('id_caja',$id_caja) }}"
                  maxlength="12" autocomplete='off' hidden>
            </div>
            
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                <select id="cuenta" name="cuenta" disabled }}>
                    <option value = 0 selected>Contado efectivo</option>
                </select>
                <label>Cod.Cuenta</label>
              </div>

              
              <div class="col xl3 m3 input-field">
                <input id="importeEfectivo" name="importeEfectivo" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('importeEfectivo',$importeEfectivo) }}"
                  disabled
                  maxlength="8" step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="importeEfectivo">Importe a rendir</label>
                <small class="errorTxt6"></small>
              </div>

              <div class="col xl3 m3 input-field">
                <input id="rinde" name="rinde" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('rinde',$rindeEfectivo) }}"
                  maxlength="8" autocomplete='off' step="0.01"
                  required
                  autofocus
                  oninput="calcularDiferencia(this)"
                  data-error=".errorTxt6">
                <label for="rinde">Arqueo de caja</label>
                <small class="errorTxt6"></small>

                <div class="card-alert card green lighten-5">
                  <div class="card-content green-text">
                    <p>Importe en efectivo</p>
                  </div>
                  <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              </div>
              

              <div class="col xl3 m3 input-field">
                <input id="diferencia" name="diferencia" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('diferencia',$saldoEfectivo) }}"
                  disabled
                  maxlength="8" autocomplete='off' step="0.01"
                  data-error=".errorTxt6">
                <label for="diferencia">Diferencia</label>
                <small class="errorTxt6"></small>
              </div>
            </div>

            <!-- concepto o tipo de movimiento -->
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                  <select id="concepto" name="concepto" disabled>
                    <option value = '' selected>Ingresos por T.Crédito</option>
                  </select>
              </div>

              <div class="col xl3 m3 input-field">
                <input id="tarjetacredito" name="tarjetacredito" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('tarjetacredito',$tarjetaCredito) }}"
                  disabled
                  maxlength="8" step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="tarjetacredito">Importe a rendir</label>
                <small class="errorTxt6"></small>
              </div>

              <input id="tarjetacredito2" name="tarjetacredito2" type="number"
                  value="{{ old('tarjetacredito2',$tarjetaCredito) }}"
                  hidden>
            </div>

            <!-- Tarjeta de Debito -->
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                <select id="tipo" name="tipo" disabled>
                    <option value = 0 selected>Ingresos por T.Débito</option>
                </select>
              </div>

              <div class="col xl3 m3 input-field">
                <input id="tarjetadebito" name="tarjetadebito" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('tarjetadebito',$tarjetaDebito) }}"
                  disabled
                  maxlength="8" step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="tarjetadebito">Importe a rendir</label>
                <small class="errorTxt6"></small>
              </div>

              <input id="tarjetadebito2" name="tarjetadebito2" type="number"
                  value="{{ old('tarjetadebito2',$tarjetaDebito) }}"
                  hidden>
            </div>

            
            <!-- Depositos y transferencias bancarias -->
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                <select id="tipo" name="tipo" disabled>
                    <option value = 0 selected>Ingresos Bancarios</option>
                </select>
              </div>

              <div class="col xl3 m3 input-field">
                <input id="bancarios" name="bancarios" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('bancarios',$bancarios) }}"
                  disabled
                  maxlength="8" step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="bancarios">Importe a rendir</label>
                <small class="errorTxt6"></small>
              </div>

              <input id="bancarios2" name="bancarios2" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('bancarios2',$bancarios) }}"
                  hidden>
            </div>

            
            <!-- Depositos y transferencias bancarias -->
            <div class="row mb-1">
              <div class="col m3 s3 input-field">
                <select id="tipo" name="tipo" disabled>
                    <option value = 0 selected>Cheques a depositar</option>
                </select>
              </div>

              <div class="col xl3 m3 input-field">
                <input id="cheques" name="cheques" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('cheques',$cheques) }}"
                  disabled
                  maxlength="8" step="0.01"
                  required
                  data-error=".errorTxt6">
                <label for="cheques">Importe a rendir</label>
                <small class="errorTxt6"></small>
              </div>

              <input id="cheques2" name="cheques2" type="number"
                  value="{{ old('cheques2',$cheques) }}"
                  hidden>
            </div>


          </div>
        </div>
      
        <!-- Form -->
      </form>
    </div>
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

  function calcularDiferencia(e) {
    var rinde = e.value;
    var aRendir = document.getElementById("importeEfectivo").value;
    var diferencia = rinde - aRendir

    document.getElementById('diferencia').setAttribute("value", diferencia);
  }
</script>
@endsection