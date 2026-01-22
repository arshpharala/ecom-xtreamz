@extends('theme.xtremez.layouts.app')

@section('content')
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="mb-0">Order #{{ $order->reference_number }}</h3>
        <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
      </div>
      <div class="d-flex gap-2">
        @php
          $returnableItems = $order->lineItems->filter(fn($li) => $li->getReturnableQuantity() > 0);
        @endphp

        @if ($order->canBeReturned() && $returnableItems->count() > 0)
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newReturnModal">
            <i class="bi bi-arrow-return-left"></i> Request Return
          </button>
        @endif

        <a href="{{ route('customers.profile', ['tab' => 'order']) }}" class="btn btn-outline-secondary">&larr; Back to
          Orders</a>
      </div>
    </div>

    @includeIf('theme.xtremez.customers.orders._new_return_modal')

    <div class="row">
      <div class="col-md-8">
        <!-- Order Status and Tracking Card -->
        <div class="card card-outline card-primary mb-4">
          <div class="card-header">
            <h3 class="card-title">Order Info</h3>
            <div class="card-tools">
              <span
                class="badge badge-{{ $order->status === 'delivered'
                    ? 'success'
                    : ($order->status === 'shipped'
                        ? 'info'
                        : ($order->status === 'cancelled'
                            ? 'danger'
                            : 'warning')) }}">
                {{ strtoupper($order->status) }}
              </span>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">{{ ucfirst($order->payment_status) }}</h5>
                  <span class="description-text">PAYMENT STATUS</span>
                </div>
              </div>
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">{{ ucfirst($order->payment_method) ?? 'N/A' }}</h5>
                  <span class="description-text">PAYMENT METHOD</span>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">{{ $order->currency->code ?? strtoupper(active_currency()) }}</h5>
                  <span class="description-text">CURRENCY</span>
                </div>
              </div>
            </div>

            @if ($order->tracking_number)
              <hr>
              <div class="mt-3">
                <h5><i class="bi bi-truck"></i> Tracking Information</h5>
                <div class="row">
                  <div class="col-md-4">
                    <p class="mb-1 text-muted small">Tracking Number</p>
                    <strong>{{ $order->tracking_number }}</strong>
                  </div>
                  <div class="col-md-4">
                    <p class="mb-1 text-muted small">Tracking Status</p>
                    <span class="badge badge-info">{{ $order->tracking_status ?? 'N/A' }}</span>
                  </div>
                  <div class="col-md-4">
                    @if ($order->tracking_link)
                      <p class="mb-1 text-muted small">Action</p>
                      <a href="{{ $order->tracking_link }}" target="_blank" class="btn btn-xs btn-outline-primary">Track
                        Order</a>
                    @endif
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Order Items Card -->
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 font-weight-bold fw-bold">Order Items</h5>
            <div class="card-tools d-flex gap-2">
              <div class="input-group input-group-sm" style="width: 200px;">
                <input type="text" id="orderItemSearch" class="form-control" placeholder="Search items...">
              </div>
              <span class="badge badge-primary">{{ $order->lineItems->count() }} Items</span>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
              <table class="table table-hover table-valign-middle mb-0" id="orderItemsTable">
                <thead class="bg-light sticky-top" style="z-index: 10;">
                  <tr>
                    <th style="width: 50%;">Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right text-end">Unit Price</th>
                    <th class="text-right text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->lineItems as $item)
                    <tr class="order-item-row">
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="{{ $item->productVariant->getThumbnail() }}" alt=""
                            style="width:40px; height:40px; object-fit:cover;" class="rounded border mr-3 me-3">
                          <div class="min-width-0">
                            <div class="font-weight-bold fw-bold text-truncate order-item-name"
                              title="{{ $item->productVariant->product->translation->name ?? 'Product' }}">
                              {{ $item->productVariant->product->translation->name ?? 'Product' }}
                            </div>
                            <div class="small text-muted text-truncate">
                              @forelse ($item->productVariant->attributeValues as $val)
                                <span class="me-2">{{ $val->attribute->name }}:
                                  <strong>{{ $val->value }}</strong></span>
                              @empty
                              @endforelse
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="text-center align-middle">{{ $item->quantity }}</td>
                      <td class="text-right text-end align-middle">{!! price_format($order->currency->code, $item->price) !!}</td>
                      <td class="text-right text-end align-middle font-weight-bold fw-bold">{!! price_format($order->currency->code, $item->subtotal) !!}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('orderItemSearch');
            if (searchInput) {
              searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.order-item-row');
                rows.forEach(row => {
                  const name = row.querySelector('.order-item-name').textContent.toLowerCase();
                  row.style.display = name.includes(query) ? '' : 'none';
                });
              });
            }
          });
        </script>
      </div>

      <div class="col-md-4">
        <!-- Billing Details Card -->
        <div class="card mb-4">
          <div class="card-header bg-light">
            <h3 class="card-title">Billing Details</h3>
          </div>
          <div class="card-body">
            <p class="mb-1"><strong>{{ $order->billingAddress->name ?? 'N/A' }}</strong></p>
            <p class="mb-1 text-muted small"><i class="bi bi-envelope"></i> {{ $order->email ?? 'N/A' }}</p>
            <p class="mb-3 text-muted small"><i class="bi bi-telephone"></i> {{ $order->billingAddress->phone ?? 'N/A' }}
            </p>
            @if ($order->billingAddress)
              <div class="bg-light p-2 rounded small">
                {!! $order->billingAddress->render() !!}
              </div>
            @endif
          </div>
        </div>

        <!-- Summary Card -->
        <div class="card mb-4">
          <div class="card-header bg-light">
            <h3 class="card-title">Order Summary</h3>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <span>Subtotal</span>
                <span>{!! price_format($order->currency->code, $order->sub_total) !!}</span>
              </li>

              @if ($order->couponUsages->count() > 0)
                @foreach ($order->couponUsages as $couponUsage)
                  <li class="list-group-item d-flex justify-content-between text-success">
                    <span>Coupon ({{ $couponUsage->coupon->code }})</span>
                    <span>-{!! price_format($order->currency->code, $couponUsage->discount_amount) !!}</span>
                  </li>
                @endforeach
              @endif

              <li class="list-group-item d-flex justify-content-between">
                <span>Tax</span>
                <span>{!! price_format($order->currency->code, $order->tax) !!}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between bg-light">
                <span class="h5 mb-0">Total</span>
                <span class="h5 mb-0 font-weight-bold fw-bold">{!! price_format($order->currency->code, $order->total) !!}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    {{-- Return Requests --}}
    @if ($order->returnRequests->count())
      <div class="card card-outline card-dark shadow-sm">
        <div class="card-header">
          <h3 class="card-title text-dark fw-bold"><i class="bi bi-arrow-return-left me-2"></i> Return Requests</h3>
        </div>

        <div class="card-body">
          @foreach ($order->returnRequests as $return)
            <div class="card shadow-sm border-0 mb-4 overflow-hidden return-request-card"
              id="return-{{ $return->id }}">
              <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-muted small fw-bold text-uppercase">Return Request</span>
                  <h5 class="mb-0 fw-bold">#{{ $return->reference_number }}</h5>
                </div>

                @php
                  $statusColors = [
                      'requested' => 'bg-light text-dark border',
                      'accepted' => 'bg-info',
                      'rejected' => 'bg-danger',
                      'in_transit' => 'bg-primary',
                      'received' => 'bg-purple',
                      'under_inspection' => 'bg-warning',
                      'resolving' => 'bg-indigo text-white',
                      'completed' => 'bg-success text-white',
                  ];
                  $badgeClass = $statusColors[$return->status] ?? 'bg-secondary';
                @endphp

                <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase">
                  {{ str_replace('_', ' ', $return->status) }}
                </span>
              </div>

              <div class="card-body">
                <div class="row">
                  <!-- Progress Tracker (Timeline) -->
                  <div class="col-md-5 border-right border-end">
                    <div class="timeline">
                      @php
                        $allSteps = [
                            'requested' => [
                                'title' => 'Request Submitted',
                                'desc' => 'We have received your return request.',
                                'icon' => 'bi-send',
                                'color' => 'bg-info',
                            ],
                            'accepted' => [
                                'title' => 'Request Accepted',
                                'desc' => 'Your return has been approved for shipment.',
                                'icon' => 'bi-check-circle',
                                'color' => 'bg-info',
                            ],
                            'in_transit' => [
                                'title' => 'With Shipping Company',
                                'desc' => 'Item is with the carrier.',
                                'icon' => 'bi-truck',
                                'color' => 'bg-primary',
                            ],
                            'received' => [
                                'title' => 'Fulfillment Center',
                                'desc' => 'Item received at logistics center.',
                                'icon' => 'bi-box-seam',
                                'color' => 'bg-indigo',
                            ],
                            'inspection' => [
                                'title' => 'Quality Inspection',
                                'desc' => 'Team is checking condition.',
                                'icon' => 'bi-search',
                                'color' => 'bg-warning',
                            ],
                            'completed' => [
                                'title' => 'Resolution Finalized',
                                'desc' => 'Refund/replacement is complete.',
                                'icon' => 'bi-flag-fill',
                                'color' => 'bg-success',
                            ],
                        ];

                        $currentStatus = $return->status;
                      @endphp

                      @foreach ($allSteps as $key => $step)
                        @php
                          $isDone = false;
                          $isCurrent = false;

                          if ($key === 'requested') {
                              $isDone = true;
                          }
                          if (
                              $key === 'accepted' &&
                              in_array($currentStatus, [
                                  'accepted',
                                  'in_transit',
                                  'received',
                                  'under_inspection',
                                  'resolving',
                                  'completed',
                              ])
                          ) {
                              $isDone = true;
                          }
                          if (
                              $key === 'in_transit' &&
                              in_array($currentStatus, [
                                  'in_transit',
                                  'received',
                                  'under_inspection',
                                  'resolving',
                                  'completed',
                              ])
                          ) {
                              $isDone = true;
                          }
                          if (
                              $key === 'received' &&
                              in_array($currentStatus, ['received', 'under_inspection', 'resolving', 'completed'])
                          ) {
                              $isDone = true;
                          }
                          if (
                              $key === 'inspection' &&
                              in_array($currentStatus, ['under_inspection', 'resolving', 'completed'])
                          ) {
                              $isDone = true;
                          }
                          if ($key === 'completed' && $currentStatus === 'completed') {
                              $isDone = true;
                          }

                          if (
                              $key === $currentStatus ||
                              ($key === 'inspection' && in_array($currentStatus, ['under_inspection', 'resolving']))
                          ) {
                              $isCurrent = true;
                          }
                        @endphp

                        <div>
                          <i class="bi {{ $step['icon'] }} {{ $isDone ? $step['color'] : 'bg-gray' }}"></i>
                          <div class="timeline-item">
                            <h3
                              class="timeline-header {{ $isCurrent ? 'text-primary font-weight-bold fw-bold' : '' }} border-0">
                              {{ $step['title'] }}
                            </h3>
                            <div class="timeline-body small text-muted pt-0">
                              {{ $step['desc'] }}
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>

                  <!-- Instructions & Details -->
                  <div class="col-md-7 ps-4">
                    <div class="instruction-panel mb-4">
                      @if ($return->status === 'requested')
                        <div class="callout callout-warning bg-light">
                          <h6 class="fw-bold">Awaiting Review</h6>
                          <p class="small text-muted mb-0">
                            Our team is reviewing your request. You'll receive an email once it's accepted.
                          </p>
                        </div>
                      @elseif($return->status === 'accepted')
                        <div class="callout callout-info bg-light">
                          <h6 class="fw-bold text-info mb-3"><i class="bi bi-truck me-2"></i> Next Step: Ship the item
                          </h6>
                          <p class="small mb-3">Please pack the item securely and ship it to the following address:</p>

                          <div class="p-3 bg-white border rounded small mb-3 shadow-sm">
                            <strong>Xtremez Return Center</strong><br>
                            123 Warehouse St, Logistics Park<br>
                            Dubai, UAE
                          </div>

                          @if ($return->shipping_label_path)
                            <div class="mb-3">
                              <a href="{{ asset('storage/' . $return->shipping_label_path) }}" target="_blank"
                                class="btn btn-primary btn-sm btn-block">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Download Return Label
                              </a>
                            </div>
                          @else
                            <div class="alert alert-info py-2 small mb-0 border-0">
                              <i class="bi bi-info-circle mr-2"></i>
                              No shipping label provided. Please use your own carrier.
                            </div>
                          @endif
                        </div>
                      @elseif($return->status === 'rejected')
                        <div class="callout callout-danger bg-light">
                          <h6 class="fw-bold text-danger mb-2">Request Rejected</h6>
                          <p class="small mb-0">
                            {{ $return->admin_notes ?? 'Unfortunately, your request does not meet our return policy criteria.' }}
                          </p>
                        </div>
                      @else
                        <div class="mb-4">
                          <label class="text-muted small fw-bold text-uppercase d-block mb-1">Items in this
                            return</label>
                          <div class="d-flex flex-wrap gap-2">
                            @foreach ($return->items as $ritem)
                              <div class="p-1 border rounded d-flex align-items-center" style="max-width: 200px;">
                                <img src="{{ $ritem->orderLineItem->productVariant->getThumbnail() }}"
                                  class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                <span class="small text-truncate"
                                  title="{{ $ritem->orderLineItem->productVariant->product->translation->name }}">
                                  {{ $ritem->orderLineItem->productVariant->product->translation->name }}
                                </span>
                                <span class="ms-1 badge bg-light text-dark small">x{{ $ritem->quantity }}</span>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endif

                      {{-- Attachments --}}
                      @if ($return->attachments && $return->attachments->count())
                        <div class="d-flex flex-wrap gap-2">
                          @foreach ($return->attachments as $att)
                            <div class="attachment-preview position-relative">
                              <a href="{{ $att->url }}" target="_blank" title="View full image">
                                <img src="{{ $att->url }}" class="img-thumbnail"
                                  style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                              </a>
                            </div>
                          @endforeach
                        </div>
                      @endif
                    </div>

                    @if ($return->customer_tracking_number)
                      <div class="mt-4 p-3 bg-white border rounded shadow-sm">
                        <h6 class="fw-bold mb-2 small text-uppercase text-muted">Logistics Info</h6>
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <span class="text-muted small">Carrier:</span>
                            <span class="fw-bold ms-1">{{ $return->carrier_name ?? 'Not specified' }}</span>
                          </div>
                          <div>
                            <span class="text-muted small">Tracking #:</span>
                            <span class="fw-bold ms-1 text-primary">{{ $return->customer_tracking_number }}</span>
                          </div>
                        </div>
                      </div>
                    @endif

                    @if ($return->admin_notes && $return->status !== 'rejected')
                      <div class="mt-4 p-3 bg-light border-left border-info rounded-end shadow-sm border-start border-5">
                        <p class="mb-1 small text-uppercase text-info fw-bold">
                          <i class="bi bi-chat-left-dots me-1"></i> Admin Response
                        </p>
                        <div class="small text-dark">{{ $return->admin_notes }}</div>
                      </div>
                    @endif

                  </div> {{-- col --}}
                </div> {{-- row --}}
              </div> {{-- card-body --}}
            </div> {{-- return card --}}
          @endforeach
        </div>
      </div>
    @endif

  </div>

@endsection
