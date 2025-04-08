@extends('layouts/contentLayoutMaster')

@section('title', 'Service - Klanten')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

<!-- modalForm -->
<div wire:ignore.self class="modal fade" data-backdrop="static" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalForm">Service - Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @livewire('service.users.form')
            </div>
        </div>
    </div>
</div>

@livewire('service.users.index')                 

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Bootstrap edit modal --}}
<script>
    window.addEventListener('closeModal', event => {
        $("#modalForm").modal('hide');
    })
    window.addEventListener('openModal', event => {
        $("#modalForm").modal('show');
    })
    $(document).ready(function(){
        // This event is triggered when the modal is hidden
        $("#modalForm").on('hidden.bs.modal', function(){
            livewire.emit('forcedCloseModal');
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
                Livewire.dispatch('delete', {id: event.detail[0].id});
            }
        });
    });
    window.addEventListener('swal:confirm-send', event => {
        swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].type,
            buttons: true,
            dangerMode: true,
			showCancelButton: true,
			reverseButtons: true,
			cancelButtonText: "Annuleren",
            confirmButtonText: "Ja, verstuur",
        })
            .then((willSend) => {
                if (willSend.isConfirmed) {
                    Livewire.dispatch('sendInvitationMail', {itemID: event.detail[0].id});
                }
            });
    });
</script>
@stack('page-scripts')
@endsection