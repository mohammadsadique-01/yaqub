@extends('backend.layouts.admin')

@section('title', 'Operator')

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
                                {{ isset($operator) ? 'Edit Operator' : 'Add Operator' }}
                            </h3>
                        </div>

                        <form method="POST" action="{{ isset($operator) ? route('operators.update', $operator->id) : route('operators.store') }}">
                            @csrf
                            @if(isset($operator))
                                @method('PUT')
                            @endif

                            <div class="card-body p-3">

                                {{-- Name --}}
                                <div class="form-group">
                                    <label class="small text-muted">Operator Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="name"
                                        value="{{ old('name', $operator->name ?? '') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        placeholder="Enter operator name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="form-group">
                                    <label class="small text-muted">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="phone"
                                        value="{{ old('phone', $operator->phone ?? '') }}"
                                        class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                        placeholder="Enter phone number">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="form-group">
                                    <label class="small text-muted">Address</label>
                                    <textarea name="address"
                                            rows="2"
                                            class="form-control form-control-sm"
                                            placeholder="Enter address">{{ old('address', $operator->address ?? '') }}</textarea>
                                </div>

                                {{-- Remark --}}
                                <div class="form-group mb-0">
                                    <label class="small text-muted">Remark</label>
                                    <textarea name="remark"
                                            rows="2"
                                            class="form-control form-control-sm"
                                            placeholder="Any remark...">{{ old('remark', $operator->remark ?? '') }}</textarea>
                                </div>

                            </div>

                            <div class="card-footer text-right py-2">
                                <button type="reset" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-undo"></i> Reset
                                </button>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i>
                                    {{ isset($operator) ? 'Update Operator' : 'Save Operator' }}
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
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Remark</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operators as $op)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $op->name }}</td>
                                    <td>{{ $op->phone }}</td>
                                    <td style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $op->address }}
                                    </td>
                                    <td>{{ $op->remark }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('operators.edit', $op->id) }}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('operators.destroy', $op->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this operator?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-danger">
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
