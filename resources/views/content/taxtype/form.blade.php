@extends('layouts/contentLayoutMaster')

@section('title', 'Belastingtype')

@section('content')

@livewire('tax-type.form', ['model' => $taxtype, 'edit' => $edit])               

@endsection