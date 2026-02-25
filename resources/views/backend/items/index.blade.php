@extends('backend.layouts.admin')

@section('title', 'Items')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="row mb-2">
                <div class="col-sm-5">
                    <div class="card card-outline card-primary">
                        <div class="card-header py-2">
                            <h3 class="card-title text-sm">
                                <i class="fas fa-user-cog mr-1"></i>
                                {{ isset($item) ? 'Edit Item' : 'Add Item' }}
                            </h3>
                        </div>

                        <form method="POST" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}">
                            @csrf
                            @if(isset($item))
                                @method('PUT')
                            @endif

                            <div class="card-body p-3">

                                {{-- Name --}}
                                <div class="form-group">
                                    <label class="small text-muted">Item Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="name"
                                        value="{{ old('name', $item->name ?? '') }}"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        placeholder="Enter item name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="small text-muted">HSN / SAC <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="hsn_sac"
                                        value="{{ old('hsn_sac', $item->hsn_sac ?? '') }}"
                                        class="form-control form-control-lg @error('hsn_sac') is-invalid @enderror"
                                        placeholder="Enter HSN / SAC">
                                    @error('hsn_sac')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="small text-muted">Unit <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="unit"
                                        value="{{ old('unit', $item->unit ?? '') }}"
                                        class="form-control form-control-lg @error('unit') is-invalid @enderror"
                                        placeholder="Enter unit">
                                    @error('unit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <div class="card-footer text-right py-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i>
                                    {{ isset($item) ? 'Update Item' : 'Save Item' }}
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="table-responsive" style="max-height: 380px;">
                        <table class="table table-bordered table-sm datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>HSN / SAC</th>
                                    <th>Unit</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->hsn_sac }}</td>
                                    <td>{{ $row->unit }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('items.edit', $row->id) }}" class="btn btn-info btn-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('items.destroy', $row->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs" onclick="return confirm('Delete this item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
