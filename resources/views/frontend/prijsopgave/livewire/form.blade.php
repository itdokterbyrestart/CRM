<form wire:submit="store" class="my-1">
    <div class="row">
        @if ($currentStep == 0)
            <h1>Er is een fout opgetreden, ververs de pagina en probeer het opnieuw</h1>
        @endif

        @if ($currentStep != 0)
            <div class="col-12 mb-1">
                <h1 class="mx-1">
                    <div class="row justify-content-between">
                        @if ($currentStep > 1) <span wire:click="StepSelection('1')">@endif <button type="button" class="mb-1 col-auto btn @if ($currentStep == 1) btn-primary @else btn-secondary @endif" @if ($currentStep == 5) disabled @endif>Datum check</button>@if ($currentStep > 1) </span> @endif > 
                        @if ($currentStep > 2) <span wire:click="StepSelection('2')">@endif <button type="button" class="mb-1 col-auto btn @if ($currentStep == 2) btn-primary @elseif($currentStep > 2) btn-secondary @else btn-light @endif" @if ($currentStep < 2) disabled @endif @if ($currentStep == 5) disabled @endif>Contact info</button> @if ($currentStep > 2) </span> @endif >
                        @if ($currentStep > 3) <span wire:click="StepSelection('3')">@endif <button type="button" class="mb-1 col-auto btn @if ($currentStep == 3) btn-primary @elseif($currentStep > 3) btn-secondary @else btn-light @endif" @if ($currentStep < 3) disabled @endif @if ($currentStep == 5) disabled @endif>Feest info</button> @if ($currentStep > 3) </span> @endif >
                        <button type="button" class="mb-1 col-auto btn @if ($currentStep == 4) btn-primary @elseif($currentStep > 4) btn-secondary @else btn-light @endif" @if ($currentStep < 4) disabled @endif @if ($currentStep == 5) disabled @endif>Controle</button> > 
                        <button type="button" class="mb-1 col-auto btn @if ($currentStep == 5) btn-primary @elseif($currentStep > 5) btn-secondary @else btn-light @endif" @if ($currentStep < 5) disabled @endif>Prijsopgave</button>
                    </div>
                </h1>
            </div>
        @endif

        @if ($currentStep == 1)
            {{-- Datum check --}}
            <div class="col-12">
                <h2>Datum check</h2>
            </div>

            {{-- party_date --}}
            <div class="col-12">
                <label for="party_date" class="col-form-label col-form-label-lg">Feestdatum</label>
                <input wire:model.live.debounce.500ms="party_date" type="date" class="form-control form-control-lg @error('party_date') is-invalid @enderror" id="party_date" required autofocus tabindex="0">
                @error('party_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if ($party_date_available !== null)
                <div class="col-12 mt-2">
                    @if ($party_date_available == true)
                        <h4><span class="text-success"><i class="fa fa-check"></i> De datum is beschikbaar</span></h4>
                    @else
                        <h4><span class="text-danger"><i class="fa fa-times"></i> De datum is helaas niet beschikbaar</span></h4>
                    @endif
                </div>
            @endif

            {{-- Next Step --}}
            @if ($party_date_available !== null)
                @if ($party_date_available == true)
                    <div class="col-12 mt-2">
                        <button type="button" wire:click="nextStep" class="btn btn-primary w-100">Naar contact info ></button>
                    </div>
                @endif
            @endif
        @endif

        @if ($currentStep == 2)
            {{-- Contactgegevens --}}
            <div class="col-12">
                <h2>Contactgegevens</h2>
            </div>

            {{-- Name --}}
            <div class="col-12">
                <label for="name" class="col-form-label col-form-label-lg">Naam</label>
                <input wire:model.live.debounce.500ms="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- E-mail --}}
            <div class="col-12">
                <label for="email" class="col-form-label col-form-label-lg">E-mailadres (Hierop ontvang je de prijsopgave)</label>
                <input wire:model.live.debounce.500ms="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" required tabindex="0">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-12">
                <label for="phone" class="col-form-label col-form-label-lg">Telefoonnummer (Om de prijsopgave te bespreken)</label>
                <input wire:model.live.debounce.500ms="phone" type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="phone" required tabindex="0">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Next and previous Step --}}
            <div class="col-12 mt-2">
                <div class="btn-group w-100" role="group" aria-label="Navigation">
                    <button type="button" wire:click="previousStep" class="btn btn-light w-50">< Terug naar feestdatum</button>
                    <button type="button" wire:click="nextStep" class="btn btn-primary w-50">Naar feest info ></button>
                </div>
            </div>
        @endif

        @if ($currentStep == 3)        
            {{-- Feest info --}}
            <div class="col-12">
                <h2>Feest info</h2>
            </div>

            {{-- start_time --}}
            <div class="col-6">
                <label for="start_time" class="col-form-label col-form-label-lg">Starttijd</label>
                <input wire:model.live.debounce.500ms="start_time" type="time" class="form-control form-control-lg @error('start_time') is-invalid @enderror" id="start_time" required autofocus tabindex="0" step="1800">
                @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- end_time --}}
            <div class="col-6">
                <label for="end_time" class="col-form-label col-form-label-lg">Eindtijd</label>
                <input wire:model.live.debounce.500ms="end_time" type="time" class="form-control form-control-lg @error('end_time') is-invalid @enderror" id="end_time" required tabindex="0" step="1800">
                @error('end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Party type --}}
            <div class="col-12">
                <label for="party_type" class="col-form-label col-form-label-lg">Type feest</label>
                <select wire:model.live.debounce.500ms="party_type" class="form-control form-control-lg @error('party_type') is-invalid @enderror" id="party_type">
                    <option value="" disabled hidden>Kies een type feest</option>
                    @foreach ($party_type_array as $party_type_name)
                        <option value="{{ $party_type_name }}">{{ $party_type_name }}</option>
                    @endforeach
                </select>
                @error('party_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- location --}}
            <div class="col-12">
                <label for="location" class="col-form-label col-form-label-lg">Feestlocatie <span class="text-info" data-toggle="tooltip" data-placement="top" title="De feestlocatie moet bereikbaar zijn zonder de tussenkomst van trappen of opstapjes. Staat de exacte locatie niet in de lijst? Kies dan de plaats die het dichtst in de buurt ligt of selecteer 'De plaats staat niet in de lijst'."><i class="fas fa-info-circle"></i> Info</span></label>
                <select wire:model.live.debounce.500ms="location" class="form-control form-control-lg @error('location') is-invalid @enderror" id="location">
                    <option value="" disabled hidden>Kies een plaats</option>
                    @foreach ($location_array as $location_name)
                        <option value="{{ $location_name }}">{{ $location_name }}</option>
                    @endforeach
                </select>                
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- party_on_upper_floor --}}
            <div class="col-6 col-xl-4 mt-1">
                <div class="form-check">
                    <input wire:model.live.debounce.500ms="party_on_upper_floor" type="checkbox" id="party_on_upper_floor" class="form-check-input">
                    <label for="party_on_upper_floor" class="form-check-label">Het feest is op een verdieping of in de kelder</label>
                    @error('party_on_upper_floor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            @if ($party_on_upper_floor == true)
                {{-- upper_floor_elevator_available --}}
                <div class="col-6 col-xl-4 mt-1">
                    <div class="form-check">
                        <input wire:model.live.debounce.500ms="upper_floor_elevator_available" type="checkbox" id="upper_floor_elevator_available" class="form-check-input">
                        <label for="upper_floor_elevator_available" class="form-check-label">Er is een lift aanwezig (minimaal 1x2m)</label>
                        @error('upper_floor_elevator_available')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

            @if ($upper_floor_elevator_available == false AND $party_on_upper_floor == true)
                <div class="col-12 mt-2">
                    <div class="alert alert-danger p-1" role="alert">
                        Helaas is een feest op een locatie waar trappen of opstapjes zijn niet mogelijk.
                      </div>
                </div>
            @endif

            {{-- guest_amount --}}
            <div class="col-12">
                <label for="guest_amount" class="col-form-label col-form-label-lg">Aantal gasten</label>
                <input pattern="[0-9]*" wire:model.live.debounce.500ms="guest_amount" type="number" step="1" class="form-control form-control-lg @error('guest_amount') is-invalid @enderror" id="guest_amount" required tabindex="0">
                @error('guest_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- show_type --}}
            <div class="col-12">
                <label for="show_type" class="col-form-label col-form-label-lg">Show <span class="text-info" data-toggle="tooltip" data-placement="top" title="De optie 'Alleen DJ' wil zeggen dat alles op locatie aanwezig: licht, geluid en een DJ Booth. Is dat niet aanwezig? Kies dan een DJ show."><i class="fas fa-info-circle"></i> Info</span></label>
                <select wire:model.live.debounce.500ms="show_type" class="form-control form-control-lg @error('show_type') is-invalid @enderror" id="show_type">
                    <option value="" disabled hidden>Kies een show</option>
                    @foreach ($show_type_array as $show_type_name)
                        <option value="{{ $show_type_name }}">{{ $show_type_name }}</option>
                    @endforeach
                </select>                
                @error('show_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            @if ($show_type != null)
                <div class="col-12">
                    @if ($show_type == 'Premium DJ Show' && $party_type == 'Bruiloft')
                        @php
                            $media_url = \App\Models\Product::where('name', 'Bruiloft Brons - Premium DJ Show')->with(['media' => function ($q) {$q->orderBy('order_column');}])->first()->media->first()->getUrl() ?? 0;
                            $label = "Premium DJ Show - Bruiloft";
                        @endphp
                    @elseif ($show_type == 'Premium DJ Show')
                        @php
                            $media_url = \App\Models\Product::where('name', 'Feest Brons - Premium DJ Show')->with(['media' => function ($q) {$q->orderBy('order_column');}])->first()->media->first()->getUrl() ?? 0;
                            $label = "Premium DJ Show";
                        @endphp    
                    @elseif ($show_type == 'Deluxe DJ Show')
                        @php
                            $media_url = \App\Models\Product::where('name', 'Feest Brons - Deluxe DJ Show')->with(['media' => function ($q) {$q->orderBy('order_column');}])->first()->media->first()->getUrl() ?? 0;
                            $label = "Deluxe DJ Show";
                        @endphp    
                    @elseif ($show_type == 'Alleen DJ')
                        @php
                            $media_url = \App\Models\Product::where('name', 'Alleen DJ - Brons')->with(['media' => function ($q) {$q->orderBy('order_column');}])->first()->media->first()->getUrl() ?? 0;
                            $label = "Alleen DJ";
                        @endphp  
                        <h4 class="text-warning mt-2"><i class="fa fa-exclamation"></i> Let op! Je hebt gekozen voor alleen DJ. Hierbij moet de locatie beschikken over licht, geluid en een DJ booth.</h4>
                        @if ($location_has_equipment == false)                        
                            <button type="button" class="btn btn-primary" wire:click="$set('location_has_equipment', true)">Ik bevestig dat de locatie beschikt over licht, geluid en een DJ booth</button>
                        @elseif ($location_has_equipment == true) 
                            <h5>Je hebt bevestigd dat de locatie over licht, geluid en een DJ booth beschikt</h5>
                        @endif
                    @endif
            @endif

            {{-- Next and previous Step --}}
            <div class="col-12 mt-2">
                <div class="btn-group w-100" role="group" aria-label="Navigation">
                    <button type="button" wire:click="previousStep" class="btn btn-light w-50">< Terug naar contact info</button>
                    @if (($party_on_upper_floor == false OR ($party_on_upper_floor == true AND $upper_floor_elevator_available == true)) && ($show_type != 'Alleen DJ' OR $location_has_equipment == true))
                        <button type="button" wire:click="nextStep" class="btn btn-primary w-50">Gegevens controleren ></button>
                    @endif
                  </div>
            </div>

            @if ($show_type != null && $media_url != null)
                <div class="col-12 mt-2">
                    @if (($show_type != 'Alleen DJ' OR $location_has_equipment == true))
                        <div class="row mt-2">
                            <div class="col-12 col-sm-5 col-lg-3">
                                <img src="{{ $media_url }}" alt="Voorbeeld DJ Show" class="img-fluid">
                                <label for="" class="col-form-label col-form-label-lg">{{ $label }}</label>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif

        @if ($currentStep == 4)
            {{-- Contactgegevens --}}
            <div class="col-12 mb-2">
                <h2>Gegevens controle</h2>
            </div>
            
            <div class="col-12">
                <h3>Zijn de volgende gegevens correct?</h3>
            </div>
            <div class="col-12">
                <p class="h4" style="font-weight: 100">
                    <b class="h3">Datum</b><br>
                    Datum: <b>{{ date("d-m-Y", strtotime($party_date)); }}</b><br>
                    @if ($party_date_available !== null)
                            @if ($party_date_available == true)
                                <span class="text-success"><i class="fa fa-check"></i> De datum is beschikbaar</span>
                            @else
                                <span class="text-danger"><i class="fa fa-times"></i> De datum is helaas niet beschikbaar</span>
                            @endif
                            <br>
                    @endif
                    <button type="button" wire:click="StepSelection('1')" class="btn btn-primary">Aanpassen</button><br><br>

                    <b class="h3">Contactgegevens</b><br>
                    Naam: <b>{{ $name }}</b><br>
                    E-mailadres: <b>{{ $email }}</b> (Op dit e-mailadres ontvang je de prijsopgave)<br>
                    Telefoonnummer: <b>{{ $phone }}</b> (Via dit nummer neem ik contact met je op om de prijsopgave te bespreken)<br>
                    <button type="button" wire:click="StepSelection('2')" class="btn btn-primary">Aanpassen</button><br><br>

                    <b class="h3">Feest info</b><br>
                    Feesttijden: <b>{{ $start_time }} - {{ $end_time }}</b> @if($party_duration < 1) <span class="text-danger">(De tijden zijn ongeldig: Het feest moet minimaal 1 uur duren)</span> @endif @if($party_duration > 8) <span class="text-danger">(De tijden zijn ongeldig: Het feest mag maximaal 8 uur duren)</span> @endif<br>
                    Feesttype: <b>{{ $party_type }}</b><br>
                    Feestlocatie: <b>{{ $location }}</b><br>
                    Opmerking locatie: <b>{{ ($party_on_upper_floor == false ? 'Feest is op de begane grond zonder opstapjes of trappen' : 'Feest is op een verdieping of in de kelder, bereikbaar met een lift (minimaal 1x2m) zonder opstapjes of trappen') }}</b><br>
                    Aantal gasten: <b>{{ $guest_amount }}</b><br>
                    Show: <b>{{ $show_type }}</b> @if ($show_type == 'Alleen DJ') (De locatie beschikt over licht, geluid en een DJ booth) @endif<br>
                    <button type="button" wire:click="StepSelection('3')" class="btn btn-primary">Aanpassen</button>
                </p>
                @if($errors->any())
                    <p class="text-danger mt-2">Er zijn errors gevonden, los het volgende op:<br>
                        {!! implode('', $errors->all(':message</br>')) !!}
                    </p>
                @endif
            </div>
            {{-- Next and previous Step --}}
            <div class="col-12 mt-2">
                <div class="btn-group w-100" role="group" aria-label="Navigation">
                    <button type="button" wire:click="previousStep" class="btn btn-light w-50">< Terug naar feest info</button>
                    @if ($party_date_available !== null AND ($party_duration >= 1 AND $party_duration <= 8))
                        @if ($party_date_available == true)
                            <button type="submit" class="btn btn-primary w-50">Prijsopgave aanvragen ></button>
                        @endif
                    @endif
                  </div>
            </div>
        @endif

        @if ($currentStep == 5)
            @script
                <?php echo "<script>window.top.location.href = 'https://t-fooh.nl/prijsopgave-gelukt.html';</script>"; ?>
            @endscript
        @endif
    </div>
</form>
