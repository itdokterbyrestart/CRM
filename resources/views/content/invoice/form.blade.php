@extends('layouts/contentLayoutMaster')

@section('title', 'Factuur')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

@livewire('invoice.form', ['model' => $invoice, 'edit' => $edit])

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')

@stack('page-scripts')

@endsection