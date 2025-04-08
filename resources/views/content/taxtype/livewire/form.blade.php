<div class="card">
	<div class="card-body">        
        <form wire:submit="store" class="my-1">
            <div class="row justify-content-between align-items-start mb-2">
                <div class="col-auto">
                    <a href="{{ route('taxtype.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
                        <a class="btn btn-primary" href="{{ route('taxtype.edit', $modelId) }}" tabindex="0">
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
                <label for="name" class="col-form-label col-form-label-md">Naam *</label>
                <input wire:model.blur="name" type="text" class="form-control form-control-md @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0" @if ($edit === 0) disabled @endif>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-1">
                <label for="percentage" class="col-form-label col-form-label-md">Percentage *</label>
                <div class="input-group">
                    <input wire:model.blur="percentage" type="number" class="form-control form-control-md @error('percentage') is-invalid @enderror" id="percentage" tabindex="0" @if ($edit === 0) disabled @endif min="0" max="100" step="1">
                    <div class="input-group-append">
                        <span class="input-group-text" id="percentage">%</span>
                    </div>
                </div>
                @error('percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <div class="form-check">        
                    <input wire:model.blur="default" type="checkbox" id="default" class="form-check-input" @if ($edit === 0) disabled @endif>
                    <label for="default" class="form-check-label">Standaard</label>
                    @error('default')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
                        <a href="{{ route('taxtype.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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