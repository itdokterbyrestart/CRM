@extends('layouts/contentLayoutMaster')

@section('title', 'Rol')

@section('content')

@livewire('role.form', ['model' => $role, 'edit' => $edit])               

@endsection