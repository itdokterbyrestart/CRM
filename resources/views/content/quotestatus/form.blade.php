@extends('layouts/contentLayoutMaster')

@section('title', 'Offerte status')

@section('content')

@livewire('quote-status.form', ['model' => $quotestatus, 'edit' => $edit])               

@endsection