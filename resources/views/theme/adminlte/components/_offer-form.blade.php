@foreach (active_locals() as $locale)
  @php
    $translation = $offer?->translations->where('locale', $locale)->first() ?? null;
  @endphp
  <div class="form-group">
    <label for="title_{{ $locale }}">Title ({{ strtoupper($locale) }})</label>
    <input type="text" name="title[{{ $locale }}]" class="form-control" value="{{ $translation->title ?? '' }}"
      required>

  </div>

  <div class="form-group">
    <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
    <textarea name="description[{{ $locale }}]" rows="3" class="form-control">{{ $translation->description ?? '' }}</textarea>

  </div>
@endforeach

<div class="form-row">
  <div class="form-group col-md-6">
    <label>Discount Type</label>
    <select name="discount_type" class="form-control" required>
      <option value="fixed" {{ isset($offer) && $offer->discount_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
      <option value="percent" {{ !isset($offer) || $offer->discount_type === 'percent' ? 'selected' : '' }}>Percent
      </option>
    </select>
  </div>

  <div class="form-group col-md-6">
    <label>Discount Value</label>
    <input type="number" name="discount_value" class="form-control" step="0.01"
      value="{{ $offer->discount_value ?? '' }}" required>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label>Start Date</label>
    <input type="datetime-local" name="starts_at" class="form-control"
      value="{{ isset($offer->starts_at) ? $offer->starts_at->format('Y-m-d\TH:i') : '' }}">
  </div>
  <div class="form-group col-md-6">
    <label>End Date</label>
    <input type="datetime-local" name="ends_at" class="form-control"
      value="{{ isset($offer->ends_at) ? $offer->ends_at->format('Y-m-d\TH:i') : '' }}">
  </div>
</div>

<div class="form-group">
  <label>
    <input type="checkbox" name="is_active" value="1"
      {{ isset($offer) ? ($offer->is_active ? 'checked' : '') : 'checked' }}>
    Active
  </label>
</div>
