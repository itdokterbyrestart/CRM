<form wire:submit="store" class="my-1">
    <div class="row justify-content-between align-items-start">
        <h3 class="col-auto">Klant service</h3>
        @if ($edit === 1)
            <div class="col-auto">
                <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
                    <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Opslaan
                </button>
            </div>    
        @endif
    </div>

    <div class="mb-1">
        <div class="form-group">
            <label for="customer" class="col-form-label">Klant</label>
            <select wire:model.blur="customer" id="customer" class="form-control @error('customer') is-invalid @enderror" tabindex="0"  @if ($edit === 0) disabled @endif>
                <option value="">Kies een klant</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <hr>

    <h3>Services</h3>


    <div class="mb-1">
        @forelse ($customerservices as $index => $customerservice)
            <div class="form-row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="service_id.{{ $index }}" class="col-form-label col-form-label-md">Service</label>
                        <select wire:model.live="service_id.{{ $index }}" id="service_id.{{ $index }}" class="form-control @error('service_id.' . $index) is-invalid @enderror" @if ($edit === 0) disabled @endif tabindex="0" >
                            <option value="" disabled hidden>Kies een Service</option>
                            @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="service_month.{{ $index }}" class="col-form-label col-form-label-md">Maand</label>
                        <select wire:model.live="service_month.{{ $index }}" id="service_month.{{ $index }}" class="form-control @error('service_month.' . $index) is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                            <option value="" disabled hidden>Kies een maand</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maart</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Augustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        @error('service_month.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label for="service_description.{{ $index }}" class="col-form-label col-form-label-md">Beschrijving</label>
                    <input wire:model.live.500ms="service_description.{{ $index }}" type="text" class="form-control form-control-md @error('service_description.' . $index) is-invalid @enderror" id="service_description.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('service_description.' . $index)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($edit === 0)
                    <div class="col-12 col-sm-6">
                        <label for="service_updated_at.{{ $index }}" class="col-form-label col-form-label-md">Aangepast op</label>
                        <input wire:model.blur="service_updated_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif

                @if ($edit === 0)
                    <div class="col-12 col-sm-6">
                        <label for="service_created_at.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt op</label>
                        <input wire:model.blur="service_created_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif


                @if ($edit === 1)
                    <div class="col-12 my-1">
                        @if ($loop->last)
                            <div wire:click="addService" class="btn btn-primary" tabindex="0">
                                <span wire:target="addService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                + Service toevoegen
                            </div>
                        @endif
                        <div wire:click="removeService({{ $index }})" class="btn btn-danger" tabindex="0">
                            <span wire:target="removeService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            - Service verwijderen
                        </div>
                    </div>
                @endif
            </div>
            @if (!$loop->last)
                <hr>
            @endif
        @empty
            <p>Er zijn geen services toegevoegd</p>
            @if ($edit === 1 AND count($customerservices) == 0)
                <div wire:click="addService"class="btn btn-primary" tabindex="0">
                    <span wire:target="addService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    + Service toevoegen
                </div>
            @endif
        @endforelse
    </div>

    @if ($edit === 1)
        <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
            <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Opslaan
        </button>
    @endif
</form>
