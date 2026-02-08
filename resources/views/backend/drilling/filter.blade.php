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
                <select id="filterDebitor" class="form-control select2" multiple="multiple" data-placeholder="Select Debitor" style="width: 100%;">
                    @foreach($debitors as $debitor)
                        <option value="{{ $debitor->id }}">{{ $debitor->account_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Site --}}
            <div class="col-md-3">
                <label class="small text-muted">Site</label>
                <select id="filterSite" class="form-control select2" multiple="multiple" data-placeholder="Select Sites">
                    <option value="">All Sites</option>
                </select>
            </div>

            {{-- Operator --}}
            <div class="col-md-3">
                <label class="small text-muted">Operator</label>
                <select id="filterOperator" class="form-control select2" multiple="multiple" data-placeholder="Select Operator">
                    @foreach($operators as $operator)
                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                    @endforeach
                </select>
            </div>



            {{-- Date Range --}}
            <div class="col-md-3">
                <div class="form-group">
                    <label>Date range:</label>

                    <div class="input-group">
                    <button type="button" class="btn btn-default" id="daterange-btn">
                        <i class="far fa-calendar-alt"></i>
                        <span>Date range picker</span>
                        <i class="fas fa-caret-down"></i>
                    </button>
                    </div>

                    <input type="hidden" id="startDate">
                    <input type="hidden" id="endDate">
                </div>
            </div>




{{-- 
            <div class="col-md-2">
                <label class="small text-muted">From Date</label>
                <input type="date" id="filterFromDate" class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label class="small text-muted">To Date</label>
                <input type="date" id="filterToDate" class="form-control form-control-sm">
            </div> --}}

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
