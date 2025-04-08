@extends('layouts/contentLayoutMaster')

@section('title', 'Opdrachten')

@section('content')

@livewire('order.index')

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
                    Livewire.dispatch('delete', {id: event.detail[0].id});
                }
            });
    });
    window.addEventListener('swal:invoice_found', event => {
        swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].type,
            buttons: true,
            dangerMode: false,
            showDenyButton: true,
			reverseButtons: true,
            denyButtonText: "Factuur bekijken",
            confirmButtonText: "Nieuwe maken",
        })
            .then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('createInvoice', {order_id: event.detail[0].id});
                } else if (result.isDenied) {
                    Livewire.dispatch('showInvoice', {order_id: event.detail[0].id});
                }
            });
    });
    window.addEventListener('swal:confirm_appointment', event => {
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
            .then((sendAppointmentMail) => {
                if (sendAppointmentMail.isConfirmed) {
                    console.log(event.detail[0].id, event.detail[0].email_type);
                    Livewire.dispatch('sendAppointmentMail', {order_id: event.detail[0].id, email_type: event.detail[0].email_type});
                }
            });
    });
</script>
@stack('page-scripts')
@endsection