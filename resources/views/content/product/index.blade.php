@extends('layouts/contentLayoutMaster')

@section('title', 'Producten')

@section('content')

@livewire('product.index')                 

@endsection

@section('page-script')
{{-- Sweet alert modals --}}
<script>
    window.addEventListener('swal:confirm', event => {
        swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].type,
            buttons: true,
            dangerMode: true,
			showCancelButton: true,
			reverseButtons: true,
			cancelButtonText: "Annuleren",
            confirmButtonText: "Ja, verwijder",
        })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    Livewire.dispatch('delete', {product_id: event.detail[0].id});
                }
            });
    });
</script>
@endsection