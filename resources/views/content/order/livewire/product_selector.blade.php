<form wire:submit="select_product" class="my-1">
    <div class="form-row">
        <div class="col-12">
            <label for="product_id" class="col-form-label col-form-label-md">Product</label>
            <div class="input-group">
                <select wire:model.blur="product_id" class="form-control form-control-md @error('product_id') is-invalid @enderror" id="product_id">
                    <option value="0" disabled>Kies een product</option>
                    @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="btn btn-primary" wire:click="openProductModal">+</div>
                </div>
            </div>
                
            @error('product_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button wire:target="select_product" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="select_product" class="btn btn-primary my-1" tabindex="0">
        <span wire:target="select_product" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Kies product
    </button>
</form>