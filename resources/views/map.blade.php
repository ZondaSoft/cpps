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
<div class="modal fade" id="myModelMap" name="myModelMap" tabindex="-2" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    @if ($legajo != null)
      <form method="post" action="{{ url('/home/map/'.$legajo->id) }}" enctype="multipart/form-data" name="modal-map" id="modal-map">
    @else
      <form method="post" action="{{ url('/home/map/') }}" enctype="multipart/form-data" name="modal-map" id="modal-map">
    @endif

    {{ csrf_field() }}

     <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="lblUbicacion">Ubicación geográfica  #</h4>

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

            <div class="col-xl-12">
              <div class="col-md-10">
                <label class="col-form-label">Latitud</label>
                <input class="form-control" type="text" name="latitud" id="latitud"
                {{ $edicion?'enabled':'disabled' }}
                {{ $agregar?'autofocus=""':'autofocus=""' }}
                value="{{ $legajo?$legajo->latitud:'' }}" autocomplete='off'>
              </div>
              <div class="col-md-10">
                <label class="col-form-label">Longitud</label>
                <input class="form-control" type="text" name="longitud" id="longitud"
                {{ $edicion?'enabled':'disabled' }}
                {{ $agregar?'autofocus=""':'autofocus=""' }}
                value="{{ $legajo?$legajo->longitud:'' }}" autocomplete='off'>
              </div>
              <div class="col-md-10">
                &nbsp;
              </div>

              <div class="col-md-12" id="map_canvas"  style="position: relative;overflow: hidden;height: 345px;">
                <!--
                  <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d68915.12058438336!2d-65.48052900203983!3d-24.79871092416007!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x941bc3a35151b7f9%3A0xa5cd992cd587f206!2sSalta!5e0!3m2!1ses-419!2sar!4v1602288526367!5m2!1ses-419!2sar" width="600" height="340" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                -->
              </div>

              

              <div class="row">
                  <div class="col-xl-8">
                    <label class="col-form-label" hidden>Ciudad</label>
                    <input class="form-control" type="text" name="txtCiudad" id="txtCiudad" autocomplete='nope'
                      {{ $edicion?'enabled':'disabled' }}
                      {{ $agregar?'autofocus=""':'autofocus=""' }}
                      value="{{ $legajo?$legajo->ciudad:'' }}" style="max-width: 400px" hidden>

                    <label class="col-form-label" hidden>Estado</label>
                    <input class="form-control" type="text" name="txtEstado" id="txtEstado" autocomplete='nope'
                    {{ $edicion?'enabled':'disabled' }}
                    {{ $agregar?'autofocus=""':'autofocus=""' }}
                    value="{{ $legajo?$legajo->estado:'' }}" autocomplete='off' style="max-width: 400px" hidden>

                    <label class="col-form-label">Busqueda en Google Maps</label>
                    <input class="form-control" type="text" name="txtDireccion" id="txtDireccion" autocomplete='nope'
                    {{ $edicion?'enabled':'disabled' }}
                    {{ $agregar?'autofocus=""':'autofocus=""' }}
                    value="{{ $legajo?$legajo->direccion:'' }}" autocomplete='off'>
                </div>

                <div class="col-xl-3" style="padding-top: 35px">
                  <button class="btn btn-labeled btn-info" type="button" id="btnSearch">
                    <span class="btn-label"><i class="fa fa-search"></i>
                    </span>Buscar...</button>
                </div>
              </div>
            </div>
            
        </div>
        <div class="modal-footer">
           <div class="col-lg-9 mb-9">
              
           </div>

           <button class="btn bg-purple-light" type="button" data-dismiss="modal" name="btnCerrar" id="btnCerrar"> Cerrar </button>
           
           <!-- <input type="submit" value="Enviar información"> -->
        </div>
     </div>

   </form>

  </div>
</div>
