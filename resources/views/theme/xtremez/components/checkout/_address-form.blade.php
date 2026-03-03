<div id="newAddressSection">
  <div class="mb-3">
    <label class="form-label">Full
      Name</label>
    <input type="text" name="name" class="form-control theme-input" placeholder="Enter your name"
      value="{{ old('name') }}">
  </div>

  @guest
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control theme-input" placeholder="Enter your email"
        value="{{ old('email') }}">
    </div>
  @endguest

  <div class="mb-3">
    <label class="form-label">Mobile Number <small>({{ active_country()->phone_code }})</small></label>
    <input type="text" name="phone" class="form-control theme-input" placeholder="Enter your mobile number"
      value="{{ old('phone') }}">
  </div>

  <div class="mb-3">
    <label class="form-label">Province</label>
    <select name="province_id" id="province-select" class="form-select theme-select">
      <option value="">Select your province</option>
      @foreach ($provinces ?? [] as $province)
        <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">City</label>
    <select name="city_id" id="city-select" class="form-select theme-select">
      <option value="">Select your city</option>
    </select>
  </div>

  {{-- <div class="mb-3">
    <label class="form-label">Area</label>
    <select name="area_id" id="area-select" class="form-select theme-select">
      <option value="">Select your area</option>
    </select>
  </div> --}}

  <div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control theme-input"
      placeholder="House no / building / street / area" value="{{ old('address') }}">
  </div>

  <div class="mb-3">
    <label class="form-label">Landmark (Optional)</label>
    <textarea name="landmark" class="form-control theme-input" placeholder="E.g. beside train station">{{ old('landmark') }}</textarea>
  </div>

</div>
