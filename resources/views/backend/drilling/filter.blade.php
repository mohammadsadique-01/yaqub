{{-- ================= FILTER SECTION ================= --}}
<div id="filterCard" class="card card-outline card-info mb-2">
    <div class="card-header py-2">
        <h3 class="card-title text-sm">
            <i class="fas fa-filter mr-1"></i> Filter Reports
        </h3>
    </div>

    <div class="card-body py-2">
        <div class="row">

            {{-- Debitor --}}
            <div class="col-md-3">
                <label class="small text-muted">Debitor</label>
                <select class="form-control select2 debitorSelect" multiple="multiple" data-placeholder="Select Debitor" style="width: 100%;">
                    <option value="">All Debitors</option>
                    @foreach($debitors as $debitor)
                        <option value="{{ $debitor->id }}">{{ $debitor->account_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Site --}}
            <div class="col-md-3">
                <label class="small text-muted">Site</label>
                <select class="form-control select2 siteSelect" multiple="multiple" data-placeholder="Select Sites">
                    <option value="">All Sites</option>
                </select>
            </div>

            {{-- Operator --}}
            <div class="col-md-2">
                <label class="small text-muted">Operator</label>
                <select id="filterOperator" class="form-control select2" multiple="multiple" data-placeholder="Select Operator">
                    <option value="">All Operators</option>
                    @foreach($operators as $operator)
                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- From Date --}}
            <div class="col-md-2">
                <label class="small text-muted">From Date</label>
                <input type="date" id="filterFromDate" class="form-control form-control-sm">
            </div>

            {{-- To Date --}}
            <div class="col-md-2">
                <label class="small text-muted">To Date</label>
                <input type="date" id="filterToDate" class="form-control form-control-sm">
            </div>

        </div>

        <div class="row mt-2">
            <div class="col text-right">
                <button id="applyFilter" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Apply
                </button>

                <button id="resetFilter" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>
