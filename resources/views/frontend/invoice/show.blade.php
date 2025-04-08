@extends('layouts/fullLayoutMaster')

@section('title', 'Factuur ' . $invoice->invoice_number . ' - ' . (strlen($customer->company) > 0 ? $customer->company : $customer->name))

@section('page-style')
<style>
	@media print
	{    
		@page {
			size: 992px  1403px;
		}
	}
</style>
@endsection

@section('content')

<div class="container-lg d-print-none">
	<div class="card mt-2">
		<div class="card-header">
			<h2 class="mb-0">Factuur {{ $invoice->invoice_number }}</h2>
		</div>
		<div class="card-body">
			<div class="row">
				@if ($status->name === 'Betaald')
					<div class="col-12">
						<button class="btn btn-success btn-lg" style="pointer-events: none;">Betaald</button>
					</div>
					<div class="col-12 mt-1">
						<p>
							{{ $status->pivot->comment ?? 'Bedankt voor de betaling, deze is in goede orde ontvangen.' }}
						</p>							
					</div>
				@elseif ($status->name === 'Geweigerd')
				<div class="col-12">
					<button class="btn btn-danger btn-lg" style="pointer-events: none;">Geweigerd</button>
				</div>
				<div class="col-12 mt-1">
					<p>
						{{ $status->pivot->comment ?? 'De factuur is geweigerd, ik neem zo snel mogelijk contact op.' }}
					</p>
				</div>
				@elseif ($status->name === 'Wachten op betaling' || $status->name === 'Verlopen' || $status->name === 'Herinnering 1' || $status->name === 'Herinnering 2' || $status->name === 'Herinnering 3')
					@livewire('frontend.invoice.payment', ['invoice_id' => $invoice->id])
				@endif
				<div class="col-12 mt-3">
					<a href="javascript:void(0)" onclick="window.print();">
						<button class="btn btn-primary btn-lg" style="pointer-events: none;">
							<i class="fa fa-print"></i>&nbsp;Printen
						</button>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@include('frontend.invoice.invoice')

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
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
@endsection
