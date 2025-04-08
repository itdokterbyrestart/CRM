<div>
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-1">
            <thead>
                <tr>
                    <th></th>
                    <th>Bestandsnaam</th>
                    <th>Actie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($files as $file)
                    <tr>
                        <td>
                            @if ($file->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                <i class="fas fa-file-word"></i>
                            @elseif ($file->mime_type == 'application/pdf')
                                <i class="fas fa-file-pdf"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </td>
                        <td>{{ $file->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-primary" wire:click="downloadFile('{{ $file->id }}')">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-danger" wire:click="confirmDelete('{{ $file->id }}', '{{ $file->model_id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <span class="font-weight-bold">Er zijn geen bestanden gevonden</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if (count($files) == 0)
        <button class="btn btn-primary" wire:click="createInvoiceFile">
            <i class="fas fa-sync"></i> Genereer .docx
        </button>
    @endif
    
</div>