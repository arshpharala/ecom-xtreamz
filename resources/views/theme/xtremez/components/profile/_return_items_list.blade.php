@foreach ($items as $item)
  @php
    $returnableQty = $item->getReturnableQuantity();
  @endphp
  @if ($returnableQty > 0)
    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2 bg-white">
      <div class="d-flex align-items-center">
        <input type="checkbox" name="items[{{ $loop->index }}][order_line_item_id]" value="{{ $item->id }}"
          class="form-check-input me-3" onchange="toggleQtyInput(this, 'qty_{{ $item->id }}')">
        <img src="{{ $item->productVariant->getThumbnail() }}" alt=""
          style="width:50px; height:50px; object-fit:cover;" class="rounded me-3">
        <div>
          <div class="fw-bold">{{ $item->productVariant->product->translation->name ?? 'Product' }}</div>
          <div class="small text-muted">Purchased: {{ $item->quantity }} | Available to Return: {{ $returnableQty }}
          </div>
        </div>
      </div>
      <div style="width: 100px;">
        <input type="number" name="items[{{ $loop->index }}][quantity]" id="qty_{{ $item->id }}"
          class="form-control form-control-sm" value="1" min="1" max="{{ $returnableQty }}" disabled>
      </div>
    </div>
  @endif
@endforeach

<script>
  function toggleQtyInput(checkbox, inputId) {
    document.getElementById(inputId).disabled = !checkbox.checked;
  }
</script>
