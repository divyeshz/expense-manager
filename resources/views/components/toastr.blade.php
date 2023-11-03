@if ($msg = Session::get('success'))
    <script>
        toastr.success('{{ $msg }}');
    </script>
@endif

@if ($msg = Session::get('error'))
    <script>
        toastr.error('{{ $msg }}');
    </script>
@endif

@if ($msg = Session::get('info'))
    <script>
        toastr.info('{{ $msg }}');
    </script>
@endif

@if ($msg = Session::get('warning'))
    <script>
        toastr.warning('{{ $msg }}');
    </script>
@endif
