@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.create_title', ['name' => 'Email'])</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.cms.emails.index') }}" class="btn btn-secondary float-sm-right">
        @lang('crud.back_to_list', ['name' => 'Email'])
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    {{-- Main Content --}}
    <div class="col-md-7">
      <form action="{{ route('admin.cms.emails.store') }}" method="post" class="ajax-form" enctype="multipart/form-data">
        @csrf
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Email Details</h3>
          </div>
          <div class="card-body">


            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Reference</label>
                  <input type="text" name="reference" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Subject</label>
                  <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Template Path</label>
                  <input type="text" name="template" class="form-control" required>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">From Email <small>(Optional)</small></label>
                      <input type="email" name="from_email" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">From Name <small>(Optional)</small></label>
                      <input type="text" name="from_name" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">Reply-To Email <small>(Optional)</small></label>
                  <input type="email" name="reply_to_email" class="form-control">
                </div>

                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active"
                      {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Is Active</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn btn-secondary">@lang('crud.create')</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
