@php
    $viewUrl = route('drilling.show', $row->id);
@endphp

<button class="btn btn-info btn-xs viewDrilling" data-url="{{ $viewUrl }}">
    <i class="fas fa-eye"></i>
</button>

<button class="btn btn-primary btn-xs editDrilling" data-id="{{ $row->id }}">
    <i class="fas fa-pen"></i>
</button>

<button class="btn btn-danger btn-xs deleteDrilling" data-id="{{ $row->id }}">
    <i class="fas fa-trash"></i>
</button>
