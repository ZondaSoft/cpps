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
    <a href="javascript:void(0)" class="btn waves-effect waves-light invoice-export border-round z-depth-4">
      <i class="material-icons">picture_as_pdf</i>
      <span class="hide-on-small-only">Exportar</span>
    </a>
  </div>
  <!-- create agregar button-->
  <div class="invoice-create-btn">
    <a href="{{asset('orden-add')}}" class="btn waves-effect waves-light invoice-create border-round z-depth-4">
      <i class="material-icons">add</i>
      <span class="hide-on-small-only">Agregar comprobante</span>
    </a>
  </div>

  <!----------------------------------------------->
  <!--          MODAL DE CONFIRMACION            -->
  <!----------------------------------------------->
  {{-- <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Cerrar Caja ?</h4>
      <p>Está a punto de realizar el cierre de caja, recuerde que este paso es irreversible ....</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Volver</a>
      <a href="{{ asset('/caja-cerrar') }}" class="btn waves-effect waves-light deep-orange darken-1 z-depth-4 modal-trigger">Cerrar caja !</a>
    </div>
  </div> --}}


  <!-- create cierre de caja button-->
  <div class="invoice-create-btn" style="margin-left: 15px">
    <a href="{{ asset('caja-cerrar') }}/{{ $id_caja }}" class="btn waves-effect waves-light deep-orange darken-1 border-round z-depth-4 modal-trigger" >  {{-- onclick="showModalBorrar()" --}}
      <i class="material-icons">vpn_key</i>
      <span class="hide-on-small-only">Cerrar caja</span>
    </a>
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
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <!-- data table responsive icons -->
          <th></th>
          <!-- data table checkbox -->
          <th></th>
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
      </div>

      <tbody>
        <tr>
          <td></td>
          <td></td>
          <td>
              <a href="{{asset('apertura') . '/' . $apertura->id }}">{{ $apertura->id }}</a>
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
              <a href="{{asset('app-invoice-view')}}" class="invoice-action-view mr-4">
                <i class="material-icons">remove_red_eye</i>
              </a>
              <a href="{{asset('app-invoice-edit')}}" class="invoice-action-edit">
                <i class="material-icons">edit</i>
              </a>
            </div>
          </td>
        </tr>

        @foreach ($novedades as $novedad)
        <tr>
          <td></td>
          <td></td>
          <td>
              <a href="{{asset('apertura') . '/' . $novedad->id }}">{{ $novedad->numero }}</a>
          </td>
          <td>{{ date('d/m/Y', strtotime($novedad->fecha)) }}</td>
          <td><span class="invoice-customer">
            @if ($novedad->cuenta == 0) Contado efectivo (Gastos) @endif
            @if ($novedad->cuenta == 1) Tarjeta Credito (Ingresos)) @endif
            @if ($novedad->cuenta == 2) Tarjeta Debito (Ingresos) @endif
            @if ($novedad->cuenta == 3) Transf. Bancaria Galicia (Ingresos) @endif
            @if ($novedad->cuenta == 4) Transf. Bancaria Macro (Ingresos) @endif
            @if ($novedad->cuenta == 5) Contado efectivo (Ingresos) @endif
          </span></td>
          <td><span class="invoice-customer">{{ $novedad->concepto }} - {{ substr($novedad->nomConcepto,0,20) }}</span></td>
          <td><span class="invoice-amount">@if ($novedad->cuenta == 0)-@endif ${{ number_format($novedad->importe,2) }}</span></td>

          <div hidden>
            @if ($novedad->cuenta == 0)
              {{ $saldo = $saldo - $novedad->importe }}
            @else
              {{ $saldo = $saldo + $novedad->importe }}
            @endif
          </div>

          <td><span class="invoice-amount">${{ number_format($saldo,2) }}</span></td>
          <td>
            <div class="invoice-action">
              <a href="{{asset('app-invoice-view')}}" class="invoice-action-view mr-4">
                <i class="material-icons">remove_red_eye</i>
              </a>
              <a href="{{asset('app-invoice-edit')}}" class="invoice-action-edit">
                <i class="material-icons">edit</i>
              </a>
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
<script src="{{asset('js/scripts/app-invoice.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
@endsection