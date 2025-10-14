<div id="address" class="profile-tab">
  <div class="profile-main bg-white ms-4 p-4 border border-2 shadow ">
    <div class="row p-4">

      {{-- Saved Addresses --}}
      @if ($user->addresses->isNotEmpty())
        <div class="">
          <h5 class="fw-bold mb-3">Saved Addresses</h5>

          @foreach ($user->addresses as $address)
            <div class="default-address-box border p-4 mb-3 shadow-sm position-relative">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <label class="form-check-label w-100">
                    <div class="fw-semibold mb-1">{{ $address->name }}</div>
                    <div class="small text-muted">{{ $address->phone }}</div>
                    <div class="mt-2">{!! $address->render() !!}</div>
                  </label>
                </div>
                <div class="dropdown ms-3">
                  <a href="#" id="addressDropdown{{ $address->id }}" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots-vertical fs-5 text-secondary"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                    aria-labelledby="addressDropdown{{ $address->id }}">
                    <li>
                      <a href="#" class="dropdown-item text-danger btn-delete"
                        data-url="{{ route('customers.address.destroy', $address->id) }}">
                        <i class="bi bi-trash me-2"></i> Delete
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      {{-- Add New Address --}}
      <div class=" mt-4">
        <h5 class="fw-semibold mb-3">Add New Address</h5>
        <form method="POST" action="{{ route('customers.address.store') }}" class="ajax-form">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control theme-input" placeholder="Enter your name"
                value="{{ $user->name }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mobile Number</label>
              <input type="text" name="phone" class="form-control theme-input" placeholder="Enter your mobile no"
                value="{{ $user->detail->mobile ?? '' }}">
            </div>

            <div class="col-md-4">
              <label class="form-label">Province</label>
              <select name="province_id" id="province-select" class="form-select theme-select">
                <option selected="">Select your province</option>
                @foreach ($provinces as $province)
                  <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">City</label>
              <select name="city_id" id="city-select" class="form-select theme-select">
                <option selected="">Select your city</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Area</label>
              <select name="area_id" id="area-select" class="form-select theme-select">
                <option selected="">Select your area</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control theme-input"
                placeholder="House no / building / street / area">
            </div>

            <div class="col-12">
              <label class="form-label">Landmark <span class="text-muted small">(Optional)</span></label>
              <textarea name="landmark" class="form-control theme-input" placeholder="E.g. beside train station"></textarea>
            </div>

            <div class="col-12 mt-3 text-end">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Save Address
              </button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
