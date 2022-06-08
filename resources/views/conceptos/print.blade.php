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
@section('title','Imprimir conceptos')

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
      <form method="post" action="{{ asset( url('/conceptos/print') ) }}" enctype="multipart/form-data">
      

        {{ csrf_field() }}

        <div class="row">
        <div class="col s12 display-flex content-end mt-2">
          <div class="m6 s6 display-flex content-end">
            <h5 style="margin-top: 7px;margin-bottom: 14px;onclick="excel(this)"">Imprimir Conceptos</h5>
          </div>
          <div class="col m6 s6">
            <button class="waves-effect waves-light btn mb-1 mr-1">Imprimir</button>
            <button class="waves-effect waves-light red green btn mb-1 mr-1" style="font-color: withe" onclick="excel(this)">Excel</button>
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
              <div class="s10 m10">
                <!-- Cuenta -->
                <div class="form-row">
                    <div class="col m6 s6 input-field">
                      <select id="cuenta" name="cuenta" onchange="changeVehiculos()">
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
                  
                  <div class="col m12 s12 input-field">
                  </div>

                  <!-- concepto o tipo de movimiento -->
                  <div class="col m6 s6 input-field">
                      <select id="concepto" name="concepto">
                          @foreach ($conceptos as $concepto)
                              <option value = "{{ $concepto->codigo  }}" @if ( old('concepto',$legajo->concepto)  == $concepto->codigo)  selected   @endif  >{{ $concepto->codigo  }} - {{ $concepto->detalle }}</option>
                          @endforeach
                      </select>
                      <label>Desde Concepto</label>
                  </div>


                  
                  <!-- concepto o tipo de movimiento -->
                  <div class="col m6 s6 input-field">
                    <select id="concepto2" name="concepto2">
                        @foreach ($conceptos as $concepto)
                            <option value = "{{ $concepto->codigo  }}" @if ( old('concepto',$legajo->concepto)  == $concepto->codigo)  selected   @endif  >{{ $concepto->codigo  }} - {{ $concepto->detalle }}</option>
                        @endforeach
                    </select>
                    <label>Hasta Concepto</label>
                  </div>


                  <div class="col xl2 m3 input-field">
                    <input id="ddesde" name="ddesde" type="date" placeholder="dd/mm/aaaa" class=""
                      value="{{ old('ddesde',$ddesde) }}"
                      maxlength="10" autocomplete='off'
                      required
                      data-error=".errorTxt4"
                      >
                    <label for="ddesde">Desde fecha</label>
                    <small class="errorTxt4"></small>
                  </div>

                  <div class="col xl2 m3 input-field">
                    <input id="dhasta" name="dhasta" type="date" placeholder="dd/mm/aaaa" class="" 
                      value="{{ old('dhasta',$dhasta) }}"
                      maxlength="10" autocomplete='off'
                      required
                      data-error=".errorTxt5">
                    <label for="dhasta">Hasta fecha</label>
                    <small class="errorTxt5"></small>
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

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
{{-- <script src="{{asset('js/scripts/page-users.js')}}"></script> --}}
<script src="{{asset('js/scripts/app-invoice.js')}}"></script>

<script>
  
  $(document).ready(function(){
    $('.datepicker').datepicker();
  });

  function dateChange1(element) {
    alert('Click!!')

    $(".datepicker").datepicker({
      autoClose: true,
      defaultDate: new Date(currYear,1,31),
      // setDefaultDate: new Date(2000,01,31),
      maxDate: new Date(currYear,12,31),
      yearRange: [1928, currYear],
      format: "dd/mm/yyyy"    
    });
  }

  function pdfexport(e) {
    //var id_caja = document.getElementById('id_caja').value

    document.getElementById('formMain').action="{{ url('/home/print') }}";
    
  }

  // Salida del informe a Excel
  function excel(e) {

    document.getElementById('formMain').action="{{ url('/home/excel/') }}";

  }
</script>
@endsection