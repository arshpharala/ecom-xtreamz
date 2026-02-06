@extends('theme.adminlte.layouts.app')

@push('css')
  <style>
    .card-premium {
      transition: all 0.3s ease;
      border-top: 3px solid transparent !important;
    }

    .card-premium:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .accent-primary {
      border-top-color: #007bff !important;
    }

    .accent-success {
      border-top-color: #28a745 !important;
    }

    .accent-danger {
      border-top-color: #dc3545 !important;
    }

    .accent-info {
      border-top-color: #17a2b8 !important;
    }

    .accent-dark {
      border-top-color: #343a40 !important;
    }

    .luxury-summary-row {
      padding: 12px 0;
      border-bottom: 1px dashed #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .luxury-summary-row:last-child {
      border-bottom: none;
    }

    .grand-total-luxury {
      background: #f8f9fa;
      margin: 15px -20px -20px -20px;
      padding: 25px;
      border-radius: 0 0 8px 8px;
      border-top: 2px solid #343a40;
    }

    .slack-remark {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.2s;
    }

    .slack-remark:hover {
      background: #f9f9f9;
    }

    .remark-avatar {
      width: 36px;
      height: 36px;
      border-radius: 6px;
      background: #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: #555;
      text-transform: uppercase;
      font-size: 0.8rem;
    }

    .custom-badge-luxury {
      font-size: 0.65rem;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-white {
      background: #fff;
      border: 1px solid #dee2e6;
      color: #495057;
    }

    .btn-white:hover {
      background: #f8f9fa;
    }
  </style>
@endpush

@section('content-header')
  <div class="container-fluid">
    <div class="row mb-3 align-items-center">
      <div class="col-sm-6">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-1 small text-uppercase font-weight-bold">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.sales.orders.index') }}" class="text-muted">Sales</a></li>
            <li class="breadcrumb-item active text-primary" aria-current="page">Order Details</li>
          </ol>
        </nav>
        <h1 class="m-0 font-weight-bold">Order #{{ $order->order_number }}</h1>
        <p class="text-muted small mb-0"><i class="far fa-calendar-alt mr-1"></i> Placed on
          {{ $order->created_at->format('d M Y, h:i A') }}</p>
      </div>
      <div class="col-sm-6 text-right">
        <div class="btn-group shadow-sm">
          <a href="#" class="btn btn-white btn-sm" target="_blank">
            <i class="fas fa-print mr-1"></i> Print
          </a>
          <a href="#" class="btn btn-white btn-sm" target="_blank">
            <i class="fas fa-file-pdf mr-1"></i> PDF
          </a>
          <a href="{{ route('admin.sales.orders.index') }}" class="btn btn-white btn-sm">
            <i class="fas fa-list mr-1"></i> List
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <!-- Top Stats Grid -->
  <div class="row">
    <!-- Customer Details -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-premium accent-primary h-100 shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="card-title text-muted mb-0 font-weight-bold uppercase"
            style="font-size: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-user-circle mr-1 text-primary"></i> Customer
          </h6>
        </div>
        <div class="card-body pt-2">
          <h5 class="mb-2 font-weight-extrabold text-dark">{{ $order->billingAddress->name }}</h5>
          <div class="d-flex align-items-center mb-1 small">
            <i class="far fa-envelope mr-2 text-muted" style="width: 14px;"></i>
            <span class="text-truncate mr-auto">{{ $order->email ?? 'N/A' }}</span>
            @if ($order->email)
              <button class="btn btn-xs btn-link p-0 text-muted copy-btn" data-clipboard="{{ $order->email }}"
                title="Copy Email">
                <i class="far fa-copy"></i>
              </button>
            @endif
          </div>
          <div class="d-flex align-items-center small">
            <i class="fas fa-phone mr-2 text-muted" style="width: 14px;"></i>
            <span class="mr-auto">{{ $order->billingAddress->phone ?? 'N/A' }}</span>
            @if ($order->billingAddress->phone)
              <button class="btn btn-xs btn-link p-0 text-muted copy-btn"
                data-clipboard="{{ $order->billingAddress->phone }}" title="Copy Phone">
                <i class="far fa-copy"></i>
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Details -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-premium accent-success h-100 shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="card-title text-muted mb-0 font-weight-bold uppercase"
            style="font-size: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-credit-card mr-1 text-success"></i> Payment
          </h6>
        </div>
        <div class="card-body pt-2">
          <div class="mb-2">
            @php
              $payBadge =
                  $order->payment_status === 'paid'
                      ? 'success'
                      : ($order->payment_status === 'refunded'
                          ? 'danger'
                          : 'warning');
            @endphp
            <span class="badge badge-{{ $payBadge }} px-3 py-1 rounded-pill" style="font-size: 0.65rem;">
              {{ strtoupper($order->payment_status) }}
            </span>
          </div>
          <div class="small mb-1"><span class="text-muted">Method:</span> <span
              class="font-weight-bold">{{ strtoupper($order->payment_method) }}</span></div>
          <div class="small mb-1 text-truncate"><span class="text-muted">Ref:</span> <span
              class="text-dark">{{ $order->external_reference ?? 'N/A' }}</span></div>
          <div class="small text-muted" style="font-size: 0.7rem;"><i class="far fa-clock mr-1"></i>
            {{ $order->updated_at ? $order->updated_at->format('d M, h:i A') : 'N/A' }}</div>
        </div>
      </div>
    </div>

    <!-- Billing Address -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-premium accent-danger h-100 shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="card-title text-muted mb-0 font-weight-bold uppercase"
            style="font-size: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-map-marker-alt mr-1 text-danger"></i> Shipping/Billing
          </h6>
        </div>
        <div class="card-body pt-2 overflow-auto">
          <address class="small mb-0 text-dark" style="line-height: 1.4;">
            {!! $order->address->render() !!}
          </address>
        </div>
      </div>
    </div>

    <!-- Order Management -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-premium accent-dark h-100 shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between">
          <h6 class="card-title text-muted mb-0 font-weight-bold uppercase"
            style="font-size: 0.7rem; letter-spacing: 0.5px;">
            <i class="fas fa-cog mr-1 text-dark"></i> Actions
          </h6>
        </div>
        <div class="card-body pt-2">
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST" class="ajax-form mb-3">
            @csrf
            @method('PUT')
            <div class="input-group input-group-sm mb-2 shadow-none">
              <select name="status" class="form-control border-right-0" style="border-radius: 4px 0 0 4px;">
                @foreach (['draft', 'placed', 'processing', 'confirmed', 'fulfilled', 'cancelled'] as $s)
                  <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}
                  </option>
                @endforeach
              </select>
              <div class="input-group-append">
                <button type="submit" class="btn btn-dark btn-sm font-weight-bold px-3"
                  style="border-radius: 0 4px 4px 0;">Apply</button>
              </div>
            </div>
          </form>

          <div class="d-flex gap-2">
            @if ($order->status === 'placed')
              <button class="btn btn-sm btn-success flex-grow-1 mr-1 font-weight-bold" data-toggle="modal"
                data-target="#confirmOrderModal">
                Confirm
              </button>
              <button class="btn btn-sm btn-outline-danger flex-grow-1 font-weight-bold" data-toggle="modal"
                data-target="#cancelOrderModal">
                Cancel
              </button>
            @else
              <div class="bg-light rounded p-2 text-center w-100">
                <span class="text-muted small d-block font-weight-bold">Current Status</span>
                <span class="text-dark font-weight-bold">{{ strtoupper($order->status) }}</span>
              </div>
            @endif
          </div>

          <!-- Delivery Date Integration -->
          @if ($order->status === 'confirmed' || $order->status === 'fulfilled')
            <hr class="my-3">
            <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST" class="ajax-form">
              @csrf
              @method('PUT')
              <div class="form-group mb-0">
                <label class="small font-weight-bold mb-1">Set Delivery Date</label>
                <div class="input-group input-group-sm">
                  <input type="datetime-local" name="delivered_at" class="form-control"
                    value="{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d\TH:i') : '' }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-outline-primary">Update</button>
                  </div>
                </div>
                <small class="text-muted" style="font-size: 0.65rem;">Starts the 30-day return window.</small>
              </div>
            </form>
          @endif

          @if ($order->delivered_at)
            <div class="mt-2 text-success small font-weight-bold">
              <i class="fas fa-truck-loading mr-1"></i> Delivered: {{ $order->delivered_at->format('d M, h:i A') }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Main Content: Items Table -->
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h5 class="card-title mb-0 font-weight-bold text-dark">Order Items</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0 vertical-align-middle custom-order-table">
              <thead class="bg-light">
                <tr>
                  <th class="border-0 px-4">Product Details</th>
                  <th class="border-0 text-center">Qty</th>
                  <th class="border-0 text-right">Unit Price</th>
                  <th class="border-0 text-right px-4">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->lineItems as $item)
                  <tr>
                    <td class="px-4 py-3">
                      <div class="d-flex align-items-center">
                        <img src="{{ $item->productVariant->getThumbnail() }}" class="rounded mr-3"
                          style="width: 48px; height: 48px; object-fit: cover; border: 1px solid #eee;">
                        <div>
                          <p class="mb-0 font-weight-bold text-dark">
                            {{ $item->productVariant->product->translation->name ?? 'Product' }}</p>
                          <div class="small text-muted mb-1">
                            <span
                              class="badge badge-light border">{{ $item->productVariant->product->category->translation->name ?? 'Category' }}</span>
                            <span class="ml-1">SKU: {{ $item->productVariant->sku ?? '#' }}</span>
                          </div>
                          <!-- Attributes -->
                          <div class="mb-1">
                            @foreach ($item->productVariant->attributeValues as $val)
                              <span class="badge badge-light border-0 py-1 px-2 mr-1"
                                style="font-size: 0.7rem;">{{ $val->attribute->name }}: {{ $val->value }}</span>
                            @endforeach
                          </div>

                          <!-- Branding Notes Refined -->
                          @if (isset($item->options['customization']))
                            <div class="mt-2">
                              <div class="custom-badge-luxury bg-light d-inline-block">
                                <span class="font-weight-bold text-info text-uppercase mr-2"
                                  style="font-size: 0.6rem; letter-spacing: 0.5px;">Branding</span>
                                @if (!empty($item->options['customization']['text']))
                                  <span class="text-dark">"{{ $item->options['customization']['text'] }}"</span>
                                @endif
                                @if ($item->attachments->count() > 0)
                                  <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach ($item->attachments as $attachment)
                                      <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                                        target="_blank" class="d-inline-block">
                                        <img
                                          src="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                                          class="rounded shadow-sm"
                                          style="width: 28px; height: 28px; object-fit: cover; border: 1px solid #fff;">
                                      </a>
                                    @endforeach
                                  </div>
                                @endif
                              </div>
                            </div>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="text-center font-weight-bold">{{ number_format($item->quantity) }}</td>
                    <td class="text-right text-muted">{!! price_format($order->currency->code, $item->price) !!}</td>
                    <td class="text-right px-4 font-weight-bold text-dark">{!! price_format($order->currency->code, $item->subtotal) !!}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Related Return Requests Integration Card Premium -->
      @if ($order->returnRequests->count() > 0)
        <div class="card card-premium shadow-sm border-0 mt-4 mb-4 overflow-hidden">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 font-weight-bold text-dark"><i class="fas fa-undo-alt mr-2 text-info"></i>Linked
              Return Requests</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table mb-0 vertical-align-middle custom-order-table">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 px-4">RMA #</th>
                    <th class="border-0">Status</th>
                    <th class="border-0">Qty</th>
                    <th class="border-0 text-right">Credit Value</th>
                    <th class="border-0 text-right px-4">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->returnRequests as $rr)
                    <tr>
                      <td class="px-4 py-3"><strong>{{ $rr->reference_number }}</strong><br><small
                          class="text-muted">{{ $rr->created_at->format('d M Y') }}</small></td>
                      <td>
                        @php
                          $badges = [
                              'requested' => 'badge-warning',
                              'accepted' => 'badge-info',
                              'in_transit' => 'badge-primary',
                              'received' => 'badge-purple',
                              'under_inspection' => 'badge-warning',
                              'resolving' => 'badge-info',
                              'completed' => 'badge-success',
                              'rejected' => 'badge-danger',
                          ];
                        @endphp
                        <span
                          class="badge {{ $badges[$rr->status] ?? 'badge-secondary' }} font-weight-normal px-2 py-1">
                          {{ strtoupper(str_replace('_', ' ', $rr->status)) }}
                        </span>
                      </td>
                      <td>{{ $rr->items->sum('quantity') }} items</td>
                      <td class="text-right">{!! price_format($order->currency->code, $rr->items->sum(fn($i) => $i->quantity * $i->orderLineItem->price)) !!}</td>
                      <td class="text-right px-4">
                        <a href="{{ route('admin.sales.return-requests.show', $rr->id) }}"
                          class="btn btn-xs btn-outline-primary rounded-pill px-3">
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

    <!-- Right Column: Summary & Remarks -->
    <div class="col-lg-4">
      <!-- Order Summary Card -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="card-title mb-0 font-weight-bold text-dark">Order Summary</h5>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal</span>
            <span>{!! price_format($order->currency->code, $order->sub_total) !!}</span>
          </div>
          @if ($order->couponUsages->count() > 0)
            @foreach ($order->couponUsages as $couponUsage)
              <div class="d-flex justify-content-between mb-2 text-success">
                <span>Discount ({{ $couponUsage->coupon->code }})</span>
                <span>-{!! price_format($order->currency->code, $couponUsage->discount_amount) !!}</span>
              </div>
            @endforeach
          @endif
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Tax</span>
            <span>{!! price_format($order->currency->code, $order->tax) !!}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Shipping</span>
            <span>{!! price_format($order->currency->code, 0) !!}</span>
          </div>
          <hr class="my-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold">Grand Total</h5>
            <h4 class="mb-0 font-weight-bold text-primary">{!! price_format($order->currency->code, $order->total) !!}</h4>
          </div>
        </div>
      </div>

      <!-- Internal Remarks Slack-Style -->
      <div class="card card-premium shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0 font-weight-bold text-dark">Internal Remarks</h5>
          <span class="badge badge-pill badge-light border px-2 text-muted"
            style="font-size: 0.7rem;">{{ $order->comments->count() }} total</span>
        </div>
        <div class="card-body p-0">
          <!-- Timeline -->
          <div class="timeline-container p-4" style="max-height: 450px; overflow-y: auto;">
            @forelse($order->comments->sortByDesc('created_at') as $comment)
              <div class="slack-remark">
                <div class="remark-avatar">
                  {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(strafter($comment->user->name ?? '', ' '), 0, 1)) }}
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center mb-1">
                    <span class="font-weight-bold text-dark mr-2"
                      style="font-size: 0.85rem;">{{ $comment->user->name ?? 'Administrator' }}</span>
                    <small class="text-muted"
                      style="font-size: 0.65rem;">{{ $comment->created_at->format('h:i A') }}</small>
                  </div>
                  <div class="remark-content text-muted mb-1" style="font-size: 0.8rem; line-height: 1.5;">
                    {!! nl2br(e($comment->content)) !!}
                  </div>
                  @if ($comment->user_id === auth()->id())
                    <button class="btn btn-link text-danger p-0 delete-remark" data-id="{{ $comment->id }}"
                      style="font-size: 0.65rem; text-decoration: none;">Delete</button>
                  @endif
                </div>
              </div>
            @empty
              <div class="text-center py-5">
                <i class="far fa-comment-dots fa-3x text-light mb-3"></i>
                <p class="text-muted small mb-0">No internal remarks yet.</p>
              </div>
            @endforelse
          </div>

          <!-- Add Remark Form Luxury -->
          <div class="p-4 border-top bg-white rounded-bottom">
            <form action="{{ route('admin.cms.remarks.store') }}" method="POST">
              @csrf
              <input type="hidden" name="commentable_id" value="{{ $order->id }}">
              <input type="hidden" name="commentable_type" value="{{ get_class($order) }}">
              <div class="form-group mb-3">
                <textarea name="content" class="form-control form-control-sm bg-light border-0" rows="3"
                  placeholder="Jot down a note or internal reminder..." required
                  style="resize: none; border-radius: 12px; padding: 12px;"></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold py-2 shadow-sm"
                style="border-radius: 10px;">
                <i class="fas fa-paper-plane mr-2"></i> Post Remark
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <!-- Confirm Modal -->
  <div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title font-weight-bold">Confirm Order #{{ $order->order_number }}</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="confirmed">
          <div class="modal-body py-4 text-center">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <p class="mb-0">Are you sure you want to <strong>Confirm</strong> this order?<br>This will notify the
              customer and move the order to the processing stage.</p>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4">Yes, Confirm Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Cancel Modal -->
  <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title font-weight-bold">Cancel Order #{{ $order->order_number }}</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="cancelled">
          <div class="modal-body py-4 text-center">
            <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
            <p class="mb-0 text-dark">Are you sure you want to <strong>Cancel</strong> this order?<br><span
                class="text-danger small font-weight-bold">This action cannot be undone.</span></p>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-dismiss="modal">Go Back</button>
            <button type="submit" class="btn btn-danger px-4">Yes, Cancel Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      $(function() {
        // Copy to clipboard
        $('.copy-btn').on('click', function() {
          const text = $(this).data('clipboard');
          const btn = $(this);
          const originalIcon = btn.find('i').attr('class');

          const tempInput = $('<input>');
          $('body').append(tempInput);
          tempInput.val(text).select();
          document.execCommand('copy');
          tempInput.remove();

          btn.find('i').attr('class', 'fas fa-check text-success');
          setTimeout(() => {
            btn.find('i').attr('class', originalIcon);
          }, 1500);
        });

        // Delete remark
        $(document).on('click', '.delete-remark', function() {
          if (!confirm('Are you sure you want to delete this remark?')) return;
          const btn = $(this);
          const id = btn.data('id');
          $.ajax({
            url: `{{ url('admin/cms/remarks') }}/${id}`,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function() {
              btn.closest('.remark-item').fadeOut(300, function() {
                $(this).remove();
              });
            },
            error: function() {
              alert('Failed to delete remark.');
            }
          });
        });
      });
    </script>
  @endpush
@endsection
