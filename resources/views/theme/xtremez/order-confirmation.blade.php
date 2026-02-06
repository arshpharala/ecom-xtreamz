@extends('theme.xtremez.layouts.app')

@section('content')
  {{-- <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row">
        <h2 class="section-title fs-1 text-center m-0">Order Summary</h2>
      </div>
    </div>
  </section> --}}

  <section class="order-summary-section pb-5">
    <div class="container">
      <!-- Thank You Banner -->
      <div class="bg-white p-5 mb-4 text-center order-thankyou">
        <h1 class="mb-2 order-thankyou-heading">Thank You {{ $order->billingAddress->name ?? 'Guest' }}!</h1>
        <div class="fs-4 fw-light">
          Your <span class="order-id">#{{ $order->reference_number }}</span> is completed successfully
        </div>
      </div>

      <!-- Main Grid -->
      <div class="row gx-3 gy-3 bg-body-tertiary">
        <!-- Left Column -->
        <div class="col-md-6">
          <div class=" p-4 mb-3">
            <div class="fw-bold fs-4">Order Placed</div>
            <div class="small mt-2 mb-0 fs-6">
              We will notify you once it's confirmed.<br>
              A confirmation email will be sent to <span class="fw-bold">{{ $order->email }}</span> when your order is
              confirmed.
            </div>
          </div>
          <div class=" p-4">
            <div class="fw-bold mb-3 fs-4">Customer Details</div>
            <div class="mb-2">
              <span class="fw-bold">Email</span><br>
              {{ $order->email ?? 'N/A' }}
            </div>
            <div class="row">
              <div class="col-6">
                <div class="fw-bold">Billing address</div>
                <div>
                  {!! $order->address->render() !!}
                </div>
              </div>
              <div class="col-6">
                <div class="fw-bold">Shipping address</div>
                <div>
                  {!! $order->address->render() !!}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <div class=" p-4 h-100 d-flex flex-column">
            <!-- Items -->
            <div class="mb-3">
              @foreach ($order->lineItems as $item)
                <div class="d-flex align-items-center mb-2 order-item">
                  <div class="order-item-img me-2">
                    <img src="{{ $item->productVariant->getThumbnail() }}" alt="Product">
                  </div>
                  <div class="flex-grow-1 small fs-6">
                    <div class="fw-bold fs-6">
                      {{ optional($item->productVariant)->product->translation->name ?? 'Product' }}</div>
                    <div class="text-muted">Quantity: {{ $item->quantity }}</div>

                    @if (isset($item->options['customization']))
                      <div class="order-branding-details mt-2 p-2 bg-light rounded border-start border-4 border-warning">
                        @if (!empty($item->options['customization']['text']))
                          <div class="mb-1">
                            <small class="fw-bold text-uppercase text-muted" style="font-size: 0.7rem;">Branding
                              Notes</small>
                            <div class="fst-italic">"{{ $item->options['customization']['text'] }}"</div>
                          </div>
                        @endif

                        @if ($item->attachments->count() > 0)
                          <div class="mt-2">
                            <small class="fw-bold text-uppercase text-muted d-block mb-1"
                              style="font-size: 0.7rem;">Branding Assets</small>
                            <div class="d-flex gap-2 flex-wrap">
                              @foreach ($item->attachments as $attachment)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                                  target="_blank">
                                  <img src="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                                    class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                </a>
                              @endforeach
                            </div>
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                  <div class="fw-bold ms-2 fs-6 text-nowrap">
                    {!! price_format($order->currency->code, $item->price * $item->quantity) !!}
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Totals -->
            <ul class="list-unstyled mt-auto fs-6">
              <li class="d-flex justify-content-between mb-1">
                <span class="text-muted">Subtotal</span>
                <span>
                  {!! price_format($order->currency->code, $order->sub_total) !!}</span>
              </li>
              @if ($order->couponUsages->count() > 0)
                @foreach ($order->couponUsages as $couponUsage)
                  <li class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Coupon ({{ $couponUsage->coupon->code }})</span>
                    <span>-{!! price_format($order->currency->code, $couponUsage->discount_amount) !!}</span>
                  </li>
                @endforeach
              @endif
              <li class="d-flex justify-content-between mb-1">
                <span class="text-muted">Tax</span>
                <span>
                  {!! price_format($order->currency->code, $order->tax) !!}</span>
              </li>
              <hr>
              <li class="d-flex justify-content-between pt-2 fw-bold mt-2">
                <span>Total</span>
                <span class="text-black">
                  {!! price_format($order->currency->code, $order->total) !!}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
