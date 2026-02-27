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

            <a class="nav-link" id="social-login-tab" data-bs-toggle="pill" data-bs-target="#v-pills-social-login"
              type="button" role="tab" aria-controls="v-pills-social-login" aria-selected="false">SSO</a>

            <a class="nav-link" id="password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button"
              role="tab" aria-controls="v-pills-password" aria-selected="false">Password</a>

            {{-- ✅ NEW TAB --}}
            <a class="nav-link" id="jasani-tab" data-bs-toggle="pill" data-bs-target="#v-pills-jasani" type="button"
              role="tab" aria-controls="v-pills-jasani" aria-selected="false">Jasani Sync</a>

            <a class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contact" type="button"
              role="tab" aria-controls="v-pills-contact" aria-selected="false">Contact Page</a>
          </div>
        </div>

        <div class="col-7 col-sm-9">
          <div class="tab-content" id="v-pills-tabContent">

            {{-- ===================== SITE ===================== --}}
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="home-tab">
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
                      <h5 class="mb-3">Site Intro</h5>
                      <textarea name="site_intro" class="form-control" placeholder="Site Intro" rows='4'>{{ setting('site_intro') }}</textarea>
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

                    <div class="mb-4">
                      <h5 class="mb-3">Tiny MC Key</h5>
                      <input type="text" class="form-control" name="tiny_mc_key"
                        value="{{ setting('tiny_mc_key') }}">
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Return Policy (days)</h5>
                      <input type="number" class="form-control" name="return_policy_days"
                        value="{{ setting('return_policy_days', 7) }}" min="0" max="365">
                      <small class="text-muted d-block mt-1">Number of days after order date within which customers can
                        request returns.</small>
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Allow Negative Purchase</h5>
                      <div class="form-check form-switch">
                        <input type="hidden" name="allow_negative_purchase" value="0">
                        <input type="checkbox" class="form-check-input" id="allowNegativePurchase"
                          name="allow_negative_purchase" value="1"
                          {{ (bool) setting('allow_negative_purchase', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="allowNegativePurchase">
                          Allow users to add quantity more than available stock
                        </label>
                      </div>
                      <small class="text-muted d-block mt-2">When enabled, customers can purchase items even if stock is
                        insufficient</small>
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

            {{-- ===================== SOCIAL LINKS ===================== --}}
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

            {{-- ===================== PAYMENT GATEWAY ===================== --}}
            <div class="tab-pane fade" id="v-pills-payment-gateway" role="tabpanel"
              aria-labelledby="payment-gatway-tab">

              <form action="{{ route('admin.cms.payment-gateways.store') }}" method="POST"
                enctype="multipart/form-data" class="ajax-form">
                @csrf

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

              </form>
            </div>

            {{-- ===================== SSO ===================== --}}
            <div class="tab-pane fade" id="v-pills-social-login" role="tabpanel" aria-labelledby="social-login-tab">
              <form action="{{ route('admin.cms.settings.store') }}" method="POST" enctype="multipart/form-data"
                class="ajax-form">
                @csrf

                <div class="row">
                  <div class="col-12">
                    <div class="card shadow-sm border-0">
                      <div class="card-header">
                        <h5 class="mb-0">SSO Settings</h5>
                      </div>
                      <div class="card-body">

                        <div class="mb-3">
                          <label for="social_login_enabled" class="form-label fw-bold">Enable Social Login</label>
                          <select id="social_login_enabled" name="social_login_enabled" class="form-select">
                            <option value="1" {{ setting('social_login_enabled') ? 'selected' : '' }}>Enabled
                            </option>
                            <option value="0" {{ !setting('social_login_enabled') ? 'selected' : '' }}>Disabled
                            </option>
                          </select>
                        </div>

                        <hr>

                        <div class="row">
                          <div class="col-md-6">

                            <div class="mb-3">
                              <label for="social_login_google" class="form-label">
                                <i class="fab fa-google text-danger me-1"></i> Google Login
                              </label>
                              <select id="social_login_google" name="social_login_google" class="form-select">
                                <option value="1" {{ setting('social_login_google') ? 'selected' : '' }}>Enabled
                                </option>
                                <option value="0" {{ !setting('social_login_google') ? 'selected' : '' }}>Disabled
                                </option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="social_login_facebook" class="form-label">
                                <i class="fab fa-facebook text-primary me-1"></i> Facebook Login
                              </label>
                              <select id="social_login_facebook" name="social_login_facebook" class="form-select">
                                <option value="1" {{ setting('social_login_facebook') ? 'selected' : '' }}>Enabled
                                </option>
                                <option value="0" {{ !setting('social_login_facebook') ? 'selected' : '' }}>
                                  Disabled</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="social_login_twitter" class="form-label">
                                <i class="fab fa-twitter text-info me-1"></i> Twitter (X) Login
                              </label>
                              <select id="social_login_twitter" name="social_login_twitter" class="form-select">
                                <option value="1" {{ setting('social_login_twitter') ? 'selected' : '' }}>Enabled
                                </option>
                                <option value="0" {{ !setting('social_login_twitter') ? 'selected' : '' }}>Disabled
                                </option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="social_login_linkedin" class="form-label">
                                <i class="fab fa-linkedin text-primary me-1"></i> LinkedIn Login
                              </label>
                              <select id="social_login_linkedin" name="social_login_linkedin" class="form-select">
                                <option value="1" {{ setting('social_login_linkedin') ? 'selected' : '' }}>Enabled
                                </option>
                                <option value="0" {{ !setting('social_login_linkedin') ? 'selected' : '' }}>
                                  Disabled</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="social_login_github" class="form-label">
                                <i class="fab fa-github text-dark me-1"></i> GitHub Login
                              </label>
                              <select id="social_login_github" name="social_login_github" class="form-select">
                                <option value="1" {{ setting('social_login_github') ? 'selected' : '' }}>Enabled
                                </option>
                                <option value="0" {{ !setting('social_login_github') ? 'selected' : '' }}>Disabled
                                </option>
                              </select>
                            </div>

                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>

            {{-- ===================== PASSWORD ===================== --}}
            <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="password-tab">
              <form action="{{ route('admin.cms.settings.store') }}" method="POST" enctype="multipart/form-data"
                class="ajax-form">
                @csrf

                <div class="row">
                  <div class="col-12">

                    <div class="mb-4">
                      <h5 class="mb-3">Minimum Password Length</h5>
                      <input type="number" class="form-control" name="password_min_length"
                        value="{{ setting('password_min_length', 8) }}" min="6" max="20"
                        placeholder="Minimum length">
                      <small class="text-muted">Minimum characters required for passwords</small>
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Require Uppercase Letters</h5>
                      <div class="form-check form-switch">
                        <input type="hidden" name="password_require_uppercase" value="0">
                        <input type="checkbox" class="form-check-input" id="requireUppercase"
                          name="password_require_uppercase" value="1"
                          {{ (bool) setting('password_require_uppercase', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requireUppercase">
                          Require at least one uppercase letter (A-Z)
                        </label>
                      </div>
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Require Numbers</h5>
                      <div class="form-check form-switch">
                        <input type="hidden" name="password_require_numbers" value="0">
                        <input type="checkbox" class="form-check-input" id="requireNumbers"
                          name="password_require_numbers" value="1"
                          {{ (bool) setting('password_require_numbers', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requireNumbers">
                          Require at least one number (0-9)
                        </label>
                      </div>
                    </div>

                    <div class="mb-4">
                      <h5 class="mb-3">Require Special Characters</h5>
                      <div class="form-check form-switch">
                        <input type="hidden" name="password_require_symbols" value="0">
                        <input type="checkbox" class="form-check-input" id="requireSymbols"
                          name="password_require_symbols" value="1"
                          {{ (bool) setting('password_require_symbols', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requireSymbols">
                          Require at least one special character (!@#$%^&*)
                        </label>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary px-5">Save</button>
                  </div>
                </div>
              </form>
            </div>

            {{-- ===================== ✅ JASANI SYNC ===================== --}}
            <div class="tab-pane fade" id="v-pills-jasani" role="tabpanel" aria-labelledby="jasani-tab">
              <form action="{{ route('admin.cms.settings.store') }}" method="POST" class="ajax-form">
                @csrf

                <div class="row">
                  <div class="col-12">

                    <div class="mb-4 p-4 border rounded bg-light">
                      <h5 class="mb-3">Jasani Price Discount</h5>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-semibold">Discount Percentage (%)</label>
                          <input type="number" step="0.01" min="0" max="90" class="form-control"
                            name="jasani_price_discount_percent"
                            value="{{ setting('jasani_price_discount_percent', 5) }}" placeholder="e.g. 5">
                          <small class="text-muted d-block mt-1">
                            This will reduce Jasani retail_price before saving variant price.
                          </small>
                        </div>

                        <div class="col-md-6 mb-3">
                          @php
                            $excluded = setting('jasani_discount_excluded_category_ids', '[]');
                            $excludedIds = json_decode($excluded, true);
                            if (!is_array($excludedIds)) {
                                $excludedIds = [];
                            }
                            // ✅ UUID-safe: compare as strings (STRICT)
                            $excludedIds = array_map('strval', $excludedIds);
                          @endphp

                          <label class="form-label fw-semibold">Excluded Categories (No Discount)</label>
                          <select name="jasani_discount_excluded_category_ids[]" multiple class="select2 form-select">
                            @foreach ($categories ?? [] as $cat)
                              @php
                                $catName = $cat->translations->first()->name ?? ($cat->slug ?? 'Category #' . $cat->id);
                                $catId = (string) $cat->id;
                              @endphp
                              <option value="{{ $catId }}"
                                {{ in_array($catId, $excludedIds, true) ? 'selected' : '' }}>
                                {{ $catName }} (ID: {{ $catId }})
                              </option>
                            @endforeach
                          </select>

                          <small class="text-muted d-block mt-2">
                            Products belonging to any selected category will NOT get Jasani discount.
                          </small>
                        </div>
                      </div>

                      <div class="alert alert-info mb-0">
                        <strong>Example:</strong> If discount is 5% and Jasani sends 100 AED, saved price becomes 95 AED.
                        Excluded category products will stay 100 AED.
                      </div>
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

            {{-- ===================== CONTACT PAGE ===================== --}}
            <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="contact-tab">
              <form action="{{ route('admin.cms.settings.store') }}" method="POST" class="ajax-form">
                @csrf

                <div class="row">
                  <div class="col-md-8">
                    <div class="card card-outline card-primary mb-4">
                      <div class="card-header">
                        <h5 class="card-title">General Settings</h5>
                      </div>
                      <div class="card-body">
                        <div class="mb-4">
                          <label class="form-label fw-bold">Contact Subjects</label>
                          <textarea name="contact_subjects" class="form-control" rows="5" placeholder="Enter subjects (one per line)">{{ setting('contact_subjects') }}</textarea>
                          <small class="text-muted">Enter subjects that will appear in the contact form dropdown. Put
                            each subject on a new line.</small>
                        </div>
                      </div>
                    </div>



                    <div class="card card-outline card-success mb-4">
                      <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Office Map Configuration</h5>
                        <div class="form-check form-switch ms-auto">
                          <input type="hidden" name="show_office_map" value="0">
                          <input type="checkbox" class="form-check-input" id="showOfficeMap" name="show_office_map"
                            value="1" {{ (bool) setting('show_office_map', true) ? 'checked' : '' }}>
                          <label class="form-check-label" for="showOfficeMap">Show Office Map</label>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="mb-4">
                          <label class="form-label fw-bold">Google Map Embed Code</label>
                          <textarea name="office_map_embed" class="form-control" rows="4"
                            placeholder="Paste Google Map iframe code here">{{ setting('office_map_embed') }}</textarea>
                        </div>
                      </div>
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
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // 1. On page load, check if there's a hash in the URL
      const hash = window.location.hash;
      if (hash) {
        const activeTab = document.querySelector(`[data-bs-target="${hash}"]`);
        if (activeTab) {
          const tab = new bootstrap.Tab(activeTab);
          tab.show();
        }
      }

      // 2. When a tab is shown, update the URL hash
      const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="pill"]');
      tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function(event) {
          const target = event.target.getAttribute('data-bs-target');
          if (target) {
            // Update the URL hash without scrolling the page
            history.replaceState(null, null, target);
          }
        });
      });
    });
  </script>
@endpush
