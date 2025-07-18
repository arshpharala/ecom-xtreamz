@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="mb-0">Order #{{ $order->order_number }}</h1>
      <p class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="col-sm-6 text-right">
      <a href="#" class="btn btn-outline-primary" target="_blank">
        <i class="fas fa-file-invoice"></i> View / Download Invoice
      </a>
    </div>
  </div>
@endsection

@section('content')
<div class="row">
  <!-- Left Column -->
  <div class="col-md-6">
    <!-- Customer Info -->
    <div class="card mb-4">
      <div class="card-header"><strong>Customer Details</strong></div>
      <div class="card-body">
        <p><strong>Name:</strong> {{ $order->billingAddress->name }}</p>
        <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $order->billingAddress->phone }}</p>
      </div>
    </div>

    <!-- Billing Address -->
    <div class="card mb-4">
      <div class="card-header"><strong>Billing Address</strong></div>
      <div class="card-body">
        <address>
          {{ $order->billingAddress->address }}<br>
          {{ $order->billingAddress->landmark }}<br>
          {{ $order->billingAddress->area }}, {{ $order->billingAddress->city }}<br>
          {{ $order->billingAddress->province }}
        </address>
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div class="col-md-6">
    <!-- Payment Details -->
    <div class="card mb-4">
      <div class="card-header"><strong>Payment Details</strong></div>
      <div class="card-body">
        <p><strong>Status:</strong> <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
        <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Gateway Ref:</strong> {{ $order->stripe_payment_intent_id ?? 'N/A' }}</p>
        <p><strong>Paid At:</strong> {{ $order->updated_at->format('d M Y, h:i A') }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Order Items -->
<div class="card mb-4">
  <div class="card-header"><strong>Order Items</strong></div>
  <div class="card-body p-0">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Product</th>
          <th>Variant</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->lineItems as $item)
          <tr>
            <td>{{ $item->productVariant->product->name ?? 'Product' }}</td>
            <td>
              @foreach ($item->productVariant->attributeValues as $val)
                <span class="badge badge-light">{{ $val->attribute->name }}: {{ $val->name }}</span>
              @endforeach
            </td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price, 2) }} {{ strtoupper(active_currency()) }}</td>
            <td>{{ number_format($item->subtotal, 2) }} {{ strtoupper(active_currency()) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Order Totals -->
<div class="row">
  <div class="col-md-6 offset-md-6">
    <div class="card">
      <div class="card-header"><strong>Summary</strong></div>
      <div class="card-body">
        <ul class="list-unstyled">
          <li class="d-flex justify-content-between">
            <span>Subtotal:</span>
            <strong>{{ number_format($order->total, 2) }} {{ strtoupper(active_currency()) }}</strong>
          </li>
          <li class="d-flex justify-content-between">
            <span>Tax:</span>
            <strong>0.00 {{ strtoupper(active_currency()) }}</strong>
          </li>
          <li class="d-flex justify-content-between border-top pt-2 mt-2">
            <span>Total:</span>
            <strong>{{ number_format($order->total, 2) }} {{ strtoupper(active_currency()) }}</strong>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
