<!-- Footer -->
<footer class="main-footer">
  <strong>
    Copyright &copy; {{ date('Y') }}
    <a href="https://www.celisoftware.com/">CeliSoftware</a>.
  </strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 1.0
  </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script>
  var BASE_URL = "{{ url('/') }}";
</script>

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob -->
<script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus -->
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('custom/js/toastr.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- Inputmask -->
<script src="{{ asset('adminlte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- pace-progress -->
<script src="{{ asset('adminlte/plugins/pace-progress/pace.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('custom/js/form-validation.js') }}"></script>
<script src="{{ asset('custom/js/common.js') }}"></script>

<!-- External CDN JS -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="{{ asset('js/location-helper.js') }}"></script>

<script>
  $(function () {
    $('#example1').DataTable();
    $('[data-mask]').inputmask();
    bsCustomFileInput.init();

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('[data-toggle="tooltip"]').tooltip();
    
    //Initialize Select2 Elements
    $('.select2').select2();
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    $('#checkInDate').datetimepicker({
      format: 'YYYY-MM-DD',
      minDate: moment(),
      defaultDate: moment().toDate(),
    });
    $('#checkInDate input').on('click', function() {
      $('#checkInDate').datetimepicker('toggle');
    });
    setTimeout(() => {
        $('.alert').alert('close');
    }, 3000);
  });

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>
