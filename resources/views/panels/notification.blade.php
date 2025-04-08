@if($message = Session::get('success'))
    <script>
        swal.fire({
            title: '{{ $message['title'] }}',
            text: '{{ $message['text'] }}',
            icon: 'success',
        });
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        swal.fire({
            title: '{{ $message['title'] }}',
            text: '{{ $message['text'] }}',
            icon: 'error',
        });
    </script>
@endif
@if ($message = Session::get('warning'))
    <script>
        swal.fire({
            title: '{{ $message['title'] }}',
            text: '{{ $message['text'] }}',
            icon: 'warning',
        });
    </script>
@endif
@if ($message = Session::get('info'))
    <script>
        swal.fire({
            title: '{{ $message['title'] }}',
            text: '{{ $message['text'] }}',
            icon: 'info',
        });
    </script>
@endif
@if ($message = Session::get('question'))
<script>
    swal.fire({
            title: '{{ $message['title'] }}',
            text: '{{ $message['text'] }}',
            icon: 'question',
        });
</script>
@endif