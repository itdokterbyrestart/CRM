<div class="card">
    <div class="card-header">
        <h4 class="card-title"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalForm">+ Nieuw</button></h4>
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
                <div class="col-12 col-md-4">
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
                <div class="col-12 col-md-4">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                            Sorteer op service
                        </button>
                        <ul class="dropdown-menu py-0">
                            @foreach ($services as $service)
                                <li class="dropdown-item">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" wire:model.live="selectedServices.{{ $service->id }}" id="selectedServices.{{ $service->id }}">
                                        <label for="selectedServices.{{ $service->id }}" class="form-check-label">{{ $service->name }}</label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-4 align-right">
                    <input wire:model.live.debounce.250ms="search" type="text" class="form-control form-control-md" id="search" placeholder="Zoeken..." tabindex="0">
                </div>
            </div>
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th wire:click="sortBy('customer_id')">Klant @include('component.orderby.index' ,['field'=>'customer_id'])</th>
                                        <th wire:click="sortBy('month')">Maand @include('component.orderby.index' ,['field'=>'month'])</th>
                                        <th wire:click="sortBy('service_id')">Type @include('component.orderby.index' ,['field'=>'service_id'])</th>
                                        <th wire:click="sortBy('description')">Beschrijving @include('component.orderby.index' ,['field'=>'description'])</th>
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($models as $model)
                                            <tr>
                                                <td>{{ $model->customer->name }}</td>
                                                <td>{{ __('dates.' . DateTime::createFromFormat('!m', $model->month)->format('F')) }}</td>
                                                <td>{{ $model->service->name }}</td>
                                                <td>{{ $model->description }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if (in_array($model->service->name,['APK op afstand','APK aan huis']))
                                                            <button class="btn btn-success" wire:click="sendInvitationMailConfirmation('{{ $model->id }}')">
                                                                <i class="fas fa-envelope"></i>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-primary" wire:click="showItem('{{ $model->customer->id }}')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-secondary" wire:click="updateItem('{{ $model->customer->id }}')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger" wire:click="deleteConfirm('{{ $model->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <span class="font-weight-bold">Er is geen data gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $models->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


