<!-- Uren afgelopen week -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Overzicht uren afgelopen week</h4>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="fas fa-chevron-down"></i></a></li>
                    <li wire:click="$refresh"><a data-action="reload"><i class="fas fa-sync"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content collapse show" aria-expanded="true">
            <div class="card-body">
                {{-- <div class="row mb-1">
                    <div class="col-12 col-md-4">
                        <div class="form-inline">
                            <div class="form-group">
                                <label>
                                    Datum tussen&nbsp;&nbsp;
                                </label>
                                <input type="date" class="form-control" wire:model.blur="hour_start_date">
                                <label>
                                    &nbsp;&nbsp;en&nbsp;&nbsp;
                                </label>
                                <input type="date" class="form-control" wire:model.blur="hour_end_date">
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Opdracht</th>
                                <th>Klant</th>
                                <th>Datum</th>
                                <th>Start</th>
                                <th>Eind</th>
                                <th>Uren</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderhours as $orderhour)
                                <tr>
                                    <td>{{ $orderhour->order->title }}</td>
                                    <td>{{ $orderhour->order->customer->name }}</td>
                                    <td>{{ (date("d-m-Y",strtotime($orderhour->date))) }}</td>
                                    <td>{{ (date("H:i",strtotime($orderhour->start_time))) }}</td>
                                    <td>{{ (date("H:i",strtotime($orderhour->end_time))) }}</td>
                                    <td>{{ $orderhour->amount }}</td>
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
            </div>
        </div>
    </div>
</div>
<!-- /Uren afgelopen week-->