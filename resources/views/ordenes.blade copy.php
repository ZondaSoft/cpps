{{-- layout extend --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Ordenes de Trabajo')

{{-- page styles --}}

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/dataTables.checkboxes.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- invoice list -->
<section class="invoice-list-wrapper section" style="margin-top: 15px;">
  <!-- create invoice button-->
  <!-- Options and filter dropdown button-->
  <div class="invoice-filter-action mr-3">
    <a href="javascript:void(0)" class="btn waves-effect waves-light invoice-export border-round z-depth-4">
      <i class="material-icons">picture_as_pdf</i>
      <span class="hide-on-small-only">Exportar a PDF</span>
    </a>
  </div>
  <!-- create invoice button-->
  <div class="invoice-create-btn">
    <a href="{{asset('orden-add')}}" class="btn waves-effect waves-light invoice-create border-round z-depth-4">
      <i class="material-icons">add</i>
      <span class="hide-on-small-only">Nueva orden</span>
    </a>
  </div>
  
  <div class="filter-btn">
    <!-- Dropdown Trigger -->
    <a class='dropdown-trigger btn waves-effect waves-light purple darken-1 border-round' href='#'
      data-target='btn-filter'>
      <span class="hide-on-small-only">Filtrar ordenes</span>
      <i class="material-icons">keyboard_arrow_down</i>
    </a>
    <!-- Dropdown Structure -->
    <ul id='btn-filter' class='dropdown-content'>
      <li><a href="#!">Todas</a></li>
      <li><a href="#!">Pendientes</a></li>
      <li><a href="#!">Solo finalizadas</a></li>
    </ul>
  </div>
  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <!-- data table responsive icons -->
          <th></th>
          <!-- data table checkbox -->
          <th></th>
          <th>
            <span>Orden #</span>
          </th>
          <th>Importe</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Vehiculo</th>
          <th>Estado</th>
          <th>Accion</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          @foreach ($apertura as $abreCaja)
            <td></td>
            <td></td>
            <td>
              <a href="{{asset('app-invoice-view') . '/' . $apertura->id }}">00{{ $apertura->numero }}</a>
            </td>
            <td><span class="invoice-amount">${{ $apertura->apertura }}</span></td>
            <td>{{ $apertura->fecha }}</td>
            <td><span class="invoice-customer">{{ $apertura->ClientDetail }}</span></td>
            <td>
              <span class="invoice-vehicle">{{ $apertura->dominio }}</span>
            </td>
            <td>
              <span class="chip lighten-5 red red-text">{{ $apertura->estado }}</span>
            </td>
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
          @endforeach
        </tr>
        <tr>
          @foreach ($novedades as $novedad)
            <td></td>
            <td></td>
            <td>
              <a href="{{asset('app-invoice-view') . '/' . $novedad->id }}">00{{ $novedad->numero }}</a>
            </td>
            <td><span class="invoice-amount">${{ $novedad->importe }}</span></td>
            <td>{{ $novedad->fecha }}</td>
            <td><span class="invoice-customer">{{ $novedad->ClientDetail }}</span></td>
            <td>
              <span class="invoice-vehicle">{{ $novedad->dominio }}</span>
            </td>
            <td>
              <span class="chip lighten-5 red red-text">{{ $novedad->estado }}</span>
            </td>
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
          @endforeach
        </tr>
      </tbody>
    </table>
  </div>
</section>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/js/datatables.checkboxes.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/app-invoice.js')}}"></script>
@endsection