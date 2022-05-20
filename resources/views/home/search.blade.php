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

<!-- Page content-->
<div class="row">
   <div class="col s12 m12 l12">

      <div id="bordered-table" class="card card card-default scrollspy">
         <div class="card-content">
            
            <h4 class="card-title">Buscar cajas</h4>

            <form method="get" action="{{ asset( url('home/search') ) }}" enctype="multipart/form-data" class="form-group mb-2">

               <!-- <input class="form-control mb-2" type="text" placeholder="Texto a buscar..." autofocus=""> -->
               <input id="name" name="name" type="text" placeholder="Texto a buscar..." class="form-control mb-1" 
                        value="{{ old('name', $name) }}" autocomplete='off' autofocus data-error=".errorTxt2">
               <button class="btn btn-secondary" type="submit">Buscar</button>
               
               <!-- Cierre del form original (</ form>) -->
            </form>
            
            <div class="row">
               <div class="col s12">
               </div>
               
               <!-- START card-->
               <div class="card card-default">

                  <div class="card-header">
                     <!-- START table-responsive-->
                     <div class="col s12">
                        <table class="bordered">
                           <thead>
                              <tr>
                                 <th data-field="id">Nro.</th>
                                 <th data-field="name">Fecha</th>
                                 <th data-field="name">Cerrada ?</th>
                                 <th data-field="price"></th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($legajos as $legajo)
                                 <tr style="height: 10px;">
                                    <td style="height: 10px;">
                                       {{ $legajo->id }}
                                    </td style="height: 10px;">

                                    <td style="height: 10px;">
                                       {{ $legajo->fecha }}
                                    </td>
                                    <td style="height: 10px;">
                                       @if ($legajo->cerrada == 1)
                                          Si
                                       @endif
                                    </td>
                                    <td style="height: 10px;">
                                       <a class="mb-0 btn waves-effect waves-light cyan" href="\home\{{ $legajo->id }}">Seleccionar</a>
                                    </td>
                                 </tr>
                              @endforeach
                           </tbody>
                        </table>


                     </div>
                     <!-- END table-responsive-->
                  
                  </div>
               <!-- END card-->
               </div>

            </div>

         </div>

      </div>

   </div>
</div>

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