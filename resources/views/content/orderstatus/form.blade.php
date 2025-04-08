@extends('layouts/contentLayoutMaster')

@section('title', 'Opdracht status')

@section('content')

@livewire('orderstatus.form', ['model' => $orderstatus, 'edit' => $edit])               

@endsection