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
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">Email Details</h3>
        </div>
        <div class="card-body">

          <form action="{{ route('admin.cms.emails.update', $email->id) }}" method="post" class="ajax-form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Scrollable Content -->
            <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
              <div class="p-3" id="aside-inner-content">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Reference</label>
                      {{ Form::text('reference', $email->reference ?? '', ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                      <label>Subject</label>
                      {{ Form::text('subject', $email->subject ?? '', ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                      <label>Template Path</label>
                      {{ Form::text('template', $email->template ?? '', ['class' => 'form-control', 'required']) }}
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">From Email <small>(Optional)</small></label>
                          {{ Form::text('from_email', $email->from_email ?? '', ['class' => 'form-control']) }}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">From Name <small>(Optional)</small></label>
                          {{ Form::text('from_name', $email->from_name ?? '', ['class' => 'form-control']) }}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="">Reply-To Email <small>(Optional)</small></label>
                      {{ Form::text('reply_to_email', $email->reply_to_email ?? '', ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                      <div class="custom-control custom-switch">
                        {{ Form::checkbox('is_active', 1, old('is_active', 1), ['class' => 'custom-control-input', 'id' => 'is_active']) }}
                        <label class="custom-control-label" for="is_active">Is Active</label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>


          </form>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-secondary">@lang('crud.create')</button>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">Email Admins</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.cms.email.admins.store', $email->id) }}" method="post" class="ajax-form">
            @csrf
            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <label for="">Select Admin</label>
                  {{ Form::select('admin_ids[]', $admins ?? [], null, ['class' => 'form-control select2', 'multiple' => true, 'data-placeholder' => 'Select Admin']) }}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Type</label>
                  {{ Form::select('type', ['to' => 'To', 'cc' => 'CC', 'bcc' => 'BCC', 'exclude' => 'Exclude'], null, ['class' => 'form-control select2', 'data-placeholder' => 'Select Type', 'placeholder' => 'Select Type']) }}
                </div>
              </div>
              <div class="col-md-2 mt-lg-1">
                <button type="submit" class="btn btn-secondary mt-lg-4">Add</button>
              </div>
            </div>

            <div class="row">
              @if ($email->to->isNotEmpty())
                <div class="col-md-12">
                  <h5>To</h5>
                  <ul class="list-unstyled">
                    @foreach ($email->to as $recipient)
                      <li>{{ $recipient->name }} ({{ $recipient->email }}) <i class="fa fa-trash text-danger btn-delete" data-url="{{ route('admin.cms.email.admins.destroy', ['email' => $email, 'admin' => $recipient]) }}"></i></li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if ($email->cc->isNotEmpty())
                <div class="col-md-12">
                  <h5>CC</h5>
                  <ul class="list-unstyled">
                    @foreach ($email->cc as $recipient)
                      <li>{{ $recipient->name }} ({{ $recipient->email }}) <i class="fa fa-trash text-danger btn-delete" data-url="{{ route('admin.cms.email.admins.destroy', ['email' => $email, 'admin' => $recipient]) }}"></i></li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if ($email->bcc->isNotEmpty())
                <div class="col-md-12">
                  <h5>BCC</h5>
                  <ul class="list-unstyled">
                    @foreach ($email->bcc as $recipient)
                      <li>{{ $recipient->name }} ({{ $recipient->email }}) <i class="fa fa-trash text-danger btn-delete" data-url="{{ route('admin.cms.email.admins.destroy', ['email' => $email, 'admin' => $recipient]) }}"></i></li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if ($email->exclude->isNotEmpty())
                <div class="col-md-12">
                  <h5>Exclude</h5>
                  <ul class="list-unstyled">
                    @foreach ($email->exclude as $recipient)
                      <li>{{ $recipient->name }} ({{ $recipient->email }}) <i class="fa fa-trash text-danger btn-delete" data-url="{{ route('admin.cms.email.admins.destroy', ['email' => $email, 'admin' => $recipient]) }}"></i></li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
