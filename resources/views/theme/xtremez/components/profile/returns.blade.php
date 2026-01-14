<div id="returns" class="profile-tab">
  <div class="profile-main bg-white p-4 border border-2 shadow">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
      <h4 class="mb-0">Returns & Refunds</h4>
    </div>

    <!-- New Return Section (Hidden by default) -->
    <div id="newReturnSection" class="border rounded p-4 mb-5 bg-light" style="display: none;">
      <h5 class="mb-4">Raise a New Return Request</h5>

      @if ($eligibleOrders->count() > 0)
        <form action="{{ route('customers.returns.store') }}" method="POST" class="ajax-form"
          enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Select Order</label>
              <select name="order_id" class="form-select" required onchange="loadOrderItems(this.value)">
                <option value="">-- Select Eligible Order --</option>
                @foreach ($eligibleOrders as $order)
                  <option value="{{ $order->id }}">Order #{{ $order->reference_number }}
                    ({{ $order->created_at->format('d M Y') }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Reason for Return</label>
              <select name="return_reason_id" class="form-select" required>
                @foreach ($reasons as $reason)
                  <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                @endforeach
              </select>
            </div>

            <div id="orderItemsContainer" class="col-12 mt-4" style="display: none;">
              <h6>Select Items to Return</h6>
              <div id="itemsList"></div>
            </div>

            <div class="col-12 mt-3">
              <label class="form-label">Description / Issue Highlights</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Tell us more about the issue..."></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Refund Method</label>
              <select name="refund_method" class="form-select">
                <option value="original_method">Original Payment Method</option>
                <option value="account_credits">Account Credits</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Attach Photos (Optional)</label>
              <input type="file" name="attachments[]" class="form-control" multiple accept="image/*">
              <div class="form-text">Max 5 images. Helps us process faster.</div>
            </div>

            <div class="col-12 mt-4 text-end">
              <button type="button" class="btn btn-outline-secondary me-2"
                onclick="$('#newReturnSection').hide();">Cancel</button>
              <button type="submit" class="btn btn-primary">Submit Return Request</button>
            </div>
          </div>
        </form>
      @else
        <div class="alert alert-info py-3 mb-0">
          <i class="bi bi-info-circle me-2"></i> You don't have any orders eligible for return at this time. Returns are
          typically allowed within 30 days of payment.
        </div>
      @endif
    </div>

    <!-- Existing Returns List -->
    <div class="returns-list mt-4">
      @forelse ($user->returnRequests as $return)
        <div class="return-card border rounded-3 mb-4 shadow-sm">
          <div class="return-header bg-light d-flex justify-content-between align-items-center px-3 py-2 rounded-top">
            <div>
              <span class="fw-bold">Return #{{ $return->reference_number }}</span>
              <span class="text-muted small ms-2">Raised on {{ $return->created_at->format('d M Y') }}</span>
            </div>
            @php
              $statusColors = [
                  'pending' => 'bg-warning text-dark',
                  'approved' => 'bg-info',
                  'rejected' => 'bg-danger',
                  'shipped' => 'bg-primary',
                  'received' => 'bg-info text-dark',
                  'refunded' => 'bg-success',
              ];
              $color = $statusColors[$return->status] ?? 'bg-secondary';
            @endphp
            <span class="badge {{ $color }}">{{ ucfirst($return->status) }}</span>
          </div>

          <div class="return-body p-3">
            <div class="row align-items-center">
              <div class="col-md-7">
                <div class="small mb-2"><strong>Items:</strong></div>
                @foreach ($return->items as $item)
                  <div class="d-flex align-items-center mb-2">
                    <img src="{{ $item->orderLineItem->productVariant->getThumbnail() }}" alt="Product"
                      class="rounded me-2" style="width:40px; height:40px; object-fit:cover;">
                    <div class="small">
                      {{ $item->orderLineItem->productVariant->product->translation->name ?? 'Product' }}
                      (x{{ $item->quantity }})
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="col-md-5 text-md-end border-start">
                <div class="small text-muted mb-1">Original Order: #{{ $return->order->reference_number }}</div>
                <div class="small text-muted mb-2">Reason: {{ $return->reason->reason }}</div>

                @if ($return->admin_notes)
                  <div class="alert alert-light border small text-start mb-2 py-2 px-3">
                    <strong>Note:</strong> {{ $return->admin_notes }}
                  </div>
                @endif

                @if ($return->shipping_label_path)
                  <a href="{{ asset('storage/' . $return->shipping_label_path) }}" target="_blank"
                    class="btn btn-sm btn-outline-primary w-100 mt-2">
                    <i class="bi bi-file-earmark-arrow-down"></i> Download Shipping Label
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-5 text-muted">
          <i class="bi bi-arrow-return-left fs-1 d-block mb-3 opacity-25"></i>
          No return requests found.
        </div>
      @endforelse
    </div>
  </div>
</div>

<script>
  function loadOrderItems(orderId) {
    if (!orderId) {
      $('#orderItemsContainer').hide();
      return;
    }

    $.get('/customers/returns/order-items/' + orderId, function(data) {
      $('#itemsList').html(data);
      $('#orderItemsContainer').show();
    });
  }
</script>
