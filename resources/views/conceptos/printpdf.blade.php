<div class="row mb-lg-12">
    <div class="col-lg-6 col-md-6 col-xs-6" style="border-right: 1px;solid #C00;">
      <img alt="" src="img/logo-cpps.jpg" height="62" width="240" border-right="1"/>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 16px;font-weight: bold">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 16px;font-weight: bold">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   INFORMES DE CONCEPTOS
</div>

<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 14px">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   Desde : {{ date('d/m/Y', strtotime($desde)) }}    -    Hasta : {{ date('d/m/Y', strtotime($hasta)) }}
</div>

<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 14px">
   &nbsp;
</div>

@php
  $saldo = 0;
@endphp

<div class="panel">
   <div class="panel-body">
      <div class="table-responsive table-bordered mb-lg">
        <!-------------------------------------------------------
        //                  DEBITOS
        //------------------------------------------------------->
        @if ($novedades->count() > 0)
          <table style="font-size: 13px;">
            <thead style="bord">
              <tr>
                  <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    Número</th>
                  <th style="width: 40px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Fecha</th>
                  <th style="width: 100px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Concepto</th>
                  <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Comentarios</th>
                  <th style="width: 40px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Importe</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px;">
              @foreach ($novedades as $debito)
                <tr>
                    <td>
                      {{ $debito->numero }}
                    </td>
                    <td>
                      &nbsp;{{ date('d/m/Y', strtotime($debito->fecha)) }}
                    </td>
                    <td>
                      {{ $debito->concepto }} - {{ substr($debito->nomConcepto,0,20) }}
                    </td>
                    <td style="text-align: left">
                      {{ $debito->comentarios }}
                    </td>
                    <td style="text-align: right">
                      {{ number_format($debito->importe,2) }}
                    </td>


                    @php
                      $saldo = $saldo + $debito->importe;
                    @endphp
                </tr>
              @endforeach

            </tbody>
          </table>

          <hr>

          <h4>Total : {{ number_format($saldo,2) }} </h4>

          <div class="col-lg-12 col-md-12 col-xs-12">
            &nbsp;
          </div>
        @endif
        
      </div>

   </div>
</div>
