@extends('layouts/contentLayoutMaster')

@section('title', 'Product Groepen')

@section('content')


@livewire('product-group.index')                 

@endsection

@section('vendor-script')
<script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.0/dist/livewire-sortable.js"></script>
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
                    Livewire.dispatch('delete', {productgroup_id: event.detail[0].id});                    
                }
            });
    });
</script>
@endsection