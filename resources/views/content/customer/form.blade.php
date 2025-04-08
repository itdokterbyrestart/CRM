@extends('layouts/contentLayoutMaster')

@section('title', 'Klant')

@section('content')

@livewire('customer.form', ['model' => $customer, 'edit' => $edit])               

@endsection