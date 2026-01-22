@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Return Request #{{ $returnRequest->reference_number }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.sales.return-requests.index') }}">Return Requests</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">

      <!-- Left Column: Main Information -->
      <div class="col-md-9">

        <!-- Status Callout -->
        <div
          class="callout callout-{{ $returnRequest->status === 'completed' ? 'success' : ($returnRequest->status === 'rejected' ? 'danger' : 'info') }}">
          <h5>Current Status: {{ strtoupper(str_replace('_', ' ', $returnRequest->status)) }}</h5>
          <p>
            Request created on {{ $returnRequest->created_at->format('F d, Y h:i A') }} by
            {{ $returnRequest->user->name }}.
            @if ($returnRequest->status === 'requested')
              Waiting for admin approval.
            @endif
          </p>
        </div>

        <!-- Items Table -->
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Requested Items</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-striped projects">
              <thead>
                <tr>
                  <th style="width: 40%">Product</th>
                  <th class="text-center">Return Qty</th>
                  <th class="text-right">Unit Price</th>
                  <th class="text-right">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($returnRequest->items as $item)
                  <tr>
                    <td>
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm"
                          src="{{ $item->orderLineItem->productVariant->getThumbnail() }}" alt="Product Image">
                        <span class="username">
                          <a href="#">{{ $item->orderLineItem->productVariant->product->translation->name }}</a>
                        </span>
                        <span class="description">
                          Variant: {{ $item->orderLineItem->productVariant->name }}<br>
                          SKU: {{ $item->orderLineItem->productVariant->sku }}
                        </span>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge badge-warning" style="font-size: 1.2em;">{{ $item->quantity }}</span>
                    </td>
                    <td class="text-right">
                      {!! price_format(active_currency(), $item->orderLineItem->price) !!}
                    </td>
                    <td class="text-right font-weight-bold">
                      {!! price_format(active_currency(), $item->orderLineItem->price * $item->quantity) !!}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Images & Inspection -->
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer Attachments</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  @forelse($returnRequest->attachments as $attachment)
                    <div class="col-sm-4">
                      <a href="{{ $attachment->url }}" target="_blank" data-toggle="lightbox" data-title="Attachment">
                        <img src="{{ $attachment->url }}" class="img-fluid mb-2" alt="Attachment" />
                      </a>
                    </div>
                  @empty
                    <div class="col-12 text-muted">No attachments provided.</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            @if ($returnRequest->inspection_status !== 'pending')
              <div
                class="card card-{{ $returnRequest->inspection_status === 'passed' ? 'success' : 'danger' }} card-outline">
                <div class="card-header">
                  <h3 class="card-title">Inspection Results</h3>
                </div>
                <div class="card-body">
                  <strong>Status:</strong> <span
                    class="badge badge-{{ $returnRequest->inspection_status === 'passed' ? 'success' : 'danger' }}">{{ strtoupper($returnRequest->inspection_status) }}</span>
                  <p class="mt-2">{{ $returnRequest->inspection_notes }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Timeline History -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Activity Timeline</h3>
          </div>
          <div class="card-body">
            <div class="timeline">
              @foreach ($returnRequest->timelines as $timeline)
                <div>
                  <i
                    class="fas {{ $timeline->actor_type === 'user' ? 'fa-user bg-info' : 'fa-user-shield bg-primary' }}"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock"></i>
                      {{ $timeline->created_at->format('d M, h:i A') }}</span>
                    <h3 class="timeline-header">
                      <strong>{{ $timeline->actor_name }}</strong>: {{ $timeline->title }}
                    </h3>
                    @if ($timeline->remarks)
                      <div class="timeline-body">
                        {{ $timeline->remarks }}
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
              <div>
                <i class="fas fa-clock bg-gray"></i>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Right Column: Actions -->
      <div class="col-md-3">

        <!-- Action Box -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Action Center</h3>
          </div>
          <div class="card-body">
            <label>Return Reason</label>
            <p class="text-muted">{{ $returnRequest->reason->reason ?? 'Other' }}</p>
            <hr>
            <label>Customer Comments</label>
            <p class="text-muted small">{{ $returnRequest->comments }}</p>

            <hr>
            <label class="d-block mb-3">Recommended Action</label>

            @if ($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_REQUESTED)
              <button type="button" class="btn btn-success btn-block mb-2 btn-action-trigger" data-type="accept"
                data-title="Accept Request" data-header-class="bg-success" data-btn-class="btn-success"
                data-instructions="Approve this return request. You can optionally upload a Shipping Label here.">
                <i class="fas fa-check-circle mr-1"></i> Accept Request
              </button>
              <button type="button" class="btn btn-danger btn-block btn-sm btn-action-trigger" data-type="reject"
                data-title="Reject Return Request" data-header-class="bg-danger" data-btn-class="btn-danger"
                data-instructions="Provide a clear reason for rejecting this request. The customer will be notified.">
                Reject Request
              </button>
            @elseif($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_ACCEPTED)
              <div class="alert alert-light border small">
                Waiting for customer to ship.
              </div>
              <button type="button" class="btn btn-primary btn-block btn-action-trigger" data-type="mark_shipped"
                data-title="Seller Update: In Transit" data-instructions="Mark as In Transit if tracking is confirmed.">
                <i class="fas fa-truck mr-1"></i> Update Status to In Transit
              </button>
            @elseif($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_IN_TRANSIT)
              <button type="button" class="btn btn-primary btn-block btn-action-trigger" data-type="mark_received"
                data-title="Confirm Logistics Receipt" data-header-class="bg-purple" data-btn-class="btn-purple"
                data-instructions="Fulfillment Center Confirmation: Confirm item has arrived at warehouse.">
                <i class="fas fa-box-open mr-1"></i> Confirm Logistics Receipt
              </button>
            @elseif($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_RECEIVED)
              <button type="button" class="btn btn-warning btn-block btn-action-trigger" data-type="start_inspection"
                data-title="Start Inspection" data-instructions="Begin quality control check at Fulfillment Center.">
                <i class="fas fa-search mr-1"></i> Start Inspection
              </button>
            @elseif($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_INSPECTION)
              <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal"
                data-bs-target="#inspectionModal">
                <i class="fas fa-clipboard-check mr-1"></i> Record Results
              </button>
            @elseif($returnRequest->status === \App\Models\Sales\ReturnRequest::STATUS_RESOLVING)
              <button type="button" class="btn btn-success btn-block btn-action-trigger" data-type="complete"
                data-title="Initiate Refund / Resolution"
                data-instructions="Finalize the resolution and initiate refund or replacement.">
                <i class="fas fa-check-double mr-1"></i> Initiate Refund
              </button>
            @endif

          </div>
        </div>

        <!-- Admin Notes (Sticky) -->
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Internal Notes</h3>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group">
                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Sticky notes...">{{ $returnRequest->admin_notes }}</textarea>
              </div>
              <div class="form-group">
                <label>Ref #</label>
                <input type="text" name="refund_reference" class="form-control"
                  value="{{ $returnRequest->refund_reference }}">
              </div>
              <button type="submit" class="btn btn-sm btn-secondary btn-block">Save Notes</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modals -->
  @include('theme.adminlte.sales.return-requests.partials._inspection_modal')
  @include('theme.adminlte.sales.return-requests.partials._action_modal')
@endsection
