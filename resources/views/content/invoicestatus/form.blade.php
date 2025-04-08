@extends('layouts/contentLayoutMaster')

@section('title', 'Factuur status')

@section('content')

@livewire('invoicestatus.form', ['model' => $invoicestatus, 'edit' => $edit])               

@endsection