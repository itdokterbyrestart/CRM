<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('quote.create') }}" class="btn btn-primary">+ Nieuwe offerte</a></h4>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="fas fa-chevron-down"></i></a></li>
                <li wire:click="$refresh"><a data-action="reload"><i class="fas fa-sync"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse show" aria-expanded="true">
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-12 col-md-4 mb-1">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>
                                Items per pagina:&nbsp;&nbsp;
                            </label>
                            <select class="form-control" wire:model.live="paginationItemsAmount">
                                <option selected value="15">15</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="0">Alles</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-1">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                            Sorteer op status
                        </button>
                        <ul class="dropdown-menu py-0">
                            @foreach ($quote_statuses as $status)
                                <li class="dropdown-item list-group-item-{{ $status->contextual_class }}">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" wire:model.live="selectedStatuses.{{ $status->id }}" id="selectedStatuses.{{ $status->id }}">
                                        <label for="selectedStatuses.{{ $status->id }}" class="form-check-label">{{ $status->name }}</label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>                            
                    </div>
                </div>
                <div class="col-12 col-md-4 align-right mb-1">
                    <input wire:model.live.debounce.250ms="search" type="text" class="form-control form-control-md" id="search" placeholder="Zoeken..." tabindex="0">
                </div>
                <div class="col-auto mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="show_dates" wire:model.live="show_dates">
                        <label class="form-check-label" for="show_dates">Datums weergeven</label>
                    </div>
                </div>
                <div class="col-auto mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="show_page_view_count" wire:model.live="show_page_view_count">
                        <label class="form-check-label" for="show_page_view_count">Weergave teller</label>
                    </div>
                </div>
                @if ($selectedCustomer)
                    <div class="col-12 mt-1">
                        <div class="alert alert-primary d-block p-1" role="alert">
                            <p class="text-center">
                                Er worden alleen offertes weergegeven voor een geselecteerde klant.
                            </p>
                            <div class="text-center">
                                <a class="btn btn-primary" href="{{ route('quote.index') }}">
                                    Alle offertes weergeven
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th wire:click="sortBy('title')">Titel @include('component.orderby.index' ,['field'=>'title'])</th>
                                        <th {{-- wire:click="sortBy('quote_status')" --}}>Status {{-- @include('component.orderby.index',['field'=>'quote_status']) --}}</th>
                                        <th {{-- wire:click="sortBy('customer')" --}}>Klant {{-- @include('component.orderby.index' ,['field'=>'customer']) --}}</th>
                                        @if ($show_dates == 1)
                                            <th wire:click="sortBy('sent_at')">Verstuurd op @include('component.orderby.index' ,['field'=>'sent_at'])</th>
                                            <th wire:click="sortBy('expiration_date')">Vervaldatum @include('component.orderby.index' ,['field'=>'expiration_date'])</th>
                                            <th wire:click="sortBy('created_at')">Gemaakt op @include('component.orderby.index' ,['field'=>'created_at'])</th>
                                        @endif
                                        @if ($show_page_view_count == 1)
                                            <th>Weergaves</th>
                                        @endif
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($quotes as $quote)
                                    @php
                                        $quote_expiration_date = Carbon\Carbon::parse($quote->expiration_date);
                                        $today = Carbon\Carbon::now();

                                        $table_color = ($quote_expiration_date->subDays(3)->startOfDay() > $today->startOfDay() ? 'table-success' : ($quote_expiration_date->startOfDay() == $today->startOfDay() ? 'table-danger' : ($quote_expiration_date->subDays(3)->startOfDay() <= $today->startOfDay() ? 'class="table-warning"' : '')))
                                    @endphp
                                        <tr>
                                            <td>{{ $quote->title }}</td>
                                            <td class="table-{{ $quote->quote_statuses->first()->contextual_class }}">{{ $quote->quote_statuses->first()->name }}</td>
                                            <td><a href="{{ route('customer.show', $quote->customer) }}" target="_blank">{{ $quote->customer->name . (($show_company_names == 1) ? ($quote->customer->company ? ' (' . $quote->customer->company . ')' : '') : '') }}</a></td>
                                            @if ($show_dates == 1)
                                                <td>{{ $quote->sent_at !== null ? (date("d-m-Y",strtotime($quote->sent_at))) : 'Niet verstuurd' }}</td>
                                                <td @if (isset($quote->expiration_date) && ($quote->quote_statuses->first()->name == 'Wachten op klant')) class="{{ $table_color }}"
                                                @endif>{{  date("d-m-Y",strtotime($quote->expiration_date)) == '01-01-1970' ? 'Geen' : date("d-m-Y",strtotime($quote->expiration_date)) }}</td>
                                                <td>{{ date("d-m-Y",strtotime($quote->created_at)) }}</td>
                                            @endif
                                            @if ($show_page_view_count == 1)
                                                <td>{{ views($quote)->count() }}</td>
                                            @endif
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-success" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                    <ul class="dropdown-menu py-0">
                                                        <a href="{{ route('quote.customer.show', $quote) }}" target="_blank">
                                                            <li class="dropdown-item">
                                                                Offerte bekijken
                                                            </li>
                                                        </a>
                                                        <li class="dropdown-item" wire:click="send_quote_to_customer_confirm('{{ $quote->id }}')">
                                                            Offerte versturen
                                                        </li>
                                                        <li class="dropdown-item" wire:click="clone('{{ $quote->id }}')">
                                                            Offerte kopiëren
                                                        </li>
                                                    </ul>
                                                    @if ($quote->order_id !== NULL OR $quote->quote_statuses->first()->id === $status_convert_to_order->id)
                                                        <button class="btn btn-secondary" wire:click="get_order('{{ $quote->id }}')">
                                                            <i class="fas fa-euro-sign"></i>
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-primary" href="{{ route('quote.show', $quote->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (!in_array($quote->quote_statuses->first()->name,['Akkoord','Verloren']) OR Auth::user()->can('edit quote archived'))
                                                        <a class="btn btn-secondary" href="{{ route('quote.edit', $quote->id) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @can('delete quote')
                                                        <button class="btn btn-danger" wire:click="deleteConfirm('{{ $quote->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button> 
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <span class="font-weight-bold">Er zijn geen offertes gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $quotes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


