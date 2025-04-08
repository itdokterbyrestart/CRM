@extends('layouts/contentLayoutMaster')

@section('title', 'Belastingtype')

@section('content')

@livewire('taxtype.form', ['model' => $taxtype, 'edit' => $edit])               

@endsection