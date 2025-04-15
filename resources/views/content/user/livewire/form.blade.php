<div class="card">
	<div class="card-body">     
		<form wire:submit="store" class="my-1">
			<div class="row justify-content-between align-items-start mb-2">
                <div class="col-auto">
                    <a href="{{ route('user.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
                        <a class="btn btn-primary" href="{{ route('user.edit', $modelId) }}" tabindex="0">
                            Aanpassen
                        </a>
                    </div>
                @endif
            </div>
			
			<div class="row">
				@if ($edit === 0)
					<div class="col-12">
						<label for="id" class="col-form-label col-form-label-md">ID</label>
						<input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				<div class="col-12 col-lg-6">
					<label for="name" class="col-form-label col-form-label-md">Naam</label>
					<input wire:model.blur="name" type="text" class="form-control form-control-md @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0" @if ($edit === 0) disabled @endif>
					@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-lg-6">
					<label for="email" class="col-form-label col-form-label-md">Email</label>
					<input wire:model.blur="email" type="email" class="form-control form-control-md @error('email') is-invalid @enderror" id="email" tabindex="0" @if ($edit === 0) disabled @endif>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-lg-6">
					<label for="password" class="col-form-label col-form-label-md">Wachtwoord</label>
					<input wire:model.blur="password" type="password" class="form-control form-control-md @error('password') is-invalid @enderror" id="password" tabindex="0" @if ($edit === 0) disabled @endif>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-lg-6">
					<label for="password_confirmation" class="col-form-label col-form-label-md">Wachtwoord bevestigen</label>
					<input wire:model.blur="password_confirmation" type="password" class="form-control form-control-md @error('password_confirmation') is-invalid @enderror" id="password_confirmation" tabindex="0" @if ($edit === 0) disabled @endif>
					@error('password_confirmation')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12">
					<div class="d-flex">
						<div class="form-check mt-1 mr-1">        
							<input wire:model.blur="mail_report" type="checkbox" id="mail_report" class="form-check-input" @if ($edit === 0) disabled @endif>
							<label for="mail_report" class="form-check-label">E-mail rapportage</label>
							@error('mail_report')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="form-check mt-1 mr-1">        
							<input wire:model.blur="blocked" type="checkbox" id="blocked" class="form-check-input" @if ($edit === 0) disabled @endif>
							<label for="blocked" class="form-check-label">Geblokkeerd</label>
							@error('blocked')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>

				<div class="col-12 mt-2">
					<h6 class="mb-0">Rollen</h6>
					<div class="d-flex">
						@foreach ($roles as $role)
							<div class="form-check mr-1">        
								<input wire:model.blur="selected_roles.{{ $role->id }}" type="checkbox" id="role.{{ $role->id }}" class="form-check-input" @if ($edit === 0) disabled @endif>
								<label for="role.{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
							</div>
						@endforeach
					</div>
				</div>

				@if ($edit === 0)
					<div class="col-12">
						<label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
						<input wire:model.blur="updated_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				@if ($edit === 0)
					<div class="col-12">
						<label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
						<input wire:model.blur="created_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				@if ($edit === 1)
					<div class="col-12 mt-1">
						<a href="{{ route('user.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
							Annuleren
						</a>
						<button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
							<span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Opslaan
						</button>
					</div>
				@endif
			</div>
		</form>
	</div>
</div>
