@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="mb-0">Return Request #{{ $returnRequest->reference_number }}</h1>
      <p class="text-muted">Raised on {{ $returnRequest->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="col-sm-6 text-right">
      <a href="{{ route('admin.sales.orders.show', $returnRequest->order_id) }}" class="btn btn-outline-primary">
        <i class="fas fa-shopping-cart"></i> View Original Order
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <!-- Left Column: Details & Items -->
    <div class="col-md-8">
      <!-- Return Details -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <strong>Return Information</strong>
          <span class="badge badge-primary">Reason: {{ $returnRequest->reason->reason }}</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 mb-3">
              <label class="text-muted small uppercase font-weight-bold">Problem Description from Customer:</label>
              <div class="p-3 bg-light rounded border">
                {{ $returnRequest->description ?? 'No description provided.' }}
              </div>
            </div>
            <div class="col-sm-6">
              <p><strong>Refund Method:</strong>
                {{ $returnRequest->refund_method === 'account_credits' ? 'Account Credits' : 'Original Payment Method' }}
              </p>
            </div>
            <div class="col-sm-6 text-right">
              <p><strong>Refund Status:</strong>
                <span class="badge badge-{{ $returnRequest->refund_status === 'completed' ? 'success' : 'warning' }}">
                  {{ ucfirst($returnRequest->refund_status) }}
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Items to Return -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light"><strong>Items Requested for Return</strong></div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>Product</th>
                <th class="text-center">Orig Qty</th>
                <th class="text-center">Ret Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Return Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($returnRequest->items as $item)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="{{ $item->orderLineItem->productVariant->getThumbnail() }}" class="rounded mr-2"
                        style="width: 40px; height: 40px; object-fit: cover;">
                      <div>
                        <div class="font-weight-bold">
                          {{ $item->orderLineItem->productVariant->product->translation->name ?? 'Product' }}</div>
                        <div class="small text-muted">SKU: {{ $item->orderLineItem->productVariant->sku }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">{{ $item->orderLineItem->quantity }}</td>
                  <td class="text-center"><span class="badge badge-danger">{{ $item->quantity }}</span></td>
                  <td class="text-right">{{ money($item->orderLineItem->price) }}</td>
                  <td class="text-right font-weight-bold">{{ money($item->orderLineItem->price * $item->quantity) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Supporting Photos -->
      @if ($returnRequest->attachments->count() > 0)
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light"><strong>Customer Uploaded Photos</strong></div>
          <div class="card-body">
            <div class="row">
              @foreach ($returnRequest->attachments as $attachment)
                <div class="col-sm-4 mb-3">
                  <div class="img-thumbnail position-relative shadow-sm overflow-hidden" style="height: 200px;">
                    <a href="{{ $attachment->url }}" target="_blank">
                      <img src="{{ $attachment->url }}" class="img-fluid"
                        style="height: 100%; width: 100%; object-fit: cover;">
                      <div class="position-absolute bg-dark text-white px-2 py-1 small"
                        style="bottom: 0; right: 0; opacity: 0.8;">
                        <i class="fas fa-search-plus"></i> View Full
                      </div>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Right Column: Actions -->
    <div class="col-md-4">
      <!-- Status Timeline -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info"><strong>Status Timeline</strong></div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush small">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><i class="fas fa-plus-circle text-success mr-2"></i> Created</span>
              <span class="text-muted">{{ $returnRequest->created_at->format('d M Y, h:i A') }}</span>
            </li>
            @foreach (['approved', 'shipped', 'received', 'refunded'] as $status)
              @php $field = $status . '_at'; @endphp
              <li
                class="list-group-item d-flex justify-content-between align-items-center {{ $returnRequest->$field ? '' : 'text-muted' }}">
                <span>
                  <i
                    class="fas {{ $returnRequest->$field ? 'fa-check-circle text-success' : 'fa-circle-notch text-light' }} mr-2"></i>
                  {{ ucfirst($status) }}
                </span>
                @if ($returnRequest->$field)
                  <span class="text-muted">{{ $returnRequest->$field->format('d M Y, h:i A') }}</span>
                @else
                  <span>--</span>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <!-- Update Status Form -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary"><strong>Manage Request</strong></div>
        <div class="card-body">
          <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST"
            class="ajax-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label>Update Status</label>
              <select name="status" class="form-control">
                <option value="pending" {{ $returnRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $returnRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $returnRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="shipped" {{ $returnRequest->status === 'shipped' ? 'selected' : '' }}>Shipped (by
                  customer)</option>
                <option value="received" {{ $returnRequest->status === 'received' ? 'selected' : '' }}>Received</option>
                <option value="refunded" {{ $returnRequest->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
              </select>
            </div>

            <div class="form-group">
              <label>Shipping Cost Borne By</label>
              <select name="shipping_cost_borne_by" class="form-control">
                <option value="">-- Select --</option>
                <option value="company" {{ $returnRequest->shipping_cost_borne_by === 'company' ? 'selected' : '' }}>
                  Company</option>
                <option value="customer" {{ $returnRequest->shipping_cost_borne_by === 'customer' ? 'selected' : '' }}>
                  Customer</option>
              </select>
            </div>

            <div class="form-group">
              <label>Refund Status</label>
              <select name="refund_status" class="form-control">
                <option value="pending" {{ $returnRequest->refund_status === 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="completed" {{ $returnRequest->refund_status === 'completed' ? 'selected' : '' }}>Completed
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Internal Notes (Shared with customer)</label>
              <textarea name="admin_notes" class="form-control" rows="4">{{ $returnRequest->admin_notes }}</textarea>
            </div>

            <div class="form-group">
              <label>Shipping Label (PDF/Image)</label>
              <input type="file" name="shipping_label" class="form-control-file">
              @if ($returnRequest->shipping_label_path)
                <div class="mt-2 text-center">
                  <a href="{{ asset('storage/' . $returnRequest->shipping_label_path) }}" target="_blank"
                    class="btn btn-sm btn-outline-info btn-block">
                    <i class="fas fa-file-download"></i> View Loaded Label
                  </a>
                </div>
              @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block shadow">Update Request</button>
          </form>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light"><strong>Customer Contact</strong></div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary text-white rounded-circle p-2 mr-3"
              style="width: 40px; height: 40px; text-align: center;">
              {{ strtoupper(substr($returnRequest->user->name, 0, 1)) }}
            </div>
            <div>
              <div class="font-weight-bold">{{ $returnRequest->user->name }}</div>
              <div class="small text-muted">{{ $returnRequest->user->email }}</div>
            </div>
          </div>
          <p class="small mb-0"><strong>Return History:</strong> {{ $returnRequest->user->returnRequests()->count() }}
            requests total</p>
        </div>
      </div>
    </div>
  </div>
@endsection
