@extends('backend.layouts.admin') @section('title', 'Operator') @push('styles') <style>
  .attachment-block .operator-card {
    border-bottom: aliceblue !important;
  }

  .operators-img {
    box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px,
      rgba(0, 0, 0, 0.12) 0px -12px 30px,
      rgba(0, 0, 0, 0.12) 0px 4px 6px,
      rgba(0, 0, 0, 0.17) 0px 12px 13px,
      rgba(0, 0, 0, 0.09) 0px -3px 5px;
    width: 65px;
    height: 65px;
    object-fit: cover;
    border-radius: 50%;
  }

  .user-block .description {
    font-size: 17px;
  }

  .preview-img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
    margin-top: 10px;
  }
</style>
<style>
  .image-upload-wrapper {
    position: relative;
    display: inline-block;
    cursor: pointer;
  }

  .profile-img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dee2e6;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
  }

  .upload-icon {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #007bff;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    border: 2px solid white;
  }
</style> @endpush @section('content') 
<div class="content-header">
  <div class="container-fluid"> 
    @if(session('success')) 
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div> 
    @endif 
    @if(session('error')) 
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
      </div> 
    @endif 
    <div class="row mb-2">
      {{-- LEFT FORM --}}
      <div class="col-sm-5">
        <div class="card card-navy">
          <div class="card-header">
            <h3 class="card-title text-sm">
              <i class="fas fa-user-cog mr-1"></i>
              {{ isset($operator) ? 'Edit Operator' : 'Add Operator' }}
            </h3>
          </div>
          <form enctype="multipart/form-data" method="POST" action="{{ isset($operator) ? route('operators.update',$operator->id) : route('operators.store') }}"> @csrf @if(isset($operator)) @method('PUT') @endif <div class="card-body p-3">
            <div class="form-group">
              <div class="row">
                {{-- NAME INPUT 70% --}}
                <div class="col-md-8">
                  <label class="small text-muted">
                    Operator Name <span class="text-danger">*</span>
                  </label>

                  <input type="text"
                    name="name"
                    value="{{ old('name',$operator->name ?? '') }}"
                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                    placeholder="Enter operator name">

                  @error('name')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-4 text-center">
                  <label class="small text-muted d-block">Photo</label>
                  <div class="image-upload-wrapper">
                    <img id="imagePreview"
                      class="profile-img"
                      src="{{ isset($operator) && $operator->image ? asset('storage/operators/'.$operator->image) : asset('custom/logo.png') }}">

                    <label for="imageInput" class="upload-icon">
                      <i class="fas fa-camera"></i>
                    </label>

                    <input type="file"
                      id="imageInput"
                      name="image"
                      accept="image/*"
                      style="display:none;">
                  </div>

                  @error('image')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
              {{-- <div class="form-group">
                <label class="small text-muted"> Operator Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name',$operator->name ?? '') }}" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Enter operator name"> @error('name') <small class="text-danger">{{ $message }}</small> @enderror
              </div> --}}
              <div class="form-group">
                <label class="small text-muted"> Phone Number <span class="text-danger">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone',$operator->phone ?? '') }}" class="form-control form-control-lg @error('phone') is-invalid @enderror" placeholder="Enter phone number"> @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
              {{-- IMAGE UPLOAD --}}
              {{-- <div class="form-group text-center">
                <label class="small text-muted d-block">Operator Image</label>
                <div class="image-upload-wrapper">
                  <img id="imagePreview" class="profile-img" src="{{ isset($operator) && $operator->image ? asset('storage/operators/'.$operator->image) : asset('custom/logo.png') }}">
                  <label for="imageInput" class="upload-icon">
                    <i class="fas fa-camera"></i>
                  </label>
                  <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;">
                </div>
                <small class="text-muted">Click image to upload</small> @error('image') <div class="text-danger mt-1">
                  {{ $message }}
                </div> @enderror
              </div> --}}
              <div class="form-group">
                <label class="small text-muted">Address</label>
                <textarea name="address" rows="2" class="form-control form-control-lg" placeholder="Enter address">{{ old('address',$operator->address ?? '') }}</textarea>
              </div>
              <div class="form-group mb-0">
                <label class="small text-muted">Remark</label>
                <textarea name="remark" rows="2" class="form-control form-control-lg" placeholder="Any remark...">{{ old('remark',$operator->remark ?? '') }}</textarea>
              </div>
            </div>
            <div class="card-footer text-right py-2">
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i>
                {{ isset($operator) ? 'Update Operator' : 'Save Operator' }}
              </button>
            </div>
          </form>
        </div>
      </div>
      {{-- RIGHT LIST --}}
      <div class="col-sm-7">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-bullhorn"></i> Operator's List
            </h3>
          </div>
          <div class="card-body"> @if($operators->count()) @foreach($operators as $op) <div class="attachment-block clearfix">
              <div class="card-header operator-card">
                <div class="user-block">
                  <img class="img-circle operators-img" src="{{ $op->image ? asset('storage/operators/'.$op->image) : asset('custom/logo.png') }}">
                  <span class="username">
                    <a href="#">{{ $op->name }}</a>
                  </span> @if($op->phone) <span class="description">
                    <i class="fas fa-phone"></i> {{ $op->phone }}
                  </span> @endif @if($op->address) <span class="description">
                    <i class="fas fa-home"></i> {{ $op->address }}
                  </span> @endif @if($op->remark) <span class="description">
                    <i class="fas fa-comment-dots"></i> {{ $op->remark }}
                  </span> @endif
                </div>
                <div class="card-tools">
                  <a href="{{ route('operators.edit',$op->id) }}" class="btn btn-info btn-sm">
                    <i class="far fa-edit"></i>
                  </a>
                  <form action="{{ route('operators.destroy',$op->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this operator?')"> @csrf @method('DELETE') <button class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div> @endforeach @else <div class="text-center text-muted p-4">
              <i class="fas fa-users fa-2x"></i>
              <p class="mt-2">No operators found</p>
            </div> @endif </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- IMAGE PREVIEW SCRIPT --}}
<script>
  document.getElementById('imageInput').addEventListener('change', function(e) {
    let file = e.target.files[0];
    if (file) {
      let preview = document.getElementById('imagePreview');
      preview.src = URL.createObjectURL(file);
    }
  });
</script> @endsection