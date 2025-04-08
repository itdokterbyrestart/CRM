@extends('layouts/contentLayoutMaster')

@section('title', 'Service')

@section('content')

@livewire('service.form', ['model' => $service, 'edit' => $edit])               

@endsection