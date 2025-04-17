@extends('layouts/contentLayoutMaster')

@section('title', 'Uurtype')

@section('content')

@livewire('hour-type.form', ['model' => $hourtype, 'edit' => $edit])               

@endsection