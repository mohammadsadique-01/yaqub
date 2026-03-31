@extends('backend.layouts.admin')

@section('content')
<div class="content-header">

<div class="container">
    @if(session('success')) 
      <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div> 
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error')) 
      <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
      </div> 
    @endif 
    {{-- ================= ADD FORM ================= --}}
        <div class="card card-navy">
            <div class="card-header">
                <h3 class="card-title text-sm">
                <i class="fas fa-map mr-1"></i>
                Add Location + Villages
                </h3>
            </div>
            <div class="card-body">
                <form id="locationForm" action="{{ route('locations.store') }}" method="POST">
                    @csrf

                    {{-- hidden for edit --}}
                    <input type="hidden" name="id" id="location_id">

                    <div class="row">
                        {{-- STATE --}}
                        <div class="col-md-4">
                            <label>State</label>
                            <input type="text" id="state" name="state" class="form-control"
                                value="{{ old('state') }}" required>
                        </div>

                        {{-- DISTRICT --}}
                        <div class="col-md-4">
                            <label>District</label>
                            <input type="text" id="district" name="district" class="form-control"
                                value="{{ old('district') }}" required>
                        </div>

                        {{-- STATE CODE --}}
                        <div class="col-md-4">
                            <label>State Code</label>
                            <input type="text" id="state_code" name="state_code" class="form-control"
                                value="{{ old('state_code') }}">
                        </div>
                    </div>

                    <hr>

                    {{-- VILLAGES --}}
                    <label>Village Name</label>
                    <div id="village-wrapper">
                        @if(old('villages'))
                            @foreach(old('villages') as $index => $village)
                                <div class="row village-row mt-2">
                                    <div class="col-md-5">
                                        <input type="text" name="villages[]" class="form-control"
                                            value="{{ $village }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-success addVillage">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger removeVillage">-</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Default first row --}}
                            <div class="row village-row">
                                <div class="col-md-5">
                                    <input type="text" name="villages[]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success addVillage">+</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <br>

                    <button class="btn btn-primary" id="submitBtn">Save</button>

                </form>
            </div>
        </div>

    <br>
    {{-- ================= LIST TABLE ================= --}}
    <div class="card">
        <div class="card-header">
            <h4>Village List</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>State</th>
                        <th>District</th>
                        <th>State Code</th>
                        <th>Villages</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $location->state }}</td>

                            <td>{{ $location->district }}</td>

                            <td>{{ $location->state_code }}</td>

                            <td>
                                {{ $location->villages->pluck('village_name')->implode(', ') }}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm editBtn"
                                    data-id="{{ $location->id }}"
                                    data-state="{{ $location->state }}"
                                    data-district="{{ $location->district }}"
                                    data-code="{{ $location->state_code }}"
                                    data-villages='@json($location->villages->pluck("village_name"))'>
                                    Edit
                                </button>
                               
                                <form action="{{ route('locations.destroy', $location->id) }}" 
                                    method="POST" 
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this location and villages?')">
                                        Delete
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

@endsection


@push('scripts')

<script>

$(document).on('click','.addVillage',function(){

    $('#village-wrapper').append(`

        <div class="row village-row mt-2">

            <div class="col-md-5">
                <input type="text" name="villages[]" class="form-control" placeholder="Village Name" required>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeVillage">-</button>
            </div>

        </div>

    `);

});

$(document).on('click','.removeVillage',function(){
    $(this).closest('.village-row').remove();
});

$(document).on('click','.editBtn',function(){

    let id = $(this).data('id');
    let state = $(this).data('state');
    let district = $(this).data('district');
    let code = $(this).data('code');
    let villages = $(this).data('villages');

    // fill fields
    $('#location_id').val(id);
    $('#state').val(state);
    $('#district').val(district);
    $('#state_code').val(code);

    // change form action
    let url = window.APP.routes.locationUpdate.replace(':id', id);
    $('#locationForm').attr('action', url);

    // add PUT method
    if ($('#methodField').length === 0) {
        $('#locationForm').append('<input type="hidden" name="_method" value="PUT" id="methodField">');
    }

    $('#submitBtn').text('Update');

    // clear villages
    $('#village-wrapper').html('');

    // 🔥 ADD villages properly
    if (villages && villages.length > 0) {

        villages.forEach(function(v, index){

            let button = '';

            // 👉 only first row gets + button
            if(index === 0){
                button = `<button type="button" class="btn btn-success addVillage">+</button>`;
            } else {
                button = `<button type="button" class="btn btn-danger removeVillage">-</button>`;
            }

            $('#village-wrapper').append(`
                <div class="row village-row mt-2">
                    <div class="col-md-5">
                        <input type="text" name="villages[]" class="form-control" value="${v}" required>
                    </div>
                    <div class="col-md-2">
                        ${button}
                    </div>
                </div>
            `);

        });

    } else {
        $('#village-wrapper').append(`
            <div class="row village-row mt-2">
                <div class="col-md-5">
                    <input type="text" name="villages[]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-success addVillage">+</button>
                </div>
            </div>
        `);
    }

});


</script>

@endpush