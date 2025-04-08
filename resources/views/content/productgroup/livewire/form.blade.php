<div class="card">
	<div class="card-body">
        <form wire:submit="store" class="my-1">
            <div class="row justify-content-between align-items-start mb-2">
                <div class="col-auto">
                    <a href="{{ route('productgroup.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
                        @if ($edit == 1) Annuleren @else Terug @endif
                    </a>
                </div>
                @if ($edit == 1)
                    <div class="col-auto">
                        <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
                            <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Opslaan
                        </button>
                    </div>
                @else
                    <div class="col-auto">
                        <a class="btn btn-primary" href="{{ route('productgroup.edit', $modelId) }}" tabindex="0">
                            Aanpassen
                        </a>
                    </div>
                @endif
            </div>


            @if ($edit === 0)
                <div class="mb-1">
                    <label for="id" class="col-form-label col-form-label-md">ID</label>
                    <input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif
            
            <div class="mb-1">
                <label for="name" class="col-form-label col-form-label-md">Naam</label>
                <input wire:model.blur="name" type="text" class="form-control form-control-md @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0" @if ($edit === 0) disabled @endif>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check">
                <input wire:model.blur="description_before_products" type="checkbox" id="description_before_products" class="form-check-input" @if ($edit === 0) disabled @endif>
                <label for="description_before_products" class="form-check-label">Beschrijving voor producten</label>
                @error('description_before_products')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>

            <h3>Producten</h3>
            @error('selected_products')
                <div class="alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-1">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-12 col-md-6">
                            <div class="form-check">        
                                <input wire:model.blur="selected_products.{{ $product->id }}" type="checkbox" id="product.{{ $product->id }}" class="form-check-input" @if ($edit === 0) disabled @endif>
                                <label for="product.{{ $product->id }}" class="form-check-label">{{ $product->name }}</label>
                                @error('selected_products.' . $product->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-1">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th width="20%">Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                asort($selected_products_order);
                            @endphp
                            @foreach ($selected_products_order as $id => $value)
                                <tr>
                                    <td>{{ $products->find($id)->name }}</td>
                                    <td width="20%">
                                        <input wire:model.blur="selected_products_order.{{ $id }}" type="number" class="form-control form-control-md @error('selected_products_order.' . $product->id) is-invalid @enderror" id="selected_products_order.{{ $id }}" required min="0" max="2147483647" @if ($edit === 0) disabled @endif>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-1">
                <label for="description" class="col-form-label col-form-label-md">Beschrijving</label>
                <textarea wire:model.blur="description" type="text" class="form-control form-control-md @error('description') is-invalid @enderror" id="description" tabindex="0" rows="{{ $description_count }}" @if ($edit === 0) disabled @endif>
                </textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            

            @if ($edit === 0)
                <div class="mb-1">
                    <label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
                    <input wire:model.blur="updated_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif

            @if ($edit === 0)
                <div class="mb-1">
                    <label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
                    <input wire:model.blur="created_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif
            
            @if ($edit === 1)
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('productgroup.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
                            Annuleren
                        </a>
                        <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
                            <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Opslaan
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
