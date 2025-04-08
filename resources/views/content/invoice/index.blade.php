@extends('layouts/contentLayoutMaster')

@section('title', 'Facturen')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
<!-- modalForm -->
<div wire:ignore.self class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFile">Factuurbestanden</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @livewire('invoice.file')
            </div>
        </div>
    </div>
</div>

@livewire('invoice.index')

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Bootstrap edit modal --}}
<script>
    window.addEventListener('closeFileModal', event => {
        $("#modalFile").modal('hide');
    })
    window.addEventListener('openFileModal', event => {
        $("#modalFile").modal('show');
    })
    $(document).ready(function(){
        $("#modalFile").on('hidden.bs.modal', function(){
            Livewire.dispatchTo('invoice.file','forcedCloseModal');
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
        .then((willDeleteInvoice) => {
            if (willDeleteInvoice.isConfirmed) {
                Livewire.dispatch('delete_invoice', {id: event.detail[0].id});
            }
        });
    });
    window.addEventListener('swal:confirm-file', event => {
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
                Livewire.dispatchTo('invoice.file','delete', {id: event.detail[0].id});
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
                    Livewire.dispatch('send_invoice_to_customer', {invoice_id: event.detail[0].id});
                }
            });
    });
</script>
@endsection