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
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   CAJA DIARIA
</div>

<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 14px">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   N° de Caja : {{ $desde }}    -    Fecha : {{ date('d/m/Y', strtotime($hasta)) }}    -    Estado : @if ($cerrada == null or $cerrada == 0) Caja Abierta @endif    @if ($cerrada == 1) Caja Cerrada @endif
</div>

<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 14px">
   &nbsp;
</div>

@php
  $saldo = 0;
  $saldoDebito = 0;
  $saldoCredito = 0;
  $saldoBancario = 0;
  $saldoCheques = 0;
@endphp

<div class="panel">
   <div class="panel-body">
      <div class="table-responsive table-bordered mb-lg">
        <table style="font-size: 13px;">
          <thead style="bord">
            <tr>
                <th style="width: 50px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  Número</th>
                <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Fecha</th>
                <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                  Cuenta</th>
                <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Concepto</th>
                <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                  Comentarios</th>
                <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Importe</th>
                <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Saldo</th>
             </tr>
          </thead>
          <tbody style="font-size: 12px;">
             <tr>
                <td>
                  {{ $cajaAbierta->id }}
                </td>
                <td>
                  &nbsp;{{ date('d/m/Y', strtotime($cajaAbierta->fecha)) }}
                </td>
                <td>
                  APERTURA / SALDO INICIAL
                </td>
                <td>
                </td>
                <td style="text-align: left">
                  {{ $cajaAbierta->comentarios }}
                </td>
                <td style="text-align: right">
                  {{ number_format($cajaAbierta->apertura,2) }}
                </td>

                @php
                  $saldo = $saldo + $cajaAbierta->apertura;
                @endphp


                <td style="text-align: right">
                  {{ number_format($saldo,2) }}
                </td>
              </tr>
            

              @foreach ($novedades as $novedad)
                <tr>
                    <td>
                      {{ $novedad->numero }}
                    </td>
                    <td>
                      &nbsp;{{ date('d/m/Y', strtotime($novedad->fecha)) }}
                    </td>
                    <td style="text-align: left">
                      @if ($novedad->cuenta == 0) Ingresos en efectivo @endif
                      @if ($novedad->cuenta == 1) Tarjeta Credito (Ingresos)) @endif
                      @if ($novedad->cuenta == 2) Tarjeta Debito (Ingresos) @endif
                      @if ($novedad->cuenta == 5) Ingresos en efectivo @endif
                    </td>
                    <td>
                      {{ $novedad->concepto }} - {{ substr($novedad->nomConcepto,0,20) }}
                    </td>
                    <td style="text-align: left">
                      {{ $novedad->comentarios }}
                    </td>
                    <td style="text-align: right">
                      {{ number_format($novedad->importe,2) }}
                    </td>

                    @php
                      if ($novedad->cuenta == 0)
                        $saldo = $saldo - $novedad->importe;

                      if ($novedad->cuenta == 5)
                        $saldo = $saldo + $novedad->importe;
                    @endphp


                    <td style="text-align: right">
                      {{ number_format($saldo,2) }}
                    </td>
                </tr>
              @endforeach

          </tbody>
        </table>

        <hr>

        <div class="col-lg-12 col-md-12 col-xs-12">
           &nbsp;
        </div>

        <!-------------------------------------------------------
        //                  DEBITOS
        //------------------------------------------------------->
        @if ($debitos->count() > 0)
          <h3>Ingresos por Tarjetas de Débito</h3>
          <table style="font-size: 13px;">
            <thead style="bord">
              <tr>
                  <th style="width: 50px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    Número</th>
                  <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Fecha</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Cuenta</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Concepto</th>
                  <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Comentarios</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Importe</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Saldo</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px;">
              @foreach ($debitos as $debito)
                <tr>
                    <td>
                      {{ $debito->numero }}
                    </td>
                    <td>
                      &nbsp;{{ date('d/m/Y', strtotime($debito->fecha)) }}
                    </td>
                    <td style="text-align: left">
                      @if ($debito->cuenta == 2) Tarjeta Debito (Ingresos) @endif
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
                      if ($debito->cuenta == 2)
                        $saldoDebito = $saldoDebito + $debito->importe;
                    @endphp


                    <td style="text-align: right">
                      {{ number_format($saldoDebito,2) }}
                    </td>
                </tr>
              @endforeach

            </tbody>
          </table>

          <hr>

          <div class="col-lg-12 col-md-12 col-xs-12">
            &nbsp;
          </div>
        @endif

        <!-------------------------------------------------------
        //                  CREDITOS
        //------------------------------------------------------->
        @if ($creditos->count() > 0)
          <h3>Ingresos por Tarjetas de crédito</h3>
          <table style="font-size: 13px;">
            <thead style="bord">
              <tr>
                  <th style="width: 50px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    Número</th>
                  <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Fecha</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Cuenta</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Concepto</th>
                  <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Comentarios</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Importe</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Saldo</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px;">
              @foreach ($creditos as $credito)
                <tr>
                    <td>
                      {{ $credito->numero }}
                    </td>
                    <td>
                      &nbsp;{{ date('d/m/Y', strtotime($credito->fecha)) }}
                    </td>
                    <td style="text-align: left">
                      @if ($credito->cuenta == 2) Tarjeta credito (Ingresos) @endif
                    </td>
                    <td>
                      {{ $credito->concepto }} - {{ substr($credito->nomConcepto,0,20) }}
                    </td>
                    <td style="text-align: left">
                      {{ $credito->comentarios }}
                    </td>
                    <td style="text-align: right">
                      {{ number_format($credito->importe,2) }}
                    </td>

                    @php
                      if ($credito->cuenta == 1)
                        $saldoCredito = $saldoCredito + $credito->importe;
                    @endphp


                    <td style="text-align: right">
                      {{ number_format($saldoCredito,2) }}
                    </td>
                </tr>
              @endforeach

            </tbody>
          </table>
          
          <hr>

          <div class="col-lg-12 col-md-12 col-xs-12">
            &nbsp;
          </div>
        @endif


        <!-------------------------------------------------------
        //                  BANCARIOS
        //------------------------------------------------------->
        @if ($bancos->count() > 0)
          <h3>Ingresos Bancarios</h3>
          <table style="font-size: 13px;">
            <thead style="bord">
              <tr>
                  <th style="width: 50px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    Número</th>
                  <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Fecha</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Cuenta</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Concepto</th>
                  <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Comentarios</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Importe</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Saldo</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px;">
              @foreach ($bancos as $banco)
                <tr>
                  <td>
                    {{ $banco->numero }}
                  </td>
                  <td>
                    &nbsp;{{ date('d/m/Y', strtotime($banco->fecha)) }}
                  </td>
                  <td style="text-align: left">
                    @if ($banco->cuenta == 3) Transf. Bancaria Galicia (Ingresos) @endif
                    @if ($banco->cuenta == 4) Transf. Bancaria Macro (Ingresos) @endif
                  </td>
                  <td>
                    {{ $banco->concepto }} - {{ substr($banco->nomConcepto,0,20) }}
                  </td>
                  <td style="text-align: left">
                    {{ $banco->comentarios }}
                  </td>
                  <td style="text-align: right">
                    {{ number_format($banco->importe,2) }}
                  </td>

                  @php
                    if ($banco->cuenta == 3 or $banco->cuenta == 4)
                      $saldoBancario = $saldoBancario + $banco->importe;
                  @endphp


                  <td style="text-align: right">
                    {{ number_format($saldoBancario,2) }}
                  </td>
                </tr>
              @endforeach

            </tbody>
          </table>
          
          <hr>

          <div class="col-lg-12 col-md-12 col-xs-12">
            &nbsp;
          </div>
        @endif
      
      
        
        <!-------------------------------------------------------
        //                  CHEQUES
        //------------------------------------------------------->
        @if ($cheques->count() > 0)
          <h3>Cheques</h3>
          <table style="font-size: 13px;">
            <thead style="bord">
              <tr>
                  <th style="width: 50px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    Número</th>
                  <th style="width: 60px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Fecha</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Cuenta</th>
                  <th style="width: 150px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Concepto</th>
                  <th style="width: 120px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;text-align: center">
                    Comentarios</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Importe</th>
                  <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                    &nbsp;Saldo</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px;">
              @foreach ($cheques as $cheque)
                <tr>
                  <td>
                    {{ $cheque->numero }}
                  </td>
                  <td>
                    &nbsp;{{ date('d/m/Y', strtotime($cheque->fecha)) }}
                  </td>
                  <td style="text-align: left">
                    @if ($cheque->cuenta == 6) Ingresos en Cheques @endif
                  </td>
                  <td>
                    {{ $cheque->concepto }} - {{ substr($cheque->nomConcepto,0,20) }}
                  </td>
                  <td style="text-align: left">
                    {{ $cheque->comentarios }}
                  </td>
                  <td style="text-align: right">
                    {{ number_format($cheque->importe,2) }}
                  </td>

                  @php
                    if ($cheque->cuenta == 6)
                      $saldoCheques = $saldoCheques + $cheque->importe;
                  @endphp


                  <td style="text-align: right">
                    {{ number_format($saldoCheques,2) }}
                  </td>
                </tr>
              @endforeach

            </tbody>
          </table>
          
          <hr>

          <div class="col-lg-12 col-md-12 col-xs-12">
            &nbsp;
          </div>
        @endif
        
      </div>

      <h5>A rendir contado efectivo : {{ number_format($saldo,2) }} &nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;  
        <u>Rindio : {{ number_format($cajaAbierta->cierre,2) }} </u>&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;
        Diferencia de caja : {{ number_format($saldo - $cajaAbierta->cierre,2) }}</h5>
      <h5>Total Tarjeta Débito &nbsp;&nbsp;: {{ number_format($saldoDebito,2) }}</h5>
      <h5>Total Tarjeta Crédito : {{ number_format($saldoCredito,2) }}</h5>
      <h5>Total ingresos bancarios : {{ number_format($saldoBancario,2) }}</h5>
      <h5>Total Cheques cartera : &nbsp;&nbsp;&nbsp;{{ number_format($saldoCheques,2) }}</h5>


   </div>
</div>
