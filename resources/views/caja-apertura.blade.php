{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page Title --}}
@section('title','Abrir caja')

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
      @if($agregar == true)
      <form method="post" action="{{ asset( url('/caja-abrir') ) }}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{ asset( url('/cajas/editapertura/'.$legajo->id) ) }}" enctype="multipart/form-data">
      @endif
        
      {{ csrf_field() }}

        <div class="card">
          <div class="card-content m12 s12">

            <div class="row mb-12">
              <div class="col m7 s7 save">
                <h5 style="margin-top: 7px;margin-bottom: 14px;">Apertura de caja</h5>
              </div>

              <div class="col m2 s2 save">
                <button class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
                  <i class="material-icons mr-4">check</i>
                  <span class="btn-label"><i class="fa fa-check"></i>
                  </span>Grabar
                </button>
              </div>

              <div class="col m2 s2 save">
                <a class="btn btn-light-indigo btn-block waves-effect waves-light">
                  <span class="responsive-text">Cancelar</span>
                </a>
              </div>
            </div>

            <!-- header section -->
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

            <div class="col xl3 m3 input-field">
              <input id="id" name="id" type="text" placeholder="00000000" class="validate" 
                value="{{ old('id',$legajo->id) }}"
                disabled
                maxlength="8" autocomplete='off'
                required
                data-error=".errorTxt1">
              <label for="numero">Nro. de Caja</label>
              <small class="errorTxt1"></small>
            </div>

            <div class="col xl1 m1 input-field">
            </div>
            
            <div class="col xl2 m3 input-field">
                <input id="fecha" name="fecha" type="date" placeholder="dd/mm/aaaa" class="" 
                  value="{{ old('fecha',$legajo->fecha) }}"
                  maxlength="8" autocomplete='off'
                  required
                  autofocus
                  data-error=".errorTxt2">
                <label for="fecha">Fecha</label>
                <small class="errorTxt2"></small>
            </div>
            
            <div class="col xl1 m1 input-field">
            </div>

            <!-- Importe -->
            <div class="row mb-1">
              <div class="col xl3 m3 input-field">
                <input id="apertura" name="apertura" type="number" placeholder="$ 0000.00" class="validate" 
                  value="{{ old('apertura',$legajo->apertura) }}"
                  maxlength="11" autocomplete='off'
                  readonly
                  data-error=".errorTxt3">
                <label for="apertura">Saldo de inicio</label>
                <small class="errorTxt3"></small>
              </div>
            </div>

            <!-- Observaciones -->
            <div class="row mb-1" style="margin-left: 2px;">
              <div class="col xl8 m8 input-field">
                <input type="text" id="comentarios" name="comentarios" placeholder="Comentarios" autocomplete='off'>
                <label for="comentarios">Comentarios</label>
              </div>
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