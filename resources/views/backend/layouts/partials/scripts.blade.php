<!-- REQUIRED JS SCRIPTS -->
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('/css/dataTables.bootstrap.css') }}">
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script> 
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

<!-- Bootstrap 3.3.2 JS --> 
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script> 
<!-- FastClick --> 
<script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script> 
<!-- Admin App --> 
<script src="{{ asset('js/app.min.js') }}" type="text/javascript"></script> 

<script src="{{ asset('plugins/moment/moment.min.js') }}" type="text/javascript"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
