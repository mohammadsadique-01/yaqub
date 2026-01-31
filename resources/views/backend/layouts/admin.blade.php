@include('backend.layouts.header')
@include('backend.layouts.sidebar')

    <div class="content-wrapper">
      @yield('content')
    </div>
    
    <!-- .modal-content -->
    <div class="modal fade" id="modal-global">
      <div class="modal-dialog">
        <div class="modal-content">
          
        </div>
      </div>
    </div>
    <div class="csrfHtml" data-csrf="{{ csrf_token() }}"></div>
  

      @include('backend.layouts.footer')
      @yield('customjsfile')
      @stack('scripts')
   </body>
</html>