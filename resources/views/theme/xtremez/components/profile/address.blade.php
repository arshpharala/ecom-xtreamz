<div id="address" class="profile-tab">
  <div class="profile-main bg-white p-4 border border-2 shadow">
    <div class="row g-4 p-4 ">
      @if ($user->addresses->isNotEmpty())
        <div class="fw-bold">Saved Address</div>

        @foreach ($user->addresses as $address)
          <div class="default-address-box border p-4 position-relative">
            <div class="form-check pe-4">
              <label class="form-check-label w-100 ms-4" for="address_{{ $address->id }}">
                {!! $address->render() !!}
              </label>
            </div>

            <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
              <a href="#" id="addressDropdown{{ $address->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical text-black"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="addressDropdown{{ $address->id }}">
                <li class="text-center">
                  <a href="#" class="btn-delete text-black"
                    data-url="{{ route('customers.address.destroy', $address->id) }}">Mark as Deleted</a>
                </li>
              </ul>
            </div>
          </div>
        @endforeach
      @endif


      <form method="POST" action="{{ route('customers.address.store') }}" class="mt-5 ajax-form">
        @csrf
        <div class="fw-semibold mb-2">Add New Address</div>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control theme-input" placeholder="Enter your name"
            value="{{ $user->name }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="phone" class="form-control theme-input" placeholder="Enter your mobile no"
            value="{{ $user->detail->mobile ?? '' }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Province</label>
          <select name="province_id" id="province-select" class="form-select theme-select">
            <option selected="">Select your province</option>
            @foreach ($provinces as $province)
              <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">City</label>
          <select name="city_id" id="city-select" class="form-select theme-select">
            <option selected="">Select your city</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Area</label>
          <select name="area_id" id="area-select" class="form-select theme-select">
            <option selected="">Select your area</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control theme-input"
            placeholder="House no / building / street / area">
        </div>

        <div class="mb-3">
          <label class="form-label">Landmark (Optional)</label>
          <textarea name="landmark" class="form-control theme-input" placeholder="E.g. beside train station"></textarea>
        </div>

        <div class="col-12 mt-4">
          <button type="submit" class="btn btn-save btn-secondary">SAVE CHANGES</button>
        </div>
      </form>
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
