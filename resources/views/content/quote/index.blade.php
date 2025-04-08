@extends('layouts/contentLayoutMaster')

@section('title', 'Offertes')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

@livewire('quote.index')

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/popper/popper.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Bootstrap edit modal --}}
<script>
    window.addEventListener('closeProductSelectorModal', event => {
        $("#ProductSelectorForm").modal('hide');
    })
    window.addEventListener('openProductSelectorModal', event => {
        $("#ProductSelectorForm").modal('show');
    })
    $(document).ready(function(){
        $('#ProductSelectorForm').on('show.bs.modal', function () {
            $('#modalForm').css('z-index', 1039);
        });

        $('#ProductSelectorForm').on('hidden.bs.modal', function () {
            $('#modalForm').css('z-index', 1041);
        });
    });
</script>
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
                    Livewire.dispatch('delete', {quote_id: event.detail[0].id});

                }
            });
    });
</script>
<script>
    window.addEventListener('swal:modal.convert', event => {
        swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].type,
            buttons: true,
            dangerMode: true,
            showDenyButton: true,
			showCancelButton: true,
			reverseButtons: true,
			cancelButtonText: "Nee",
            confirmButtonText: "Ja",
            denyButtonText: 'Ja, met aanbetaling',
            customClass: {
                cancelButton: 'order-1 right-gap',
                confirmButton: 'order-2',
                denyButton: 'order-3',
            },
        })
            .then((willConvert) => {
                if (willConvert.isConfirmed) {
                    Livewire.dispatch('convert_quote_to_order', {quote_id: event.detail[0].id, deposit: 0});
                } else if (willConvert.isDenied) {
                    Livewire.dispatch('convert_quote_to_order', {quote_id: event.detail[0].id, deposit: 1});
                }
            });
    });
</script>
<script>
    window.addEventListener('swal:modal.confirm.send', event => {
        swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].type,
            buttons: true,
            dangerMode: true,
			showCancelButton: true,
			reverseButtons: true,
			cancelButtonText: "Nee",
            confirmButtonText: "Ja, toch versturen",
        })
            .then((willSend) => {
                if (willSend.isConfirmed) {
                    Livewire.dispatch('send_quote_to_customer', {quote_id: event.detail[0].id});
                }
            });
    });
</script>
@stack('page-scripts')
@endsection