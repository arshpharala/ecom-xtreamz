@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><i class="fas fa-envelope-open-text mr-2"></i> Enquiry Details</h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="{{ route('admin.cms.contact-submissions.index') }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <!-- Sender Information -->
    <div class="col-md-4">
      <div class="card card-primary card-outline shadow-sm">
        <div class="card-body box-profile">
          <div class="text-center mb-3">
            <div
              class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary shadow-sm"
              style="width: 80px; height: 80px; font-size: 32px;">
              <i class="fas fa-user"></i>
            </div>
          </div>
          <h3 class="profile-username text-center font-weight-bold">{{ $submission->name }}</h3>
          <p class="text-muted text-center"><i class="fas fa-envelope mr-1"></i> {{ $submission->email }}</p>

          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item border-top-0">
              <b>Phone</b> <a class="float-right text-dark">{{ $submission->phone ?? 'N/A' }}</a>
            </li>
            <li class="list-group-item">
              <b>Subject</b> <a class="float-right text-dark font-weight-600">{{ $submission->subject }}</a>
            </li>
            <li class="list-group-item">
              <b>IP Address</b> <a class="float-right text-muted small">{{ $submission->ip_address ?? 'N/A' }}</a>
            </li>
            <li class="list-group-item">
              <b>User Account</b>
              <span class="float-right">
                @if ($submission->user_id)
                  <span class="badge badge-info"><i class="fas fa-check-circle mr-1"></i> Registered</span>
                @else
                  <span class="badge badge-secondary">Guest</span>
                @endif
              </span>
            </li>
            <li class="list-group-item border-bottom-0">
              <b>Submitted At</b> <a
                class="float-right text-muted small">{{ $submission->created_at->format('M d, Y H:i:s') }}</a>
            </li>
          </ul>

          <div class="mt-4">
            <h6 class="text-uppercase text-muted small font-weight-bold mb-2">Email Notification Status</h6>
            @if ($submission->notified_at)
              <div class="alert alert-success py-2 px-3 mb-0" style="font-size: 13px;">
                <i class="fas fa-check-circle mr-1"></i> Notification sent on
                {{ $submission->notified_at->format('M d, Y H:i') }}
              </div>
            @else
              <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 13px;">
                <i class="fas fa-clock mr-1"></i> Notification Pending
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button type="button" class="btn btn-danger btn-block btn-delete shadow-sm"
          data-url="{{ route('admin.cms.contact-submissions.destroy', $submission->id) }}">
          <i class="fas fa-trash-alt mr-1"></i> Delete Enquiry
        </button>
      </div>
    </div>

    <!-- Message Body -->
    <div class="col-md-8">
      <div class="card card-primary shadow-sm h-100">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-comment-dots mr-2"></i> Message Content</h3>
        </div>
        <div class="card-body bg-white p-4">
          <div class="p-3 bg-light rounded border shadow-sm h-100"
            style="white-space: pre-wrap; font-size: 16px; line-height: 1.6; color: #444; min-height: 400px;">
            {{ $submission->message }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .font-weight-600 {
      font-weight: 600;
    }

    .box-profile .list-group-item {
      background: transparent;
    }
  </style>
@endpush
