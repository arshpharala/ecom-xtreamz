<div class="sidebar-filters pe-4">
  <!-- Category -->
  <div class="mb-4">
    {{-- <ul class="category-list list-unstyled mb-0">
                @foreach ($categories as $category)
                  <li
                    class="d-flex align-items-center py-3 border-bottom {{ $category->id == $activeCategory->id ? 'active' : '' }}    "
                    data-category="{{ $category->id }}" data-category-slug="{{ $category->slug }}">
                    <img src="{{ asset('storage/' . $category->icon) }}" class="me-2" width="22" alt>
                    {{ $category->name }} <span class="ms-auto badge text-dark">{{ $category->products_count }}</span>
                  </li>
                @endforeach
              </ul> --}}

    <ul class="category-list list-unstyled mb-0">
      @foreach (menu_categories(20) as $category)
        {{-- Parent Category --}}
        <li
          class="d-flex align-items-center py-3 parent-category {{ $category->id == $activeCategory->id ? 'active' : '' }}"
          data-category="{{ $category->id }}" data-category-slug="{{ $category->slug }}">

          <img src="{{ asset('storage/' . $category->icon) }}" class="me-2" width="22" alt>
          {{ $category->name }}
          <span class="ms-auto badge text-dark">{{ $category->products_count }}</span>
        </li>

        {{-- Child Categories (if any) --}}
        @if ($category->children->isNotEmpty())
          @foreach ($category->children as $child)
            <li
              class="d-flex align-items-center py-3 ps-5 child-category  {{ $child->id == $activeCategory->id ? 'active' : '' }}"
              data-category="{{ $child->id }}" data-category-slug="{{ $child->slug }}">

              <img src="{{ asset('storage/' . $child->icon) }}" class="me-2" width="18" alt>
              {{ $child->translation->name }}
              <span class="ms-auto badge text-dark">{{ $child->products_count }}</span>
            </li>
          @endforeach
        @endif
      @endforeach
    </ul>



  </div>

  <!-- Brands -->
  <div class="mb-4">
    <h5 class="fs-3 mb-3">Brands</h5>
    <select class="form-select theme-select" name="brand_id">
      <option value="" selected>–</option>
      @foreach ($brands as $brand)
        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
      @endforeach
    </select>
  </div>

  <div id="dynamic-attribute-filters" class="dynamic-attribute-filters">

  </div>


  <!-- Tags -->
  <div class="mb-4">
    <h5 class="fs-3 mb-3x ">Tags</h5>
    @foreach ($tags as $tag)
      <div class="form-check">
        <input class="form-check-input cc-form-check-input" type="checkbox" id="tag_{{ $tag->id }}">
        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
      </div>
    @endforeach
  </div>

  <!-- Price Range -->
  <div class="price-range-wrapper cc-price-range mb-4 d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span id="priceLabelMinSidebar" class="price-label">{!! price_format(active_currency(), 10) !!}</span>
      <span id="priceLabelMaxSidebar" class="price-label">{!! price_format(active_currency(), 2000) !!}</span>
    </div>
    <div id="price-slider-sidebar"></div>
  </div>

  <div class="price-range-wrapper cc-price-range mb-4 d-block d-md-none">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span id="priceLabelMinModal" class="price-label">{!! price_format(active_currency(), 10) !!}</span>
      <span id="priceLabelMaxModal" class="price-label">{!! price_format(active_currency(), 2000) !!}</span>
    </div>
    <div id="price-slider-modal"></div>
  </div>

</div>
