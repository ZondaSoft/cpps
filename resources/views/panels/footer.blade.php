<!-- BEGIN: Footer-->
<footer
  class="{{$configData['mainFooterClass']}} @if($configData['isFooterFixed']=== true){{'footer-fixed'}}@else {{'footer-static'}} @endif @if($configData['isFooterDark']=== true) {{'footer-dark'}} @elseif($configData['isFooterDark']=== false) {{'footer-light'}} @else {{$configData['mainFooterColor']}} @endif">
  <div class="footer-copyright">
    <div class="container">
      <span>&copy; 2022 <a href="http://zondasoftware.com.ar/"
          target="_blank"> -  Ver. 3.09b  -   ZondaSoftware</a> Todos los derechos reservados.
      </span>
      {{-- <span class="right hide-on-small-only">
        Desarrollado por <a href="http://zondasoftware.com.ar/">ZondaSoftware</a>
      </span> --}}
    </div>
  </div>
</footer>

<!-- END: Footer-->