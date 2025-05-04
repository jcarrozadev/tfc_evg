@if(session('success'))
    <script>
        swal({
            title: "¡Correcto!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 2500,
            buttons: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        swal({
            title: "Error",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 3000,
            buttons: false
        });
    </script>
@endif
