@extends('layouts/contentLayoutMaster')

@section('title', 'Product')

@section('content')

@livewire('product.form', ['model' => $product, 'edit' => $edit])               

@endsection

@section('vendor-script')
<script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.1/dist/livewire-sortable.js"></script>
@endsection