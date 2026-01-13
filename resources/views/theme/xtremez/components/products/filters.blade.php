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
      @foreach ($sidebarCategories as $category)
        @php
          $isParentActive = $category->id == ($activeCategory->id ?? null);
          $isChildOrGrandchildActive = $category->children->contains(function ($child) use ($activeCategory) {
              return $child->id == ($activeCategory->id ?? null) ||
                  $child->children->contains('id', $activeCategory->id ?? null);
          });
          $shouldExpandParent = $isParentActive || $isChildOrGrandchildActive;
        @endphp

        <li class="category-item parent-item">
          <div
            class="d-flex align-items-center py-3 parent-category {{ $isParentActive ? 'active' : '' }} {{ $category->children->count() ? 'has-children' : '' }}"
            data-category="{{ $category->id }}" data-category-slug="{{ $category->slug }}">

            <img src="{{ asset('storage/' . $category->icon) }}" class="me-2" width="22" alt>
            {{ $category->name }}
            <span class="ms-auto badge text-dark">{{ $category->products_count }}</span>
          </div>

          @if ($category->children->isNotEmpty())
            <ul class="category-children list-unstyled level-1 {{ $shouldExpandParent ? 'expanded' : '' }}">
              @foreach ($category->children as $child)
                @php
                  $isChildItemActive = $child->id == ($activeCategory->id ?? null);
                  $isGrandchildActive = $child->children->contains('id', $activeCategory->id ?? null);
                  $shouldExpandChild = $isChildItemActive || $isGrandchildActive;
                @endphp

                <li class="category-item child-item">
                  <div
                    class="d-flex align-items-center py-3 child-category {{ $isChildItemActive ? 'active' : '' }} {{ $child->children->count() ? 'has-children' : '' }}"
                    data-category="{{ $child->id }}" data-category-slug="{{ $child->slug }}">

                    <img src="{{ asset('storage/' . $child->icon) }}" class="me-2" width="18" alt>
                    {{ $child->translation->name }}
                    <span class="ms-auto badge text-dark">{{ $child->products_count }}</span>
                  </div>

                  @if ($child->children->isNotEmpty())
                    <ul class="category-children list-unstyled level-2 {{ $shouldExpandChild ? 'expanded' : '' }}">
                      @foreach ($child->children as $grandchild)
                        @php
                          $isGrandchildItemActive = $grandchild->id == ($activeCategory->id ?? null);
                        @endphp

                        <li class="category-item grandchild-item">
                          <div
                            class="d-flex align-items-center py-3 grandchild-category {{ $isGrandchildItemActive ? 'active' : '' }}"
                            data-category="{{ $grandchild->id }}" data-category-slug="{{ $grandchild->slug }}">

                            <img src="{{ asset('storage/' . $grandchild->icon) }}" class="me-2" width="16" alt>
                            {{ $grandchild->translation->name }}
                            <span class="ms-auto badge text-dark">{{ $grandchild->products_count }}</span>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endforeach
            </ul>
          @endif
        </li>
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
        <input class="form-check-input cc-form-check-input" type="checkbox" id="tag_{{ $tag->id }}"
          @checked(in_array($tag->name, request()->tags ?? []))>
        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
      </div>
    @endforeach
  </div>

  <!-- Price Range -->
  <div class="price-range-wrapper cc-price-range mb-4 d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span id="priceLabelMinSidebar" class="price-label">{!! price_format(active_currency(), 0) !!}</span>
      <span id="priceLabelMaxSidebar" class="price-label">{!! price_format(active_currency(), 2000) !!}</span>
    </div>
    <div id="price-slider-sidebar"></div>
  </div>

  <div class="price-range-wrapper cc-price-range mb-4 d-block d-md-none">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span id="priceLabelMinModal" class="price-label">{!! price_format(active_currency(), 0) !!}</span>
      <span id="priceLabelMaxModal" class="price-label">{!! price_format(active_currency(), 2000) !!}</span>
    </div>
    <div id="price-slider-modal"></div>
  </div>

</div>
