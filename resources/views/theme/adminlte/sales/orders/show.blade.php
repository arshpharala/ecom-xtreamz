@extends('theme.adminlte.layouts.app')

@push('head')
  <style>
    /* ===========================
      AdminLTE-like Order Details
      =========================== */

    /* Header */
    .od-title {
      font-size: 1.35rem;
      font-weight: 800;
      margin: 0;
    }

    .od-meta {
      font-size: .82rem;
      color: #6c757d;
    }

    .od-meta i {
      width: 16px;
      text-align: center;
      margin-right: 6px;
    }

    .od-pill {
      font-size: .7rem;
      padding: .22rem .55rem;
      border-radius: 999px;
      letter-spacing: .03em;
      font-weight: 700;
    }

    /* Sticky right column like screenshot */
    .od-sticky {
      position: sticky;
      top: 12px;
    }

    /* Top info boxes (tight) */
    .info-box {
      border-radius: .35rem;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .03);
      border: 1px solid #f1f3f5;
    }

    .info-box .info-box-number {
      font-weight: 800;
    }

    /* Items table compact like screenshot */
    .od-table {
      margin: 0;
    }

    .od-table thead th {
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: #6c757d;
      white-space: nowrap;
      border-top: 0 !important;
    }

    .od-table tbody td {
      padding: .55rem .75rem !important;
      vertical-align: middle !important;
    }

    .od-table tbody tr:hover {
      background: #fafafa;
    }

    /* product cell */
    .od-prod {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      min-width: 320px;
    }

    /* HARD FIX for giant image */
    .od-thumb {
      width: 38px !important;
      height: 38px !important;
      max-width: 38px !important;
      max-height: 38px !important;
      object-fit: cover !important;
      border-radius: .35rem !important;
      border: 1px solid #e9ecef !important;
      background: #fff !important;
      flex-shrink: 0 !important;
      display: block !important;
    }

    .od-prod-title {
      font-weight: 800;
      font-size: .86rem;
      line-height: 1.2;
      color: #212529;
      margin-bottom: 2px;
    }

    .od-prod-sub {
      font-size: .74rem;
      color: #6c757d;
      line-height: 1.2;
    }

    .od-prod-sub .badge {
      font-size: .68rem;
      border: 1px solid rgba(0, 0, 0, .06);
      padding: .18rem .4rem;
      border-radius: 999px;
      margin-right: 6px;
    }

    .od-mini {
      font-size: .74rem;
      color: #6c757d;
    }

    /* Right cards */
    .card {
      border-radius: .35rem;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .03);
      border: 1px solid #f1f3f5;
    }

    .card-header {
      background: #fff;
      border-bottom: 1px solid #f1f3f5;
    }

    .card-title {
      font-weight: 800 !important;
    }

    .btn-white {
      background: #fff;
      border: 1px solid #dee2e6;
      color: #495057;
    }

    .btn-white:hover {
      background: #f8f9fa;
    }

    /* Summary rows */
    .od-sum-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 0;
      border-bottom: 1px dashed #eee;
      font-size: .86rem;
    }

    .od-sum-row:last-child {
      border-bottom: 0;
    }

    .od-grand {
      font-weight: 900;
      font-size: .95rem;
    }

    /* Small copy icon alignment */
    .copy-btn {
      line-height: 1;
    }

    .copy-btn i {
      font-size: .9rem;
    }

    /* Embedded map */
    .od-map {
      width: 100%;
      height: 220px;
      border: 0;
      border-radius: .35rem;
      background: #f8f9fa;
    }
  </style>
@endpush

@section('content-header')
  @php
    $statusBadge = match ($order->status) {
        'draft' => 'secondary',
        'placed' => 'warning',
        'confirmed' => 'info',
        'fulfilled' => 'success',
        'cancelled' => 'secondary',
        'rejected' => 'danger',
        default => 'dark',
    };

    $payBadge =
        $order->payment_status === 'paid'
            ? 'success'
            : ($order->payment_status === 'refunded'
                ? 'danger'
                : 'warning');

    $shippingAddress = $order->shippingAddress ?: $order->billingAddress;
    $itemsCount = (int) $order->lineItems->sum('quantity');
  @endphp

  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-7">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-1 small font-weight-bold">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.sales.orders.index') }}" class="text-muted">Sales</a></li>
            <li class="breadcrumb-item active text-primary" aria-current="page">Order Details</li>
          </ol>
        </nav>

        <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
          <h1 class="od-title">Order <span class="text-muted">#{{ $order->reference_number }}</span></h1>
          <span class="badge badge-{{ $statusBadge }} od-pill">{{ strtoupper($order->status) }}</span>
          <span class="badge badge-{{ $payBadge }} od-pill">{{ strtoupper($order->payment_status) }}</span>
        </div>

        <div class="od-meta mt-1">
          <span class="mr-3"><i class="far fa-calendar-alt"></i>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
          <span><i class="far fa-clock"></i>Updated {{ $order->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
        </div>
      </div>

      <div class="col-sm-5 text-right">
        <div class="btn-group shadow-sm">
          <a href="{{ route('order.receipt.preview', $order->id) }}" class="btn btn-white btn-sm" target="_blank">
            <i class="fas fa-print mr-1"></i> Print
          </a>
          <a href="{{ route('order.receipt.preview', $order->id) }}" class="btn btn-white btn-sm" target="_blank">
            <i class="fas fa-file-pdf mr-1 text-danger"></i> PDF
          </a>
          <a href="{{ route('admin.sales.orders.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list mr-1"></i> Orders
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="container-fluid">

    {{-- Top Info Boxes (like screenshot) --}}
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Customer</span>
            <span class="info-box-number">{{ $order->billingAddress->name ?? 'N/A' }}</span>
            <span class="od-mini text-truncate d-block">{{ $order->email ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-success"><i class="fas fa-receipt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total</span>
            <span class="info-box-number">{!! price_format($order->currency->code, $order->total) !!}</span>
            <span class="od-mini d-block">Subtotal: {!! price_format($order->currency->code, $order->sub_total) !!}</span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-info"><i class="fas fa-box-open"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Items</span>
            <span class="info-box-number">{{ $itemsCount }} Items</span>
            <span class="od-mini d-block">Line items: {{ $order->lineItems->count() }}</span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-dark"><i class="fas fa-credit-card"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Payment</span>
            <span class="info-box-number">{{ strtoupper($order->payment_method) }}</span>
            <span class="od-mini text-truncate d-block">Ref: {{ $order->external_reference ?? 'N/A' }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Left: Items --}}
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-shopping-bag mr-2"></i>Order Items</h3>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-hover od-table">
                <thead class="bg-light">
                  <tr>
                    <th class="pl-3">Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right pr-3">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->lineItems as $item)
                    @php
                      $pv = $item->productVariant;
                      $p = $pv?->product;
                      $name = $p?->translation?->name ?? 'Product';
                      $sku = $pv?->sku ?? '#';
                      $cat = $p?->category?->translation?->name ?? 'Category';
                    @endphp

                    <tr>
                      <td class="pl-3">
                        <div class="od-prod">
                          <img src="{{ $pv?->getThumbnail() }}" class="od-thumb" alt="thumb">

                          <div class="w-100">
                            <div class="od-prod-title">
                              {{ $name }}
                              <span class="text-muted font-weight-normal">- {{ $sku }}</span>
                            </div>

                            <div class="od-prod-sub">
                              <span class="badge badge-light">{{ $cat }}</span>
                              <span class="text-muted">SKU:</span> <strong>{{ $sku }}</strong>
                            </div>

                            @if ($pv && $pv->attributeValues->count())
                              <div class="od-mini mt-1">
                                @foreach ($pv->attributeValues as $val)
                                  <span class="mr-2">
                                    <strong>{{ $val->attribute->name }}:</strong> {{ $val->value }}
                                  </span>
                                @endforeach
                              </div>
                            @endif
                          </div>
                        </div>
                      </td>

                      <td class="text-center font-weight-bold">{{ number_format($item->quantity) }}</td>
                      <td class="text-right">{!! price_format($order->currency->code, $item->price) !!}</td>
                      <td class="text-right pr-3 font-weight-bold">{!! price_format($order->currency->code, $item->subtotal) !!}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- Optional: Return Requests --}}
        @if ($order->returnRequests->count() > 0)
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-undo-alt mr-2 text-info"></i>Linked Return Requests</h3>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th class="pl-3">RMA #</th>
                      <th>Status</th>
                      <th>Qty</th>
                      <th class="text-right">Credit Value</th>
                      <th class="text-right pr-3">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($order->returnRequests as $rr)
                      @php
                        $badges = [
                            'requested' => 'warning',
                            'accepted' => 'info',
                            'in_transit' => 'primary',
                            'received' => 'purple',
                            'under_inspection' => 'warning',
                            'resolving' => 'info',
                            'completed' => 'success',
                            'rejected' => 'danger',
                        ];
                        $rrBadge = $badges[$rr->status] ?? 'secondary';
                      @endphp
                      <tr>
                        <td class="pl-3">
                          <strong>{{ $rr->reference_number }}</strong><br>
                          <small class="text-muted">{{ $rr->created_at->format('d M Y') }}</small>
                        </td>
                        <td><span class="badge badge-{{ $rrBadge }}">{{ strtoupper(str_replace('_', ' ', $rr->status)) }}</span></td>
                        <td>{{ $rr->items->sum('quantity') }}</td>
                        <td class="text-right">
                          {!! price_format($order->currency->code, $rr->items->sum(fn($i) => $i->quantity * $i->orderLineItem->price)) !!}
                        </td>
                        <td class="text-right pr-3">
                          <a href="{{ route('admin.sales.return-requests.show', $rr->id) }}" class="btn btn-xs btn-outline-primary">
                            Manage
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif
      </div>

      {{-- Right: Actions + Customer + Summary + Addresses + Map --}}
      <div class="col-lg-4">
        <div class="od-sticky">

          {{-- Actions --}}
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Actions</h3>
            </div>
            <div class="card-body">
              @if ($order->isDraft())
                <button class="btn btn-danger btn-block font-weight-bold archive-order-btn" data-id="{{ $order->id }}">
                  <i class="fas fa-archive mr-1"></i> Archive Order
                </button>
              @elseif($order->isPlaced())
                <button class="btn btn-success btn-block font-weight-bold mb-2" data-bs-toggle="modal"
                  data-bs-target="#confirmOrderModal">
                  <i class="fas fa-check-circle mr-1"></i> Confirm Order
                </button>
                <button class="btn btn-outline-danger btn-block font-weight-bold" data-bs-toggle="modal"
                  data-bs-target="#rejectOrderModal">
                  <i class="fas fa-times-circle mr-1"></i> Reject Order
                </button>
              @elseif($order->isConfirmed())
                <button class="btn btn-primary btn-block font-weight-bold mb-2" data-bs-toggle="modal"
                  data-bs-target="#fulfillOrderModal">
                  <i class="fas fa-box mr-1"></i> Mark as Fulfilled
                </button>
                <button class="btn btn-outline-secondary btn-block font-weight-bold" data-bs-toggle="modal"
                  data-bs-target="#cancelOrderModal">
                  <i class="fas fa-ban mr-1"></i> Cancel Order
                </button>
              @else
                <span class="text-muted small">No actions available for current status.</span>
              @endif
            </div>
          </div>

          {{-- Customer --}}
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-user mr-2 text-primary"></i>Customer</h3>
            </div>
            <div class="card-body">
              <div class="font-weight-bold">{{ $order->billingAddress->name ?? 'N/A' }}</div>

              <div class="d-flex align-items-center mt-2">
                <i class="far fa-envelope text-muted mr-2"></i>
                <span class="text-truncate mr-auto">{{ $order->email ?? 'N/A' }}</span>
                @if ($order->email)
                  <button class="btn btn-xs btn-link p-0 text-muted copy-btn" data-clipboard="{{ $order->email }}">
                    <i class="far fa-copy"></i>
                  </button>
                @endif
              </div>

              <div class="d-flex align-items-center mt-2">
                <i class="fas fa-phone text-muted mr-2"></i>
                <span class="mr-auto">{{ $order->billingAddress->phone ?? 'N/A' }}</span>
                @if ($order->billingAddress->phone)
                  <button class="btn btn-xs btn-link p-0 text-muted copy-btn"
                    data-clipboard="{{ $order->billingAddress->phone }}">
                    <i class="far fa-copy"></i>
                  </button>
                @endif
              </div>
            </div>
          </div>

          {{-- Order Summary --}}
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-receipt mr-2 text-success"></i>Order Summary</h3>
            </div>
            <div class="card-body">
              <div class="od-sum-row">
                <span class="text-muted">Subtotal</span>
                <span>{!! price_format($order->currency->code, $order->sub_total) !!}</span>
              </div>

              @if ($order->couponUsages->count() > 0)
                @foreach ($order->couponUsages as $couponUsage)
                  <div class="od-sum-row text-success">
                    <span>Discount ({{ $couponUsage->coupon->code }})</span>
                    <span>-{!! price_format($order->currency->code, $couponUsage->discount_amount) !!}</span>
                  </div>
                @endforeach
              @endif

              <div class="od-sum-row">
                <span class="text-muted">Tax</span>
                <span>{!! price_format($order->currency->code, $order->tax) !!}</span>
              </div>

              <div class="od-sum-row">
                <span class="text-muted">Shipping</span>
                <span>{!! price_format($order->currency->code, 0) !!}</span>
              </div>

              <div class="od-sum-row" style="border-bottom:0;">
                <span class="od-grand">Grand Total</span>
                <span class="od-grand text-success">{!! price_format($order->currency->code, $order->total) !!}</span>
              </div>

              <hr class="my-2">

              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="od-mini text-muted">Payment</div>
                  <div class="font-weight-bold">{{ strtoupper($order->payment_method) }}</div>
                  <div class="od-mini text-muted text-truncate">Ref: {{ $order->external_reference ?? 'N/A' }}</div>
                </div>
                <span class="badge badge-{{ $payBadge }}">{{ strtoupper($order->payment_status) }}</span>
              </div>
            </div>
          </div>

          {{-- Addresses + Map --}}
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>Addresses</h3>
            </div>
            <div class="card-body">
              <div class="od-mini text-muted font-weight-bold mb-1">Billing</div>
              <div class="od-mini">{!! $order->billingAddress?->render() ?? 'N/A' !!}</div>

              <hr class="my-2">

              <div class="d-flex align-items-center justify-content-between">
                <div class="od-mini text-muted font-weight-bold mb-1">Shipping</div>
                @if ($shippingAddress?->map_url)
                  <a href="{{ $shippingAddress->map_url }}" target="_blank" class="btn btn-xs btn-outline-danger">
                    <i class="fas fa-external-link-alt mr-1"></i> Open Map
                  </a>
                @endif
              </div>

              <div class="od-mini">{!! $shippingAddress?->render() ?? 'N/A' !!}</div>

              @if ($shippingAddress?->map_url)
                @php
                  $mapUrl = trim($shippingAddress->map_url);
                  $isLatLng = preg_match('/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/', $mapUrl);

                  if ($isLatLng) {
                      $embedSrc = 'https://www.google.com/maps?q=' . urlencode($mapUrl) . '&output=embed';
                  } else {
                      $embedSrc = 'https://www.google.com/maps?q=' . urlencode($mapUrl) . '&output=embed';
                  }
                @endphp

                <div class="mt-2">
                  <iframe class="od-map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    src="{{ $embedSrc }}"></iframe>
                </div>
              @endif
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- ===== Modals (keep your existing ones) ===== --}}
    {{-- Confirm --}}
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title font-weight-bold">Confirm Order #{{ $order->reference_number }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="confirmed">
            <div class="modal-body py-4 text-center">
              <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
              <p class="mb-0">Are you sure you want to <strong>Confirm</strong> this order?</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success px-4">Yes, Confirm Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Reject --}}
    <div class="modal fade" id="rejectOrderModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title font-weight-bold">Reject Order #{{ $order->reference_number }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <div class="modal-body py-4 text-center">
              <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
              <p class="mb-0 text-dark">Are you sure you want to <strong>Reject</strong> this order?</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger px-4">Yes, Reject Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Fulfill --}}
    <div class="modal fade" id="fulfillOrderModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title font-weight-bold">Fulfill Order #{{ $order->reference_number }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="fulfilled">
            <div class="modal-body">
              <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control" required>
              </div>
              <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">Shipping Provider</label>
                <input type="text" name="tracking_provider" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-dark">Tracking URL (Optional)</label>
                <input type="url" name="tracking_link" class="form-control">
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary px-4">Fulfill Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Cancel --}}
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title font-weight-bold">Cancel Order #{{ $order->reference_number }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="cancelled">
            <div class="modal-body py-4 text-center">
              <i class="fas fa-ban fa-4x text-secondary mb-3"></i>
              <p class="mb-0 text-dark">Are you sure you want to <strong>Cancel</strong> this order?</p>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Go Back</button>
              <button type="submit" class="btn btn-secondary px-4">Yes, Cancel Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  @push('scripts')
    <script>
      $(function() {
        // Copy to clipboard
        $(document).on('click', '.copy-btn', function() {
          const text = $(this).data('clipboard');
          const btn = $(this);
          const originalIcon = btn.find('i').attr('class');

          const tempInput = $('<input>');
          $('body').append(tempInput);
          tempInput.val(text).select();
          document.execCommand('copy');
          tempInput.remove();

          btn.find('i').attr('class', 'fas fa-check text-success');
          setTimeout(() => btn.find('i').attr('class', originalIcon), 1200);
        });

        // Archive order
        $(document).on('click', '.archive-order-btn', function() {
          if (!confirm('Are you sure you want to archive (soft delete) this order?')) return;
          const id = $(this).data('id');

          $.ajax({
            url: `{{ url('admin/sales/orders') }}/${id}`,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function() {
              window.location.href = "{{ route('admin.sales.orders.index') }}";
            },
            error: function() {
              alert('Failed to archive order.');
            }
          });
        });
      });
    </script>
  @endpush
@endsection