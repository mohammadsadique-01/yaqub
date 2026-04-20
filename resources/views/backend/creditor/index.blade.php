@extends('backend.layouts.admin')

@section('title', 'Creditor')

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
                <div class="col-sm-6">
                    <h1 class="m-0">Creditor</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button id="addCreditor" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Creditor
                    </button>

                    <button id="backToList" class="btn btn-secondary d-none">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div id="alertContainer" class="mt-2"></div>

            {{-- ================= TABLE SECTION ================= --}}
            <div id="tableSection">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list mr-1"></i> Creditor List</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @include('backend.creditor.table')
                    </div>
                </div>
            </div>

            {{-- ================= FORM SECTION ================= --}}
            <div id="formSection" class="d-none">
                <div class="card-header">
                    <h3 class="card-title">Add Creditor</h3>
                </div>

                <form id="form" method="POST" action="{{ route('creditor.store') }}">
                    @csrf
                    @include('backend.creditor.form')

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){

    $(document).on('click', '.add-site', function(){
        $('#site-container').append(`
            <div class="input-group mb-2">
                <input type="text" name="site_at[]" class="form-control" placeholder="Enter Site">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-site">-</button>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.remove-site', function(){
        $(this).closest('.input-group').remove();
    });

});
</script>
@endpush