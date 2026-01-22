<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-bold"><i class="fas fa-times-circle mr-2"></i> Reject Return Request</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST"
        class="ajax-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="action_type" value="reject">

        <div class="modal-body">
          <p class="text-muted mb-4">You are about to reject this request. This action will notify the customer. Please
            provide a clear reason.</p>

          <div class="form-group">
            <label class="fw-bold mb-2">Reason for Rejection</label>
            <textarea name="admin_notes" class="form-control" rows="4" required
              placeholder="Ex: Item is outside the 30-day return window."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger px-4 fw-bold">Reject Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
