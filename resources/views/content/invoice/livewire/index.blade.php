<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('invoice.create') }}" class="btn btn-primary">+ Nieuwe factuur</a></h4>
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
                            @foreach ($invoice_statuses as $status)
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
                        <input class="form-check-input" type="checkbox" id="show_prices" wire:model.live="show_prices">
                        <label class="form-check-label" for="show_prices">Prijzen weergeven</label>
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
                                Er worden alleen facturen weergegeven voor een geselecteerde klant.
                            </p>
                            <div class="text-center">
                                <a class="btn btn-primary" href="{{ route('invoice.index') }}">
                                    Alle facturen weergeven
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($selectedInvoice)
                    <div class="col-12 mt-1">
                        <div class="alert alert-primary d-block p-1" role="alert">
                            <p class="text-center">
                                Er worden alleen facturen weergegeven met een bepaalde ID.
                            </p>
                            <div class="text-center">
                                <a class="btn btn-primary" href="{{ route('invoice.index') }}">
                                    Alle facturen weergeven
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
                                        <th wire:click="sortBy('invoice_number')">Nummer @include('component.orderby.index' ,['field'=>'invoice_number'])</th>
                                        <th>Status</th>
                                        <th>Klant</th>
                                        @if ($show_dates == 1)
                                            <th wire:click="sortBy('invoice_date')">Datum @include('component.orderby.index' ,['field'=>'invoice_date'])</th>
                                            <th wire:click="sortBy('expiration_date')">Vervalt @include('component.orderby.index' ,['field'=>'expiration_date'])</th>
                                        @endif
                                        @if ($show_prices == 1)
                                            <th wire:click="sortBy('total_price_customer_excluding_tax')">€ excl. BTW @include('component.orderby.index' ,['field'=>'total_price_customer_excluding_tax'])</th>
                                            <th wire:click="sortBy('total_price_customer_excluding_tax')">€ BTW @include('component.orderby.index' ,['field'=>'total_tax_amount'])</th>
                                            <th wire:click="sortBy('total_price_customer_including_tax')">€ incl. BTW @include('component.orderby.index' ,['field'=>'total_price_customer_including_tax'])</th>
                                        @endif
                                        
                                        @if ($show_page_view_count == 1)
                                            <th>Weergaves</th>
                                        @endif
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td class="table-{{ $invoice->invoice_statuses->first()->contextual_class }}">{{ $invoice->invoice_statuses->first()->name }}</td>
                                            <td><a href="{{ route('customer.show', $invoice->order->customer) }}" target="_blank">{{ $invoice->order->customer->name . (($show_company_names == 1) ? ($invoice->order->customer->company ? ' (' . $invoice->order->customer->company . ')' : '') : '') }}</a></td>
                                            @if ($show_dates == 1)
                                                <td>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                                <td>{{ Carbon\Carbon::parse($invoice->expiration_date)->format('d-m-Y') }}</td>
                                            @endif
                                            @if ($show_prices == 1)
                                                <td>{{ number_format($invoice->total_price_customer_excluding_tax,2,',','') }}</td>
                                                <td>{{ number_format($invoice->total_tax_amount,2,',','') }}</td>
                                                <td>{{ number_format($invoice->total_price_customer_including_tax,2,',','') }}</td>
                                            @endif
                                            @if ($show_page_view_count == 1)
                                                <td>{{ views($invoice)->count() }}</td>
                                            @endif
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ($invoice->media->count() == 0)
                                                        <button class="btn btn-success" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
                                                        <ul class="dropdown-menu py-0">
                                                            <a href="{{ route('invoice.customer.show', $invoice->id) }}" target="_blank">
                                                                <li class="dropdown-item">
                                                                    Factuur bekijken
                                                                </li>
                                                            </a>
                                                            @if ($invoice->invoice_statuses->first()->name == 'Verlopen' || $invoice->invoice_statuses->first()->name == 'Herinnering 1' || $invoice->invoice_statuses->first()->name == 'Herinnering 2' || $invoice->invoice_statuses->first()->name == 'Herinnering 3')
                                                                <a href="{{ route('invoice.preview.mail_reminder', $invoice) }}" target="_blank">
                                                                    <li class="dropdown-item">
                                                                        Email bekijken
                                                                    </li>
                                                                </a>
                                                                <li class="dropdown-item" wire:click="send_reminder_to_customer('{{ $invoice->id }}')">
                                                                    Herinnering versturen
                                                                </li>
                                                            @else
                                                                <a href="{{ route('invoice.preview.mail', $invoice) }}" target="_blank">
                                                                    <li class="dropdown-item">
                                                                        Email bekijken
                                                                    </li>
                                                                </a>
                                                                <li class="dropdown-item" wire:click="send_invoice_to_customer_confirm('{{ $invoice->id }}')">
                                                                    Factuur versturen
                                                                </li>
                                                            @endif
                                                            <a href="{{ route('invoice.customer.show', $invoice->id) }}" onclick="window.open(this.href).print(); return false">
                                                                <li class="dropdown-item">
                                                                    Print factuur
                                                                </li>
                                                            </a>
                                                            <li class="dropdown-item" wire:click="showFileItem('{{ $invoice->id }}')">
                                                                Docx file genereren
                                                            </li>
                                                        </ul>
                                                    @else
                                                        <button class="btn btn-success" wire:click="showFileItem('{{ $invoice->id }}')">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-primary" href="{{ route('invoice.show', $invoice->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (!in_array($invoice->invoice_statuses->first()->name,['Betaald','Geweigerd']) OR Auth::user()->can('edit archived'))
                                                        <a class="btn btn-secondary" href="{{ route('invoice.edit', $invoice->id) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @can('delete invoice')
                                                        @if (!in_array($invoice->invoice_statuses->first()->name,['Betaald']))
                                                            <button class="btn btn-danger" wire:click="deleteConfirm('{{ $invoice->id }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button> 
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <span class="font-weight-bold">Er zijn geen facturen gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


