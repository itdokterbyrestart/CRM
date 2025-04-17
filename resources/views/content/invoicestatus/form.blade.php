@extends('layouts/contentLayoutMaster')

@section('title', 'Factuur status')

@section('content')

@livewire('invoice-status.form', ['model' => $invoicestatus, 'edit' => $edit])               

@endsection