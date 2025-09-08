<div id="password" class="profile-tab">
  <form class="profile-main ajax-form bg-white p-4 border border-2 shadow" id="updatePasswordForm" method="POST"
    action="{{ route('customers.password.update') }}">
    @csrf
    <div class="row g-4 p-4">
      <div class="col-md-12">
        <label for="currentPassword" class="form-label">Current Password</label>
        <input type="password" id="currentPassword" name="current_password" class="form-control theme-input" required>
      </div>

      <div class="col-md-12">
        <label for="newPassword" class="form-label">New Password</label>
        <input type="password" id="newPassword" name="password" class="form-control theme-input" required>
      </div>

      <div class="col-md-12">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control theme-input"
          required>
      </div>

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
  });
</script>
