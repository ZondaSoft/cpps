<div class="row mb-lg-12">
  <div class="col-lg-6 col-md-6 col-xs-6" style="border-right: 1px;solid #C00;">
    <img alt="" src="{{ asset('img/logo-cpps-black.png') }}" height="52" width="240" border-right="1"/>
  </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 16px;font-weight: bold">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 16px;font-weight: bold">
ORDENES EMITIDAS
</div>

<br>

<div class="col-lg-12 col-md-12 col-xs-12 text-center" style="font-size: 14px">
 Obra Social: {{ $obra }} - {{ $nombreObra }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Período: {{ $periodo }}
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
      //                  Title and description
      //------------------------------------------------------->
      
        <table style="font-size: 13px;">
          <thead style="bord">
            <tr>
                <th style="width: 45px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  Número</th>
                <th style="width: 38px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Fecha</th>
                <th style="width: 40px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Afiliado</th>
                <th style="width: 35px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Plan</th>
                <th style="width: 130px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Práctica</th>
                <th style="width: 40px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px;">
                  Cantidad</th>
                <th style="width: 70px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Importe</th>
                {{-- <th style="width: 8px;border-bottom: 1px solid;border-top: 1px solid;padding-top: 10px;padding-bottom: 10px">
                  &nbsp;Comentarios</th> --}}
            </tr>
          </thead>

          @php
            $matricula = 0;
            $cantidad = 0;
            $totalCantidad = 0;
            $subtotal = 0;
            $totalGral = 0;
          @endphp

          <tbody style="font-size: 12px;">
            @foreach ($novedades as $orden)
              @if ($matricula != $orden->mat_prov_cole)
                @if ($matricula != 0)
                  <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                      ---------------
                    </td>
                    <td style="text-align: right;font-weight: bolder;">
                      ---------------
                    </td>
                  </tr>
                  <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td style="text-align: right;font-weight: bolder;">
                      {{ number_format($cantidad,2) }}
                    </td>
                    <td style="text-align: right;font-weight: bolder;">
                      {{ number_format($subtotal,2) }}
                    </td>
                  </tr>

                  @php
                    $cantidad = 0;
                    $subtotal = 0;
                  @endphp
                @endif

                <tr>
                  <td style="font-weight: bolder;">
                    Matrícula :
                  </td>
                  <td style="font-weight: bolder;">
                    {{ $orden->mat_prov_cole }}
                  </td>
                  <td style="font-weight: bolder;">
                    {{ substr($orden->nom_ape,0,18) }}
                  </td>
                </tr>
                
                {{-- <tr>
                  <td>
                    <br>
                  </td>  
                </tr> --}}

                @php
                  $matricula = $orden->mat_prov_cole;
                @endphp
              @endif  

              <tr>
                <td>
                  {{ $orden->ordennro }}
                </td>
                <td>
                  &nbsp;{{ date('d/m/Y', strtotime($orden->fecha)) }}
                </td>
                <td>
                  {{ substr($orden->nom_afiliado,0,20) }}
                </td>
                <td style="text-align: right">
                  {{ $orden->plan }}
                </td>
                <td>
                  {{ substr($orden->nom_prest,0,23) }}
                </td>
                <td style="text-align: right">
                  {{ $orden->cantidad }}
                </td>
                <td style="text-align: right">
                  {{ number_format($orden->importe,2) }}
                </td>
                

                @php
                  $cantidad = $cantidad + $orden->cantidad;
                  $totalCantidad = $totalCantidad + $orden->cantidad;
                  $subtotal = $subtotal + $orden->importe;
                  $totalGral = $totalGral + $orden->importe;
                @endphp
              </tr>
            @endforeach 

            @if ($matricula != 0)
              <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                  ---------------
                </td>
                <td style="text-align: right;font-weight: bolder;">
                  ---------------
                </td>
              </tr>
              <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td style="text-align: right;font-weight: bolder;">
                  {{ number_format($cantidad,2) }}
                </td>
                <td style="text-align: right;font-weight: bolder;">
                  {{ number_format($subtotal,2) }}
                </td>
              </tr>
            @endif

          </tbody>
        </table>

        <hr>

        <h4>Total Ordenes : {{ number_format($totalCantidad,2) }} </h4>
        <h4>Total General : {{ number_format($totalGral,2) }} </h4>

        <div class="col-lg-12 col-md-12 col-xs-12">
          &nbsp;
        </div>
      
    </div>

 </div>
</div>

<style>
  .row {
    margin-right: -15px;
    margin-left: -15px;
  }
  .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
  }
  
  .col-lg-12 {
      width: 100%;
  }
  
  .text-center {
    text-align: center;
  }
  
  body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 12px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
  }
</style>