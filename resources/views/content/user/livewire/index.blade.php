<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('user.create') }}" class="btn btn-primary">+ Nieuwe gebruiker</a></h4>
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
                                        <th wire:click="sortBy('mail_report')">Rapportage @include('component.orderby.index' ,['field'=>'mail_report'])</th>
                                        <th wire:click="sortBy('updated_at')">Aangepast op @include('component.orderby.index' ,['field'=>'updated_at'])</th>
                                        <th wire:click="sortBy('created_at')">Gemaakt op @include('component.orderby.index' ,['field'=>'created_at'])</th>
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->mail_report == 1 ? 'Ja' : 'Nee' }}</td>
                                            <td>{{ $user->updated_at->format('d-m-Y | H:i') }}</td>
                                            <td>{{ $user->created_at->format('d-m-Y | H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-primary" href="{{ route('user.show', $user->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-secondary" href="{{ route('user.edit', $user->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @can('delete user')
                                                        @if ($user->id != Auth::user()->id)
                                                            <button class="btn btn-danger" wire:click="deleteConfirm('{{ $user->id }}')">
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
                                                <span class="font-weight-bold">Er zijn geen gebruikers gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


