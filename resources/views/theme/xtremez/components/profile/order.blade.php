<div id="order" class="profile-tab">

  <div class="profile-main bg-white p-4">
    <div class="row g-4 p-4">
      <div class="col-md-12">
        <div class="order-list">
          <!-- Single Order Item -->
          @forelse ($user->orders as $order)
            @foreach ($order->lineItems as $item)
              <div class="order-item d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center">
                  <div class="order-img-box me-3">
                    <img src="assets/images/product.png" alt="Product" class="order-img">
                  </div>
                  <div class="order-title fw-medium">
                    {{ $item->variant->product->translation->name ?? null }}
                  </div>
                </div>
                <div class="order-price fw-bold text-nowrap">{{ $item->subtotal }}
                  {{ $order->currency->code }}</div>
              </div>
            @endforeach
          @empty
          <div class="order-item d-flex align-items-center justify-content-between py-3">
            No Item Found
          </div>
          @endforelse

        </div>
      </div>

    </div>
  </div>
</div>
