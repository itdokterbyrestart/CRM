<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('order.create') }}" class="btn btn-primary">+ Nieuwe opdracht</a></h4>
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
                            @foreach ($order_statuses as $status)
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
                @if ($selectedCustomer)
                    <div class="col-12 mt-1">
                        <div class="alert alert-primary d-block p-1" role="alert">
                            <p class="text-center">
                                Er worden alleen opdrachten weergegeven voor een geselecteerde klant.
                            </p>
                            <div class="text-center">
                                <a class="btn btn-primary" href="{{ route('order.index') }}">
                                    Alle opdrachten weergeven
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($selectedOrder)
                    <div class="col-12 mt-1">
                        <div class="alert alert-primary d-block p-1" role="alert">
                            <p class="text-center">
                                Er worden alleen opdrachten weergegeven met een bepaalde ID.
                            </p>
                            <div class="text-center">
                                <a class="btn btn-primary" href="{{ route('order.index') }}">
                                    Alle opdrachten weergeven
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
                                        <th wire:click="sortBy('order_statuses.order')">Status @include('component.orderby.index' ,['field'=>'order_statuses.order'])</th>
                                        <th wire:click="sortBy('customers.name')">Klant @include('component.orderby.index' ,['field'=>'customers.name'])</th>
                                        @can('show hours')
                                            <th>Uren</th>
                                        @endcan
                                        @can('show profit')
                                            <th wire:click="sortBy('total_profit')">Winst @include('component.orderby.index' ,['field'=>'total_profit'])</th>
                                        @endcan
                                        <th wire:click="sortBy('updated_at')">Dagen geleden @include('component.orderby.index' ,['field'=>'updated_at'])</th>
                                        <th wire:click="sortBy('created_at')">Gemaakt op @include('component.orderby.index' ,['field'=>'created_at'])</th>
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>{{ $order->title }}</td>
                                            <td class="table-{{ $order->order_status->contextual_class }}">{{ $order->order_status->name }}</td>
                                            <td><a href="{{ route('customer.show', $order->customer) }}" target="_blank">{{ $order->customer->name . (($show_company_names == 1) ? ($order->customer->company ? ' (' . $order->customer->company . ')' : '') : '') }}</a></td>
                                            @can('show hours')
                                                <td>{{ number_format(($order->order_hours_sum_amount ?? 0),2, '.', ''); }}</td>
                                            @endcan
                                            @can('show profit')
                                                <td>€{{ $order->total_profit ?? 0.00 }}</td>
                                            @endcan
                                            @php
                                                $days_ago = Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($order->updated_at));
                                                if (in_array($order->order_status->name, ['Betaald, archief', 'Verloren', 'Gratis'])) {
                                                    $class = '';
                                                } elseif ($days_ago <= 7) {
                                                    $class = 'text-success';
                                                } elseif ($days_ago <= 14) {
                                                    $class = 'text-warning';
                                                } else {
                                                    $class = 'text-danger';
                                                }
                                            @endphp
                                            <td><span class="{{ $class }} {{ $class != '' ? 'h4' : '' }}">{{ $days_ago }}</span></td>
                                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ($order->order_status->name == 'Factureren')
                                                        <button class="btn btn-success" wire:click="createInvoiceConfirm('{{ $order->id }}')">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-primary" href="{{ route('order.show', $order->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (!in_array($order->order_status->name,['Betaald, archief','Verloren','Gratis']) OR Auth::user()->can('edit archived'))
                                                        <a class="btn btn-secondary" href="{{ route('order.edit', $order->id) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a> 
                                                    @endif
                                                    @if (in_array($order->order_status->name,['Uitnodiging gestuurd']))
                                                        <button class="btn btn-success" wire:click="sendAppointmentMailConfirmation('{{ $order->id }}','2')">
                                                            <i class="fas fa-calendar"></i>
                                                        </button>
                                                    @endif
                                                    @can('delete order')
                                                        @if ($order->invoices_count == 0)
                                                            <button class="btn btn-danger" wire:click="deleteConfirm('{{ $order->id }}')">
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
                                                <span class="font-weight-bold">Er zijn geen opdrachten gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


