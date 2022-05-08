<?php
    if(!isset($novedad)) {
        $novedad = null;
    }

    if(!isset($novedades)) {
        $novedades = null;
    }
?>

<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" id="bscss">
<!-- Modals must be declare at body level so the content overlaps the background-->
<!-- Modal Large-->
<div class="modal fade" id="myModalEdit" name="myModalEdit" tabindex="-2" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    @if ($legajo != null)
      <form method="post" action="{{ url('/home/delete/'.$legajo->id) }}" enctype="multipart/form-data" name="modal-edit" id="modal-edit">
   @else
      <form method="post" action="{{ url('/home/delete/') }}" enctype="multipart/form-data" name="modal-edit" id="modal-edit">
   @endif



    {{ csrf_field() }}

     <div class="modal-content">
          <div class="modal-header">
             <h4 class="modal-title" id="myModalLabelLarge">Baja de Legajo  #</h4>

             <div class="col-lg-3 mb-3" style="max-height: 3px;borderWidth: 0px">
                 @if ($novedades != null)
                 @if ($novedades->count() > 0 and $novedad != null)
                    <input class="form-control" type="text" value="{{ $novedad->id }}" id="nid"
                        name="nid" readonly autocomplete="off" hidden>
                 @else
                    <input class="form-control" type="text" value="" id="nid"
                        name="nid" readonly autocomplete="off" hidden>
                 @endif
                 @endif
             </div>

			 <div class="col-lg-6 mb-6">
                <button class="btn btn-info" type="submit" name="btnSiniestro" id="btnSiniestro" style="height: 35.533px" value='consultar' hidden>
                  <span class="btn-label"><i class="fa fa-info"></i>
                  </span>Ver historial del siniestro</button>
             </div>

             <button class="close" type="button" data-dismiss="modal" aria-label="Close" autofocus tabindex="-1">
                <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">

         @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
         @endif

            <div class="col-md-12">
              <div class="form-row">
                <div class="col-lg-4 mb-4">
                   <label class="col-form-label">Legajo * </label>
                       <div class="input-group " id="nro_legajo" data-provide=""
                           keyboardNavigation="false" title="Ingrese un Nro. legajo">

                           <input class="form-control" type="text" value="{{ $legajo->codigo }}" id="legajoEdit"
                              name="legajoEdit" disabled required autocomplete="off" maxlength="4" style="width: 190px">

                           <!-- onkeyup="saltar(event,'cod_nov')" -->

                       </div>
                </div>

                 <div class="col-lg-8 mb-8">
                    <label class="col-form-label">Apellidos y Nombres</label>
                    <input class="form-control" type="text" name="ApynomEdit" id="ApynomEdit"
                       disabled
                       value="{{ $legajo->detalle }} {{ $legajo->nombres }}" autocomplete='off'>
                 </div>

             </div>
          </div>

            <div class="col-md-12">
              <div class="form-row">
                 <div class="col-lg-4 mb-4">
                      <label class="col-form-label">Motivo de Baja *</label>
                      <div class="input-group " id="cod_novedad" data-provide="" keyboardNavigation="false" title="Ingrese el motivo de la baja">

                        <select class="form-control" id="mod_sijp" name="mod_sijp"> <!--  $edicion?'enabled':'disabled'   -->
                            <option value="1" @if ($legajo->mod_sijp == "1")  selected   @endif >Renuncia</option>
                            <option value="4" @if ($legajo->mod_sijp == "7")  selected   @endif >Despido con justa causa</option>
                            <option value="5" @if ($legajo->mod_sijp == "5")  selected   @endif >Despido sin justa causa</option>
             				<option value="0" @if ($legajo->mod_sijp == "0")  selected   @endif >Baja por fallecimiento</option>
                            <option value="2" @if ($legajo->mod_sijp == "2")  selected   @endif >Baja otras causales</option>
                        </select>

                      </div>
                 </div>

                </div>
            </div>

         <div class="col-lg-12 mb-12">
           <div class="form-row">
              <div class="col-lg-3 mb-3">
                  <label class="col-form-label">Fecha * </label>
                      <div class="input-group date" id="datetimepicker3" data-provide="datepicker" data-date-format="dd/mm/yyyy"
                          keyboardNavigation="true" title="Seleccione fecha" autoclose="true">
                           <input class="form-control" type="text" value="{{ old('fec_baja',$legajo->baja) }}" name="fec_baja" id="fec_baja"
                              required autocomplete="off">
                           <span class="input-group-append input-group-addon" disabled>
                            <span class="input-group-text fa fa-calendar"></span>
                          </span>
                      </div>
                </div>


                <div class="col-lg-1 mb-1">
                    <label class="col-form-label">&nbsp;</label>
                </div>


            </div>
         </div>


         <div class="col-lg-12 mb-12">
              <label class="col-form-label">Comentarios</label>
              <textarea cols="7" placeholder=".." class="form-control" enabled
                  name="baja_det" id="baja_det">{{ $legajo->baja_det }}</textarea>
         </div>

         <div class="errors">
         </div>


        </div>
        <div class="modal-footer">
           <div class="col-lg-9 mb-9">
              
           </div>

           <button class="btn btn-danger" type="button" data-dismiss="modal" name="btncancelar" id="btncancelar"> Cancelar </button>
           <button class="btn btn-success" type="submit" name="btngrabar" id="btngrabar" style="height: 35.533px" value='borrar'>
            <span class="btn-label"><i class="fa fa-trash"></i>
            </span>Dar de baja !</button>
           <!-- <input type="submit" value="Enviar información"> -->
        </div>
     </div>

   </form>

  </div>
</div>
