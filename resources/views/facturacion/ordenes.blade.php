{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','CPPS - Ver ordenes')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/dataTables.checkboxes.css')}}"> --}}
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- invoice list -->
<section class="invoice-list-wrapper section" style="margin-top: 0px;">
  <!-- create invoice button-->
  <!-- Options and filter dropdown button-->
  <div class="invoice-filter-action mr-3">
    <form method="post" action="{{ url('/cajas/print') }}/{{ $id_caja }}" enctype="multipart/form-data" id="formMain" name="formMain">
      {{ csrf_field() }}
      
      <div class="col s12">
        <ul id="dropdown2" class="dropdown-content">
          <li><button class="btn-flat mb-1 waves-effect waves-light mr-1" onclick="pdfexport(this)">Imprimir<span class="badge">1</span></button></li>
          <li><button class="btn-flat mb-1 waves-effect waves-light mr-1" onclick="excel(this)">Exportar a excel<span class="badge">1</span></button></li>
          {{-- <li><a href="#!">Exportar a excel<span class="badge">1</span></a></li> --}}
        </ul>
        <a class="btn dropdown-trigger waves-effect waves-light invoice-export border-round z-depth-4" href="#!" data-target="dropdown2">Imprimir<i
            class="material-icons right">arrow_drop_down</i></a>
      </div>

      {{-- <button class="btn waves-effect waves-light invoice-export border-round z-depth-4">
        <i class="material-icons">picture_as_pdf</i>
        <span class="hide-on-small-only"><i class="fa fa-print"></i>
        </span>Imprimir
      </button> --}}

      {{-- <button class="btn waves-effect waves-light invoice-export border-round z-depth-4">
        <i class="material-icons">picture_as_pdf</i>
        <span class="hide-on-small-only"><i class="fa fa-print"></i>
        </span>Excel
      </button> --}}

      <!-- create agregar button-->
      
      <div class="invoice-create-btn" style="margin-left: -5px">
        <a href="{{asset('facturacion')}}/{{ $id_caja }}" class="btn waves-effect waves-light invoice-create border-round z-depth-4">
          <i class="material-icons">add</i>
          <span class="hide-on-small-only">Agregar</span>
        </a>
      </div>

      <!-- create cierre de caja button-->
      
      {{-- <div class="invoice-create-btn" style="margin-left: 15px">
        <a href="{{ asset('caja-cerrar') }}/{{ $id_caja }}" class="btn waves-effect waves-light deep-orange darken-1 border-round z-depth-4" >
          <i class="material-icons">vpn_key</i>
          <span class="hide-on-small-only">Cerrar caja</span>
        </a>
      </div> --}}

      {{-- onclick="showModalBorrar()" --}}
    
    </form>
  </div>
  
  

  <!-- <div class="filter-btn"> -->
    <!-- Dropdown Trigger -->
    {{-- <a class='dropdown-trigger btn waves-effect waves-light purple darken-1 border-round' href='#'
      data-target='btn-filter'>
      <span class="hide-on-small-only">Filter Invoice</span>
      <i class="material-icons">keyboard_arrow_down</i>
    </a> --}}
    <!-- Dropdown Structure -->
    {{-- <ul id='btn-filter' class='dropdown-content'>
      <li><a href="#!">Paid</a></li>
      <li><a href="#!">Unpaid</a></li>
      <li><a href="#!">Partial Payment</a></li>
    </ul> --}}
  <!-- </div> -->
  <div class="responsive-table">
    <table id="mainTable" class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <!-- data table responsive icons -->
          <th></th>
          <!-- data table checkbox -->
          <th class="sorting_asc" tabindex="0">
            <span>Matrícula</span>
          </th>
          <th>
            <span>Profesional</span>
          </th>
          <th style="width: 10%"># Orden</th>
          <th>Paciente</th>
          <th>Fecha</th>
          <th>Nomenclador</th>
          <th>Importe</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($facturas as $novedad)
        <tr>
          <td></td>
          <td> {{ $novedad->mat_prov_cole }}</td>
          <td> {{ $novedad->NomProf }} </td>
          <td> {{ $novedad->ordennro }} </td>
          <td> {{ $novedad->nom_afiliado }} </td>
          <td> {{ $novedad->fecha }} </td>
          <td> {{ $novedad->cod_nomen }} </td>
          
          <td>
            <span class="invoice-amount" @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>
              $ {{ number_format($novedad->importe,2) }}
            </span>
          </td>

          <td>
            <div class="invoice-action">
              <a href="{{ asset('facturacion') . '/edit/' . $novedad->id }}" class="invoice-action-edit mr-4" title="Modificar factura">
                <i class="material-icons">edit</i>
              </a>
              <a href="{{ asset('orden') . '/delete/' . $novedad->id }}" class="invoice-action-edit" title="Anular factura">
                <i class="material-icons">delete</i>
              </a>
            </div>
          </td>
        </tr>
        @endforeach
        
      </tbody>
    </table>

    <!------------ MODAL PARA SUBIR A LA WEB ----------->
    <div id="modal3" class="modal bottom-sheet">
      <div class="modal-content">
        <div class="row">
          <div class="col s12">
            <div class="form-row">
              <div class="col s1" style="width: 70px;">
                <a class="btn-floating mb-1 btn-flat waves-effect waves-light red accent-2 white-text" href="#"><i class="material-icons">cloud_upload</i></a>
              </div>
              <div class="col m9 s9">
                <h4>Subir facturación a la intranet de psicologossalta.com.ar ?</h4>
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
                <span class="title">Confirma la operación ?</span>

                <label for="id_factura"># interno : </label>
                <input id="id_factura" name="id_factura" type="number" autocomplete="off" maxlength="10" value="" style="width: 100px;margin-bottom: 0px;" disabled>
              </div>
            </div>
            
            {{-- <img src="{{asset('images/avatar/avatar-7.png')}}" alt="" class="circle"> --}}

            <div class="modal-footer">
              <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Cancelar</a>
              <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="upLoad()">Subir</a>
            </div>
          </div>
        </div>
        
      </div>
    </div>

    <div class="s12 m12">
      <div class="form-row">
        
        
      
      </div>
    </div>
  </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
{{-- <script src="{{asset('vendors/data-tables/js/datatables.checkboxes.min.js')}}"></script> --}}
@endsection

{{-- page scripts --}}
@section('page-script')

<script>
  //$('#modal1').modal('open');
  // Salida del informe a PDF
  function pdfexport(e) {
    var id_caja = document.getElementById('id_caja').value

    document.getElementById('formMain').action="{{ url('/cajas/print/') }}";
    
  }

  // Salida del informe a Excel
  function excel(e) {

    document.getElementById('formMain').action="{{ url('/cajas/excel/') }}";

  }

  function prepararFactura(id) {
    document.getElementById("id_factura").value = id;
  }


  function upLoad() {
    var id = document.getElementById("id_factura").value
    
    $.ajax({
        url: "/facturacion/web/" + id,
        data: "",
        dataType: "json",
        async: false,
        method: "GET",
        success: function(result)
        {
          if (result != null) {
            
            alert(result);

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
</script>

<script src="{{asset('js/scripts/app-invoice.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
@endsection