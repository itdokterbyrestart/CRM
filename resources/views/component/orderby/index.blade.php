@if ($sortColumn !== $field)
    <i class="text-muted fas fa-sort"></i>
@elseif ($sortDirection === 'ASC')
    <i class="text-primary fas fa-sort-up"></i>
@else
    <i class="text-primary fas fa-sort-down"></i>
@endif
