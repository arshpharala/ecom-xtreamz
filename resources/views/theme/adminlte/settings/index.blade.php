@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Setting</h1>
    </div>

  </div>
@endsection
@section('content')
  <div class="card">

    <div class="card-body">
      <div class="row">
        <div class="col-5 col-sm-3">
          <div class="nav flex-column nav-tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button"
              role="tab" aria-controls="v-pills-home" aria-selected="true">Site</a>
            <a class="nav-link" id="profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button"
              role="tab" aria-controls="v-pills-profile" aria-selected="false">Social Links</a>
            <a class="nav-link" id="payment-gatway-tab" data-bs-toggle="pill" data-bs-target="#v-pills-payment-gateway"
              type="button" role="tab" aria-controls="v-pills-payment-gateway" aria-selected="false">Payment
              Gateway</a>

          </div>
        </div>
        <div class="col-7 col-sm-9">
          <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby=home-tab">
              <form action="{{ route('admin.cms.settings.store') }}" method="POST" enctype="multipart/form-data"
                class="ajax-form">
                @csrf
                <div class="row">

                  <div class="col-md-8">


                    <div class="mb-4">
                      <h5 class="mb-3">Site Title</h5>
                      <input type="text" class="form-control" name="site_title" value="{{ setting('site_title') }}"
                        placeholder="Site Title">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Contact Email</h5>
                      <input type="email" class="form-control" name="contact_email"
                        value="{{ setting('contact_email') }}" placeholder="Email">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Contact Phone</h5>
                      <input type="text" class="form-control" name="contact_phone"
                        value="{{ setting('contact_phone') }}" placeholder="Phone number">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Address</h5>
                      <textarea class="form-control" name="address" rows="3" placeholder="Address">{{ setting('address') }}</textarea>
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Copyright</h5>
                      <input type="text" class="form-control" name="copyright" value="{{ setting('copyright') }}">
                    </div>


                  </div>

                  <div class="col-md-4">
                    <div class="mb-4">
                      <h5 class="mb-3">Logo</h5>
                      <input type="file" class="form-control" name="site_logo" accept="image/*">
                      @if (setting('site_logo'))
                        <img src="{{ asset(setting('site_logo')) }}" class="mt-2" width="100">
                      @endif
                    </div>
                    <div class="mb-4">
                      <h5 class="mb-3">Footer Logo</h5>
                      <input type="file" class="form-control" name="site_footer_logo" accept="image/*">
                      @if (setting('site_footer_logo'))
                        <img src="{{ asset(setting('site_footer_logo')) }}" class="mt-2" width="100">
                      @endif
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Favicon</h5>
                      <input type="file" class="form-control" name="site_favicon" accept="image/x-icon,image/png">
                      @if (setting('site_favicon'))
                        <img src="{{ asset(setting('site_favicon')) }}" class="mt-2" width="32">
                      @endif
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-primary px-5">Save</button>
                  </div>
                </div>
              </form>

            </div>
            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="profile-tab">

              <form action="{{ route('admin.cms.settings.store') }}" method="POST" enctype="multipart/form-data"
                class="ajax-form">
                @csrf

                <div class="row">
                  <div class="col-12">

                    <div class="mb-4">
                      <h5 class="mb-3">Facebook</h5>
                      <input type="url" class="form-control" name="facebook" value="{{ setting('facebook') }}">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Instagram</h5>
                      <input type="url" class="form-control" name="instagram" value="{{ setting('instagram') }}">
                    </div>


                    <div class="mb-4">
                      <h5 class="mb-3">LinkedIn</h5>
                      <input type="url" class="form-control" name="linkedin" value="{{ setting('linkedin') }}">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Pinterest</h5>
                      <input type="url" class="form-control" name="pinterest" value="{{ setting('pinterest') }}">
                    </div>


                    <div class="mb-4">
                      <h5 class="mb-3">X (Twitter)</h5>
                      <input type="url" class="form-control" name="twitter" value="{{ setting('twitter') }}">
                    </div>


                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-primary px-5">Save</button>
                  </div>
                </div>

              </form>
            </div>
            <div class="tab-pane fade" id="v-pills-payment-gateway" role="tabpanel"
              aria-labelledby="payment-gatway-tab">

              {{ Form::open(['route' => ['admin.cms.payment-gateways.store'], 'method' => 'POST', 'class' => 'ajax-form']) }}

              <div class="row">
                <div class="col-12">
                  @foreach ($gatwayConfig as $gatewayKey => $gatewayConfig)
                    @php
                      $pg = $gateways->firstWhere('gateway', $gatewayKey);
                    @endphp

                    <div class="mb-5 p-4 border rounded shadow-sm bg-light">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-uppercase fw-bold">{{ $gatewayConfig['label'] }}</h5>

                        <div class="form-check form-switch">
                          <input type="checkbox" name="is_active[{{ $gatewayKey }}]" class="form-check-input"
                            value="1" {{ $pg && $pg->is_active ? 'checked' : '' }}>
                          <label class="form-check-label ms-2">Enabled</label>
                        </div>
                      </div>

                      <div class="row">
                        @foreach ($gatewayConfig['fields'] as $fieldKey => $field)
                          @php
                            $rawValue = $pg->$fieldKey ?? ($pg->additional[$fieldKey] ?? '');
                            if (isset($field['encrypted']) && $field['encrypted']) {
                                $value = $rawValue ? mask_sensitive($rawValue) : '';
                            } else {
                                $value = $rawValue ?? '';
                            }
                          @endphp

                          <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">{{ $field['label'] }}
                              @if (!empty($field['help']))
                                <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip"
                                  title="{{ $field['help'] }}"></i>
                              @endif
                            </label>
                            <input type="{{ $field['type'] ?? 'text' }}"
                              name="{{ $fieldKey }}[{{ $gatewayKey }}]" class="form-control"
                              value="{{ $value }}" placeholder="Enter {{ $field['label'] }}">
                          </div>
                        @endforeach

                        {{-- Optional Webhook --}}
                        @if (!empty($gatewayConfig['webhook']))
                          <div class="col-md-12 mt-2">
                            <label class="form-label fw-semibold">Webhook URL</label>
                            <input type="url" name="webhook_url[{{ $gatewayKey }}]" class="form-control"
                              value="{{ $pg->additional['webhook_url'] ?? '' }}"
                              placeholder="Enter Webhook URL (optional)">
                          </div>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-primary px-5">Save</button>
                </div>
              </div>

              {{ Form::close() }}
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
