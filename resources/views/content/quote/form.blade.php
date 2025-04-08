@extends('layouts/contentLayoutMaster')

@section('title', 'Offerte')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

<!-- modalForm -->
<div wire:ignore.self class="modal fade" data-backdrop="static" id="ProductSelectorForm" tabindex="-1" role="dialog" aria-labelledby="ProductSelectorForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalForm">Product Selectie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @livewire('order.product-select')
            </div>
        </div>
    </div>
</div>

@livewire('quote.form', ['model' => $quote, 'edit' => $edit])

@endsection

@section('vendor-script')
<script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.1/dist/livewire-sortable.js"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Bootstrap edit modal --}}
<script>
    window.addEventListener('closeProductSelectorModal', event => {
        $("#ProductSelectorForm").modal('hide');
    })
    window.addEventListener('openProductSelectorModal', event => {
        $("#ProductSelectorForm").modal('show');
    })
    $(document).ready(function(){
        $('#ProductSelectorForm').on('show.bs.modal', function () {
            $('#modalForm').css('z-index', 1039);
        });

        $('#ProductSelectorForm').on('hidden.bs.modal', function () {
            $('#modalForm').css('z-index', 1041);
        });
    });
</script>
@stack('page-scripts')
@endsection