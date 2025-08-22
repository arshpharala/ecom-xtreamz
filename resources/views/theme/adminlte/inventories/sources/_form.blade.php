<div class="col-md-8">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">General Information</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Code *</label>
          <input type="text" name="code" class="form-control" value="{{ $source->code ?? '' }}" required>
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Name *</label>
          <input type="text" name="name" class="form-control" value="{{ $source->name ?? '' }}" required>
        </div>
        <div class="col-md-12 form-group">
          <label class="form-label">Description</label>
          <textarea name="description" rows="3" class="form-control">{{ $source->description ?? '' }}</textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Contact Information</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 form-group">
          <label class="form-label">Contact Name</label>
          <input type="text" name="contact_name" class="form-control" value="{{ $source->contact_name ?? '' }}">
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Email</label>
          <input type="email" name="contact_email" class="form-control" value="{{ $source->contact_email ?? '' }}">
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_phone" class="form-control" value="{{ $source->contact_phone ?? '' }}">
        </div>
        <div class="col-md-6 form-group">
          <label class="form-label">Fax</label>
          <input type="text" name="contact_fax" class="form-control" value="{{ $source->contact_fax ?? '' }}">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Source Address</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 form-group">
          <label for="">Country</label>
          <select name="country_id" class="form-select" required>
            <option value="">Select Country</option>
            @foreach ($countries as $id => $name)
              <option value="{{ $id }}" @selected($source->country_id == $id)>{{ $name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label for="">Province</label>
          <select name="province_id" class="form-select">
            <option value="">Select Province</option>
            @foreach ($provinces as $id => $name)
              <option value="{{ $id }}" @selected($source->province_id == $id)>{{ $name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label for="">City</label>
          <select name="city_id" class="form-select">
            <option value="">Select City</option>
            @foreach ($cities as $id => $name)
              <option value="{{ $id }}" @selected($source->city_id == $id)>{{ $name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 form-group">
          <label for="">Street</label>
          <input type="text" name="street" class="form-control" value="{{ $source->street ?? '' }}">
        </div>
        <div class="col-md-6 form-group">
          <label for="">Postcode</label>
          <input type="text" name="postcode" class="form-control" value="{{ $source->postcode ?? '' }}">
        </div>

      </div>
    </div>
  </div>
</div>

<div class="col-md-4">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Settings</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 form-group">
          <label for="">Latitude</label>
          <input type="number" step="0.000001" name="lat" class="form-control"
            value="{{ $source->lat ?? '' }}">
        </div>
        <div class="col-md-12 form-group">
          <label for="">Longitude</label>
          <input type="number" step="0.000001" name="lng" class="form-control"
            value="{{ $source->lng ?? '' }}">
        </div>
        <div class="col-md-12 form-group">
          <label for="">Priority</label>
          <input type="number" min="0" name="priority" class="form-control"
            value="{{ $source->priority ?? 10 }}">
        </div>
        <div class="col-md-12 form-group">
          <div class="form-check form-switch mt-3 ps-5">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
              {{ old('is_active', $source->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
