@if(session('success'))
    <script>
        swal("¡Hecho!", "{{ session('success') }}", "success");
    </script>
@endif

@if(session('error'))
    <script>
        swal("¡Error!", "{{ session('error') }}", "error");
    </script>
@endif
