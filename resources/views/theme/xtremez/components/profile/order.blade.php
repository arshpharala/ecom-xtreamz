<div id="order" class="profile-tab">
  <div class="profile-main bg-white p-4 border border-2 shadow">
    <div class="row g-4 p-4">
      <div class="col-md-12">
        <div class="order-list">

          @forelse ($user->orders as $order)
            <div class="order-card border rounded-3 mb-4 shadow-sm">
              @php
                $statusClass = match ($order->status ?? '') {
                    'fulfilled' => 'bg-success',
                    'delivered' => 'bg-success',
                    'processing' => 'bg-primary',
                    'cancelled' => 'bg-danger',
                    'placed' => 'bg-info',
                    'pending' => 'bg-warning',
                    'draft' => 'bg-secondary',
                    default => 'bg-secondary',
                };
              @endphp
              <div class="order-header bg-light d-flex justify-content-between align-items-center px-3 py-2 rounded-top">
                <div>
                  <span class="fw-bold">Order #{{ $order->reference_number }}</span>
                  <span class="text-muted small ms-2">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
              </div>

              <div class="order-body p-3">
                @foreach ($order->lineItems as $item)
                  <div class="order-item d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div class="d-flex align-items-center">
                      <div class="order-img-box me-3">
                        <img src="{{ $item->productVariant->getThumbnail() }}" alt="Product" class="order-img rounded"
                          style="width:60px; height:60px; object-fit:cover;">
                      </div>
                      <div>
                        <div class="order-title fw-semibold">
                          {{ $item->productVariant->product->translation->name ?? 'N/A' }}
                        </div>
                        {{-- <div class="small text-muted">
                          Variant: {{ $item->productVariant->sku ?? 'Default' }}
                        </div> --}}
                      </div>
                    </div>
                    {{-- <div class="order-price fw-bold text-nowrap">
                      {!! price_format($order->currency->code, $item->subtotal) !!}
                    </div> --}}
                  </div>
                @endforeach
              </div>

              <div
                class="order-footer bg-light d-flex justify-content-between align-items-center px-3 py-2 rounded-bottom">
                <span class="fw-semibold">
                  {{-- Total: {!! price_format($order->currency->code, $order->total ?? 0) !!}  --}}
                </span>
                <div class="d-flex gap-2">

                  <a href="{{ route('customers.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                    View Details
                  </a>
                </div>
              </div>
            </div>
          @empty
            <div class="order-item text-center py-5 text-muted">
              No orders found yet.
            </div>
          @endforelse

        </div>
      </div>
    </div>
  </div>
</div>
