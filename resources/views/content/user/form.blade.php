@extends('layouts/contentLayoutMaster')

@section('title', 'Gebruiker')

@section('content')

@livewire('user.form', ['model' => $user, 'edit' => $edit])               

@endsection