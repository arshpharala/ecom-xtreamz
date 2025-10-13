<div id="profile" class="profile-tab">
  <form class="profile-main bg-white p-4 ajax-form border border-2 shadow" method="POST"
    action="{{ route('customers.profile.update') }}">
    @csrf
    <div class="row g-4 p-4">
      {{-- Full Name --}}
      <div class="col-md-6">
        <label for="fullName" class="form-label">Full Name</label>
        <div class="input-group">
          <input type="text" id="fullName" name="name" class="form-control theme-input"
            value="{{ old('name', $user->name) }}" readonly />
          <button type="button" class="btn btn-edit" aria-label="Edit Full Name">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      {{-- Email --}}
      <div class="col-md-6">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
          <input type="email" id="email" name="email" class="form-control theme-input"
            value="{{ old('email', $user->email) }}" readonly />
          <button type="button" class="btn btn-edit" aria-label="Edit Email">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      {{-- Mobile --}}
      <div class="col-md-6">
        <label for="mobile" class="form-label">Mobile</label>
        <div class="input-group">
          <input type="text" id="mobile" name="mobile" class="form-control theme-input"
            value="{{ old('mobile', $user->detail->mobile ?? '') }}" readonly />
          <button type="button" class="btn btn-edit" aria-label="Edit Mobile">
            <i class="bi bi-pencil"></i>
          </button>
        </div>

        <div id="mobile-error"></div>
      </div>

      {{-- Birthday --}}
      <div class="col-md-6">
        <label for="birthday" class="form-label">Birthday</label>
        <div class="input-group">
          <input type="date" id="birthday" name="dob" class="form-control theme-input"
            value="{{ old('dob', $user->detail->dob ?? '') }}" readonly />
          <button type="button" class="btn btn-edit" aria-label="Edit Birthday">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      {{-- Gender --}}
      <div class="col-12">
        <label class="form-label d-block mb-2">Gender</label>
        <div class="btn-group gender-toggle" role="group" aria-label="Gender selection">
          @php $gender = old('gender', $user->detail->gender ?? 'male'); @endphp

          <input type="radio" class="btn-check" name="gender" id="male" value="male"
            {{ $gender === 'male' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary {{ $gender === 'male' ? 'active' : '' }}" for="male">MALE</label>

          <input type="radio" class="btn-check" name="gender" id="female" value="female"
            {{ $gender === 'female' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary {{ $gender === 'female' ? 'active' : '' }}"
            for="female">FEMALE</label>
        </div>
      </div>

      {{-- Submit --}}
      <div class="col-12 mt-4">
        <button type="submit" class="btn btn-save btn-secondary">SAVE CHANGES</button>
      </div>
    </div>
  </form>
</div>

<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });


    $('#mobile').on('input', function() {
      let value = $(this).val();

      // Allow only numbers, +, -, and spaces
      value = value.replace(/[^0-9+\-\s]/g, '');
      $(this).val(value);

      // Regex to match international formats (+971..., 971..., +44..., etc.)
      const pattern = /^\+?\d{1,4}?[-\s]?\d{6,14}$/;

      if (!pattern.test(value)) {
        this.setCustomValidity(
        'Enter a valid mobile number, e.g. +971-504532525 or 97150323234.');
      } else {
        this.setCustomValidity('');
      }
    });


  });
</script>
