<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('customer.create') }}" class="btn btn-primary">+ Nieuwe klant</a></h4>
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
                <div class="col-12 col-sm-6 mb-1">
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
                <div class="col-12 col-sm-6 align-right mb-1">
                    <input wire:model.live.debounce.250ms="search" type="text" class="form-control form-control-md" id="search" placeholder="Zoeken..." tabindex="0">
                </div>
                <form wire:submit="update_with_order_created_at" class="col-12 mt-1">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <label for="with_order_created_at" class="col-form-label col-form-label-md mr-1">Met opdrachten gemaakt later dan:</label>
                            <input wire:model.live="with_order_created_at" type="date" class="form-control form-control-md @error('with_order_created_at') is-invalid @enderror" id="with_order_created_at">
                            @error('with_order_created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button wire:target="update_with_order_created_at" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary mr-1" tabindex="0">
                                <span wire:target="update_with_order_created_at" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Opslaan
                            </button>
                            <button wire:click="reset_with_order_created_at" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-danger" tabindex="0">
                                <span wire:target="reset_with_order_created_at" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Reset
                            </button>
                        </div>
                    </div>
				</form>
            </div>
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th wire:click="sortBy('name')">Naam @include('component.orderby.index' ,['field'=>'name'])</th>
                                        <th wire:click="sortBy('email')">Email @include('component.orderby.index' ,['field'=>'email'])</th>
                                        <th wire:click="sortBy('phone')">Telefoon @include('component.orderby.index' ,['field'=>'phone'])</th>
                                        <th wire:click="sortBy('street')">Adres @include('component.orderby.index' ,['field'=>'street'])</th>
                                        <th wire:click="sortBy('updated_at')">Aangepast op @include('component.orderby.index' ,['field'=>'updated_at'])</th>
                                        <th wire:click="sortBy('created_at')">Gemaakt op @include('component.orderby.index' ,['field'=>'created_at'])</th>
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr wire:key="{{ 'customer_' . $customer->id }}">
                                            <td>{{ $customer->company ? $customer->name . ' (' . $customer->company . ')' : $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->street }} {{ $customer->number }}<br>{{ $customer->postal_code }} {{ $customer->place_name }}</td>
                                            <td>{{ $customer->updated_at->format('d-m-Y | H:i') }}</td>
                                            <td>{{ $customer->created_at->format('d-m-Y | H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-secondary" href="{{ route('order.index', ['customer_id' => $customer->id],) }}">
                                                        <i class="fas fa-euro-sign"></i>
                                                    </a>

                                                    <a class="btn btn-primary" href="{{ route('customer.show', $customer->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-secondary" href="{{ route('customer.edit', $customer->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if ($party_fields_enabled == 0)
                                                    <button class="btn btn-success" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-calendar"></i>
                                                    </button>
                                                    <ul class="dropdown-menu py-0">
                                                        <li class="dropdown-item" wire:click="sendAppointmentMailConfirmation('{{ $customer->id }}','1')">
                                                            Afspraak maken
                                                        </li>
                                                        <li class="dropdown-item" wire:click="sendAppointmentMailConfirmation('{{ $customer->id }}','2')">
                                                            APK
                                                        </li>
                                                    </ul>
                                                    @endif
                                                    @can('delete customer')
                                                        <button class="btn btn-danger" wire:click="deleteConfirm('{{ $customer->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <span class="font-weight-bold">Er zijn geen klanten gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


