<div class="row">
    @if ($quote_status->name === 'Akkoord')
        <div class="col-12 col-sm-6">
            <button class="btn btn-success" style="pointer-events: none;">Akkoord</button>
        </div>
        <div class="col-12 col-sm-6 mt-1 mt-md-0">
            <p>
                Datum akkoord:&nbsp;<b>{{ $quote_status->pivot->created_at->format('d-m-Y') }}</b>
            </p>
        </div>	
        <div class="col-12 mt-1 mt-sm-2">
            <h4>
                Bericht:
            </h4>
            <p>
                {{ $quote_status->pivot->comment ?? 'Er is geen bericht opgegeven' }}
            </p>
            @if (count($selected_products) > 0)
                <h4>
                    Gekozen producten:
                </h4>
                <div class="alert alert-info d-sm-none p-1" role="alert">
                    <i class="fas fa-info-circle"></i>
                    De tabel is horizontaal scrollbaar!
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Prijs</th>
                                <th>Aantal</th>
                                <th>Product totaal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selected_products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>€{{ $product->price_customer_including_tax }}</td>
                                    <td>{{ $product->amount }}</td>
                                    <td>€{{ $product->total_price_customer_including_tax }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"><b>Totaal  {{ $quote->prices_exclude_tax == 0 ? 'inclusief' : 'exclusief' }} BTW</b></td>
                                <td><b>€{{ $selected_products_total }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @elseif ($quote_status->name === 'Geweigerd' OR $quote_status->name === 'Verloren')
    <div class="col-12 col-sm-6">
        <button class="btn btn-danger" style="pointer-events: none;">Geweigerd</button>
    </div>
    <div class="col-12 col-sm-6 mt-1 mt-md-0">
        <p>
            Datum geweigerd:&nbsp;<b>{{ $quote_status->pivot->created_at->format('d-m-Y') }}</b>
        </p>
    </div>	
    <div class="col-12 mt-1 mt-sm-2">
        <h4>
            Bericht:
        </h4>
        <p>
            {{ $quote_status->pivot->comment ?? 'Er is geen bericht opgegeven' }}
        </p>
    </div>
    @elseif ($quote_status->name === 'Wachten op klant')
        <div class="col-12 col-md-6">
            <button class="btn btn-outline-success mb-1" style="pointer-events: none;">Geldig</button>
            <br>
            <div class="btn-group">
                <button class="btn btn-success" wire:click="acceptOrDenyQuote(1)">Offerte accepteren</button>
                <button class="btn btn-danger" wire:click="acceptOrDenyQuote(0)">Offerte weigeren</button>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-1 mt-md-0">
            <p class="h5 font-weight-normal">
                De offerte is geldig tot:&nbsp;<b>{{ $expiration_date }}</b>
            </p>
        </div>
    @elseif ($quote_status->name === 'Verlopen')
        <div class="col-12 col-sm-6">
            <button class="btn btn-warning" style="pointer-events: none;">Verlopen</button>
        </div>
        <div class="col-12">
            <p class="mt-1 h5 font-weight-normal">
                Je kunt de offerte bekijken, maar deze is verlopen. Neem voor actuele prijzen <a href="{{ $link_to_contact_page }}">contact</a> op.<br>
                De offerte was geldig tot:&nbsp;<b>{{ $expiration_date }}</b>
            </p>
        </div>
    @else
    <div class="col-12">
        <button class="btn btn-warning" style="pointer-events: none;">Ongeldig</button>
        <p class="mt-1">Je kunt de offerte bekijken, maar deze is ongeldig. Als de offerte langer dan 7 dagen ongeldig is, neem dan <a href="http://deitdokter.nl/contact.html">contact</a> op.</p>
    </div>
    @endif
</div>