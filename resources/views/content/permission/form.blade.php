@extends('layouts/contentLayoutMaster')

@section('title', 'Permissie')

@section('content')

@livewire('permission.form', ['model' => $permission, 'edit' => $edit])               

@endsection