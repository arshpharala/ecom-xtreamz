@foreach ($items as $index => $item)
  @php
    $returnableQty = $item->getReturnableQuantity();
  @endphp
  @if ($returnableQty > 0)
    <div class="return-item-row p-2 border-bottom hover-bg" data-id="{{ $item->id }}">
      <div class="d-flex align-items-center">
        <div class="custom-control custom-checkbox mr-2 me-2">
          <input type="checkbox" name="items[{{ $index }}][order_line_item_id]" value="{{ $item->id }}"
            class="custom-control-input return-item-checkbox" id="check_{{ $item->id }}">
          <label class="custom-control-label" for="check_{{ $item->id }}"></label>
        </div>

        <img src="{{ $item->productVariant->getThumbnail() }}" alt=""
          style="width:40px; height:40px; object-fit:cover;" class="rounded border mr-2 me-2">

        <div class="flex-grow-1 min-width-0">
          <label for="check_{{ $item->id }}"
            class="return-item-name fw-bold small d-block mb-0 cursor-pointer text-truncate"
            title="{{ $item->productVariant->product->translation->name }}">
            {{ $item->productVariant->product->translation->name ?? 'Product' }}
          </label>
          <div class="x-small text-muted text-truncate">
            @forelse ($item->productVariant->attributeValues as $val)
              <span class="mr-1 me-1">{{ $val->attribute->name }}: {{ $val->value }}</span>
            @empty
            @endforelse
          </div>
          <div class="x-small text-primary">
            {{ $returnableQty }} available
          </div>
        </div>

        <div style="width: 80px;" class="ml-2 ms-2">
          <input type="number" name="items[{{ $index }}][quantity]" id="qty_{{ $item->id }}"
            class="form-control form-control-sm return-qty-input" value="1" min="1"
            max="{{ $returnableQty }}" disabled>
        </div>
      </div>
    </div>
  @endif
@endforeach

@if ($items->count() === 0)
  <div class="alert alert-info border-0 shadow-sm">
    <i class="bi bi-info-circle me-2"></i> No items are eligible for return in this order.
  </div>
@endif

<script>
  (function() {
    // We attach listeners manually to avoid global scope issues in AJAX loaded content
    const checkboxes = document.querySelectorAll('.return-item-checkbox');
    checkboxes.forEach(cb => {
      cb.addEventListener('change', function() {
        const row = this.closest('.return-item-row');
        const qtyInput = row.querySelector('.return-qty-input');
        qtyInput.disabled = !this.checked;

        if (this.checked) {
          row.classList.add('border-primary-heavy');
        } else {
          row.classList.remove('border-primary-heavy');
        }
      });
    });
  })();
</script>

<style>
  .cursor-pointer {
    cursor: pointer;
  }

  .hover-bg:hover {
    background-color: #f1f3f5;
  }

  .x-small {
    font-size: 0.75rem;
  }

  .min-width-0 {
    min-width: 0;
  }

  .return-item-row {
    transition: background 0.2s;
  }

  .border-primary-heavy {
    border: 2px solid #007bff !important;
    background-color: #e7f1ff !important;
  }
</style>
