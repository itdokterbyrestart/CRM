@extends('layouts/contentLayoutMaster')

@section('title', 'Instelling')

@section('content')

@livewire('setting.form', ['model' => $setting, 'edit' => $edit])               

@endsection