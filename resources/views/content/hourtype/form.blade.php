@extends('layouts/contentLayoutMaster')

@section('title', 'Uurtype')

@section('content')

@livewire('hourtype.form', ['model' => $hourtype, 'edit' => $edit])               

@endsection