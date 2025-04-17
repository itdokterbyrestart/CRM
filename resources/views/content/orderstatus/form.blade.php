@extends('layouts/contentLayoutMaster')

@section('title', 'Opdracht status')

@section('content')

@livewire('order-status.form', ['model' => $orderstatus, 'edit' => $edit])               

@endsection