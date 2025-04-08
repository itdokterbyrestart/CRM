@extends('layouts/contentLayoutMaster')

@section('title', 'Klanten')

@section('vendor-style')
@endsection

@section('content')

@livewire('customer.index')                 

@endsection

@section('vendor-script')
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
                Livewire.dispatch('delete_customer', {id: event.detail[0].id});
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
                    Livewire.dispatch('sendAppointmentMail', {customer_id: event.detail[0].id, email_type: event.detail[0].email_type});
                }
            });
    });
</script>
@endsection