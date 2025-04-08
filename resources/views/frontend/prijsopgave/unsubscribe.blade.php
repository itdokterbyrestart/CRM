@extends('layouts/fullLayoutMaster')

@section('title', 'Succesvol afgemeld')

@section('vendor-style')
@endsection

@section('content')
<div class="container-sm">
	<div class="card mt-5">
		<div class="card-header">
			<h2>Je bent succesvol afgemeld en de prijsopgave is verwijderd uit de database.</h2>
		</div>
		<div class="card-body">
			<h4>Wil je een nieuwe prijsopgave doen?</h4>
			<a href="https://t-fooh.nl/prijsopgave.html" class="btn btn-primary">Nieuwe prijsopgave</a>
		</div>
	</div>
</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
@endsection
