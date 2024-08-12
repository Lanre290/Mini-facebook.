@if(null === session('user'))
    <script>
        window.location.href = "/login";
    </script>
@endif