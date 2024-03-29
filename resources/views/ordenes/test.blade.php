{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Modals')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css?v2')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- main page content --}}
@section('content')
<div class="section">
  <div class="card">
    <div class="card-content">
      <p class="caption mb-0">Use a modal for dialog boxes, confirmation messages, or other content that can be called
        up. In order for the modal to work you have to add the Modal ID to the link of the trigger.</p>
    </div>
  </div>
  <!-- Modal Example -->
  <div class="row">
    <div class="col s12">
      <div id="fixed-example" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
                <h4 class="card-title">Modal Example</h4>
              </div>
              <div class="col s12 m6 l2">
                <ul class="tabs">
                  <li class="tab col s6 p-0"><a class="active p-0" href="#view-example">View</a></li>
                  <li class="tab col s6 p-0"><a class="p-0" href="#html-fixed-example">Html</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div id="view-example">
            <div class="row">
              <div class="col s12">
                <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#modal1">Modal</a>
                <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#modal2">Modal With Fixed
                  Footer</a>
                <a class="waves-effect waves-light btn modal-trigger mb-2" href="#modal3">Modal Bottom Sheet Style</a>
                <div id="modal1" class="modal">
                  <div class="modal-content">
                    <h4>Modal Header</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                      labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Disagree</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
                  </div>
                </div>
                <div id="modal2" class="modal modal-fixed-footer">
                  <div class="modal-content">
                    <h4>Modal Header</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                      labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                      labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                      labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                      labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Disagree</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
                  </div>
                </div>
                <div id="modal3" class="modal bottom-sheet">
                  <div class="modal-content">
                    <h4>Modal Header</h4>
                    <ul class="collection">
                      <li class="collection-item avatar">
                        <img src="{{asset('images/avatar/avatar-7.png')}}" alt="" class="circle">
                        <span class="title">Title</span>
                        <p>First Line
                          <br> Second Line
                        </p>
                        <a href="#!" class="secondary-content">
                          <i class="material-icons">grade</i>
                        </a>
                      </li>
                      <li class="collection-item avatar">
                        <i class="material-icons circle">folder</i>
                        <span class="title">Title</span>
                        <p>First Line
                          <br> Second Line
                        </p>
                        <a href="#!" class="secondary-content">
                          <i class="material-icons">grade</i>
                        </a>
                      </li>
                      <li class="collection-item avatar">
                        <i class="material-icons circle green">assessment</i>
                        <span class="title">Title</span>
                        <p>First Line
                          <br> Second Line
                        </p>
                        <a href="#!" class="secondary-content">
                          <i class="material-icons">grade</i>
                        </a>
                      </li>
                      <li class="collection-item avatar">
                        <i class="material-icons circle red">play_arrow</i>
                        <span class="title">Title</span>
                        <p>First Line
                          <br> Second Line
                        </p>
                        <a href="#!" class="secondary-content">
                          <i class="material-icons">grade</i>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="html-fixed-example">
            <pre><code class="language-markup">
  &lt;!-- Modal Trigger -->
  &lt;a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal&lt;/a>
  &lt;!-- Modal Structure -->
  &lt;div id="modal1" class="modal">
    &lt;div class="modal-content">
      &lt;h4>Modal Header&lt;/h4>
      &lt;p>A bunch of text&lt;/p>
    &lt;/div>
    &lt;div class="modal-footer">
      &lt;a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree&lt;/a>
    &lt;/div>
  &lt;/div>
  </code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals with Fixed Footer -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="fixed-footer" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">Modals with Fixed Footer</h4>
          <p>If you have content that is very long and you want the action buttons to be visible all the time, you can
            add the <code class="language-markup">modal-fixed-footer</code> class to the modal. </p>
          <pre><code class="language-markup">
  &lt;!-- Modal Trigger -->
  &lt;a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal&lt;/a>
  &lt;!-- Modal Structure -->
  &lt;div id="modal1" class="modal modal-fixed-footer">
    &lt;div class="modal-content">
      &lt;h4>Modal Header&lt;/h4>
      &lt;p>A bunch of text&lt;/p>
    &lt;/div>
    &lt;div class="modal-footer">
      &lt;a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree&lt;/a>
    &lt;/div>
  &lt;/div>
  </code></pre>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Sheet Modals -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="sheet-modals" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">Bottom Sheet Modals</h4>
          <p>Bottom Sheet Modals are good for displaying actions to the user on the bottom of a screen. They still act
            the same as regular modals.</p>
          <pre><code class="language-markup">
  &lt;!-- Modal Trigger -->
  &lt;a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal&lt;/a>
  &lt;!-- Modal Structure -->
  &lt;div id="modal1" class="modal bottom-sheet">
    &lt;div class="modal-content">
      &lt;h4>Modal Header&lt;/h4>
      &lt;p>A bunch of text&lt;/p>
    &lt;/div>
    &lt;div class="modal-footer">
      &lt;a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree&lt;/a>
    &lt;/div>
  &lt;/div>
  </code></pre>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals with Button trigger -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="button-trigger" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">Modals with Button trigger</h4>
          <p>If you prefer to use a button to open a modal specify the Modal ID in <code
              class="language-markup">data-target</code>
            rather than the href attribute. </p>
          <pre><code class="language-markup">
  &lt;!-- Modal Trigger -->
  &lt;button data-target="modal1" class="btn modal-trigger">Modal&lt;/button>
  </code></pre>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery Plugin Initialization -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="plugin-initialization" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">jQuery Plugin Initialization</h4>
          <p>To open a modal using a trigger:</p>
          <pre><code class="language-javascript">
  $(document).ready(function(){
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
  });
  </code></pre>
          <p>You can also open modals programatically, the below code will make your modal open on document ready:</p>
          <pre><code class="language-javascript">
  $('#modal1').modal('open');
  </code></pre>
          <p>You can also close them programatically:</p>
          <pre><code class="language-javascript">
  $('#modal1').modal('close');
  </code></pre>
        </div>
      </div>
    </div>
  </div>

  <!-- Options -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="options" class="card card card-default scrollspy">
        <div class="card-content">
          <h4 class="card-title">Options</h4>
          <p>You can customize the behavior of each modal using these options. For example, you can call a custom
            function to run when a modal is dismissed. To do this, just place your function in the intialization code
            as shown below.</p>
          <pre><code class="language-javascript">
$('.modal').modal({
  dismissible: true, // Modal can be dismissed by clicking outside of the modal
  opacity: .5, // Opacity of modal background
  inDuration: 300, // Transition in duration
  outDuration: 200, // Transition out duration
  startingTop: '4%', // Starting top style attribute
  endingTop: '10%', // Ending top style attribute
  ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
  alert("Ready");
  console.log(modal, trigger);
  },
  complete: function() { alert('Closed'); } // Callback for Modal close
  }
);
</code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- page scripts  --}}
@section('page-script')
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection