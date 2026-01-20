@extends('theme.xtremez.layouts.app')

@section('content')
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="mb-0">Order #{{ $order->reference_number }}</h3>
        <p class="text-muted small">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
      </div>
      <div class="d-flex gap-2">
        @php
          $returnableItems = $order->lineItems->filter(fn($li) => $li->getReturnableQuantity() > 0);
        @endphp

        @if ($order->canBeReturned() && $returnableItems->count() > 0)
          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newReturnModal">
            <i class="bi bi-arrow-return-left"></i> Request Return
          </button>
        @endif

        <a href="{{ route('customers.profile', ['tab' => 'order']) }}" class="btn btn-outline-secondary">&larr; Back to
          Orders</a>
      </div>
    </div>

    @includeIf('theme.xtremez.customers.orders._new_return_modal')

    <div class="row">
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header"><strong>Billing Details</strong></div>
          <div class="card-body">
            <p><strong>Name:</strong> {{ $order->billingAddress->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $order->billingAddress->phone ?? 'N/A' }}</p>
            @if ($order->billingAddress)
              <address>{!! $order->billingAddress->render() !!}</address>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header"><strong>Payment & Shipping</strong></div>
          <div class="card-body">
            <p><strong>Payment Status:</strong>
              <span
                class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
            </p>
            <p><strong>Method:</strong> {{ ucfirst($order->payment_method) ?? 'N/A' }}</p>
            <p><strong>Paid At:</strong> {{ $order->updated_at->format('d M Y, h:i A') }}</p>
            <p><strong>Currency:</strong> {{ $order->currency->code ?? strtoupper(active_currency()) }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header"><strong>Order Items</strong></div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Product</th>
              <th>Variant</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->lineItems as $item)
              <tr>
                <td>{{ $item->productVariant->product->translation->name ?? 'Product' }}</td>
                <td>
                  @forelse ($item->productVariant->attributeValues as $val)
                    <span class="badge bg-light text-dark me-1">{{ $val->attribute->name }}: {{ $val->value }}</span>

                  @empty
                    -
                  @endforelse

                </td>
                <td>{{ $item->quantity }}</td>
                <td>{!! price_format($order->currency->code, $item->price) !!}</td>
                <td>{!! price_format($order->currency->code, $item->subtotal) !!}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6 offset-md-6">
        <div class="card">
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              <li class="d-flex justify-content-between"><span>Subtotal</span><strong>{!! price_format($order->currency->code, $order->sub_total) !!}</strong>
              </li>
              @if ($order->couponUsages->count() > 0)
                @foreach ($order->couponUsages as $couponUsage)
                  <li class="d-flex justify-content-between"><span>Coupon
                      ({{ $couponUsage->coupon->code }})</span><strong>-{!! price_format($order->currency->code, $couponUsage->discount_amount) !!}</strong></li>
                @endforeach
              @endif
              <li class="d-flex justify-content-between"><span>Tax</span><strong>{!! price_format($order->currency->code, $order->tax) !!}</strong></li>
              <li class="d-flex justify-content-between border-top pt-2 mt-2">
                <span>Total</span><strong>{!! price_format($order->currency->code, $order->total) !!}</strong>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    @if ($order->returnRequests->count())
      <div class="card">
        <div class="card-header"><strong>Return Requests for this Order</strong></div>
        <div class="card-body">
          @foreach ($order->returnRequests as $return)
            <div class="mb-3 p-3 border rounded">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="mb-0">Return #{{ $return->reference_number }}</h6>
                  <small class="text-muted">Submitted on {{ $return->created_at->format('d M Y, h:i A') }}</small>
                </div>
                <div class="text-end">
                  <span
                    class="badge bg-{{ $return->status === 'approved' ? 'success' : ($return->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($return->status) }}</span>
                </div>
              </div>

              <div class="mt-2">
                <p class="mb-1"><strong>Reason:</strong> {{ $return->reason->reason ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Refund method:</strong>
                  {{ $return->refund_method === 'account_credits' ? 'Account Credits' : 'Original Payment Method' }}</p>
                <p class="mb-0"><strong>Items:</strong></p>
                <ul>
                  @foreach ($return->items as $ri)
                    <li>{{ $ri->orderLineItem->productVariant->product->translation->name ?? 'Product' }} — Qty:
                      {{ $ri->quantity }}</li>
                  @endforeach
                </ul>

                @if ($return->attachments->count())
                  <div class="mt-2">
                    <strong>Attachments:</strong>
                    <div class="d-flex gap-2 mt-1">
                      @foreach ($return->attachments as $att)
                        <a href="{{ $att->url }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
@endsection
