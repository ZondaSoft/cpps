{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','CPPS - Caja diaria')

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

      <input hidden id="id_caja" name="id_caja" value="{{ $id_caja }}" type="text">
      <input hidden id="fecha" name="fecha" value="{{ $fecha }}" type="text">
      <input hidden id="cerrada" name="cerrada" value="{{ $cerrada }}" type="text">

      <!-- create agregar button-->
      @if ($cerrada == false)
      <div class="invoice-create-btn" style="margin-left: -5px">
        <a href="{{asset('orden-add')}}" class="btn waves-effect waves-light invoice-create border-round z-depth-4">
          <i class="material-icons">add</i>
          <span class="hide-on-small-only">Agregar</span>
        </a>
      </div>

      <!-- create cierre de caja button-->
      
      <div class="invoice-create-btn" style="margin-left: 15px">
        <a href="{{ asset('caja-cerrar') }}/{{ $id_caja }}" class="btn waves-effect waves-light deep-orange darken-1 border-round z-depth-4" >  {{-- onclick="showModalBorrar()" --}}
          <i class="material-icons">vpn_key</i>
          <span class="hide-on-small-only">Cerrar caja</span>
        </a>
      </div>
    @else
      <div class="invoice-create-btn">
        <a href="{{asset('orden-abrir')}}" class="btn waves-effect waves-light invoice-create border-round z-depth-4">
          <i class="material-icons">add</i>
          <span class="hide-on-small-only">Abrir nueva caja</span>
        </a>
      </div>
    @endif
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
            <span>Tipo</span>
          </th>
          <th>
            <span>Nro.</span>
          </th>
          <th style="width: 10%">Fecha</th>
          <th>Cuenta</th>
          <th>Concepto</th>
          <th>Importe</th>
          <th>Saldo</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <div hidden>
      {{ $saldo = 0 }}
      {{ $saldoDebito = 0 }}
      {{ $saldoCredito = 0 }}
      {{ $saldoBancario = 0 }}
      {{ $saldoCheques = 0 }}
      </div>

      <tbody>
        <tr>
          <td></td>
          <td></td>
          <td>
              {{-- <a href="{{asset('apertura') . '/' . $apertura->id }}">{{ $apertura->id }}</a> --}}
          </td>
          <td>{{ date('d/m/Y', strtotime($apertura->fecha)) }}</td>
          <td><span class="invoice-customer">APERTURA / SALDO INICIAL</span></td>
          <td>
              
          </td>
          <td><span class="invoice-amount">${{ number_format($apertura->apertura,2) }}</span></td>

          <div hidden>
            {{ $saldo = $saldo + $apertura->apertura }}
          </div>

          <td><span class="invoice-amount">${{ number_format($saldo,2) }}</span></td>
          <td>
            <div class="invoice-action">
              
            </div>
          </td>
        </tr>
        @foreach ($novedades as $novedad)
        <tr>
          <td>{{ $novedad->id }}</td>
          <td> @if ($novedad->tipo == 0) Fac @endif 
            @if ($novedad->tipo == 1) Rec @endif 
            @if ($novedad->tipo == 2) ND @endif
            @if ($novedad->tipo == 3) NC @endif
            @if ($novedad->tipo == 4) Ti @endif
            @if ($novedad->tipo == 5) Tr @endif
            @if ($novedad->tipo == 6) Ot @endif 
          </td>
          <td @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>
              <a @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif href="#">{{ $novedad->numero }}</a>
          </td>
          <td @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>{{ date('d/m/Y', strtotime($novedad->fecha)) }}</td>
          <td><span class="invoice-customer" 
            @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>
            @if ($novedad->cuenta == 0) Egresos en efectivo @endif
            @if ($novedad->cuenta == 1) Tarjeta Credito (Ingresos)) @endif
            @if ($novedad->cuenta == 2) Tarjeta Debito (Ingresos) @endif
            @if ($novedad->cuenta == 3) Transf. Bancaria Galicia (Ingresos) @endif
            @if ($novedad->cuenta == 4) Transf. Bancaria Macro (Ingresos) @endif
            @if ($novedad->cuenta == 5) Ingresos en efectivo @endif
            @if ($novedad->cuenta == 6) Ingresos en Cheques @endif
          </span></td>
          <td><span class="invoice-customer" @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>
            {{ substr($novedad->nomConcepto,0,20) }}
          </span></td>
          
          <td>
            <span class="invoice-amount" @if ($novedad->cuenta > 0 and $novedad->cuenta < 5) style="color: red" @endif>
              @if ($novedad->cuenta == 0)(@endif
                ${{ number_format($novedad->importe,2) }}
              @if ($novedad->cuenta == 0))@endif
            </span>
          </td>

          <div hidden>
            @if ($novedad->cuenta === 0)
              {{ $saldo = $saldo - $novedad->importe }}
            @endif
            @if ($novedad->cuenta === 5)
              {{ $saldo = $saldo + $novedad->importe }}
            @endif

            @if ($novedad->cuenta == 2)
              {{ $saldoDebito = $saldoDebito + $novedad->importe }}
            @endif
            @if ($novedad->cuenta == 1)
              {{ $saldoCredito = $saldoCredito + $novedad->importe }}
            @endif
            @if ($novedad->cuenta == 3 or $novedad->cuenta == 4)
              {{ $saldoBancario = $saldoBancario + $novedad->importe }}
            @endif
            @if ($novedad->cuenta == 6)
              {{ $saldoCheques = $saldoCheques + $novedad->importe }}
            @endif
  
          </div>

          <td>
            <span class="invoice-amount">
              @if ($novedad->cuenta == 0 or $novedad->cuenta == 5)
                ${{ number_format($saldo,2) }}
              @endif
            </span>
          </td>
          <td>
            <div class="invoice-action">
              @if ($cerrada == false)
                <a href="{{ asset('orden-add') . '/edit/' . $novedad->id }}" class="invoice-action-edit mr-4">
                  <i class="material-icons">edit</i>
                </a>
                <a href="{{ asset('orden') . '/delete/' . $novedad->id }}" class="invoice-action-edit">
                  <i class="material-icons">delete</i>
                </a>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
        
      </tbody>
    </table>

    <div class="s12 m12">
      <div class="form-row">
        
        <div class="col m2 s2 input-field">
          <input id="saldoEfectivo" name="saldoEfectivo" type="text" class="validate" 
            value="{{ old('saldoEfectivo',$saldo) }}"
            disabled data-error=".errorTxt1">
          <label for="saldo">Saldo contado actual</label>
          <small class="errorTxt1"></small>
        </div>

        <div class="col m2 s2 input-field">
          <input id="saldoDebito" name="saldoDebito" type="text" class="validate" 
            value="{{ old('saldoDebito',$saldoDebito) }}"
            disabled data-error=".errorTxt1">
          <label for="saldo">Ingresos por Débitos</label>
          <small class="errorTxt1"></small>
        </div>

        <div class="col m2 s2 input-field">
          <input id="saldoCredito" name="saldoCredito" type="text" class="validate" 
            value="{{ old('saldoCredito',$saldoCredito) }}"
            disabled data-error=".errorTxt1">
          <label for="saldo">Ingresos por Créditos</label>
          <small class="errorTxt1"></small>
        </div>

        <div class="col m2 s2 input-field">
          <input id="saldoBancario" name="saldoBancario" type="text" class="validate" 
            value="{{ old('saldoBancario',$saldoBancario) }}"
            disabled data-error=".errorTxt1">
          <label for="saldo">Bancarios</label>
          <small class="errorTxt1"></small>
        </div>

        <div class="col m2 s2 input-field">
          <input id="saldoCheques" name="saldoCheques" type="text" class="validate" 
            value="{{ old('saldoCheques',$saldoCheques) }}"
            disabled data-error=".errorTxt1">
          <label for="saldo">Cheques</label>
          <small class="errorTxt1"></small>
        </div>
      
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
</script>

<script src="{{asset('js/scripts/app-invoice.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
@endsection