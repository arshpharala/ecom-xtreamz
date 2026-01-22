<!-- Inspection Modal -->
<div class="modal fade" id="inspectionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="fas fa-microscope mr-2"></i> Record Quality Inspection</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST"
        class="ajax-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="action_type" value="record_inspection">

        <div class="modal-body">
          <div class="form-group mb-4">
            <label class="fw-bold mb-2">Inspection Outcome</label>
            <div class="d-flex g-3">
              <div class="custom-control custom-radio mr-4">
                <input class="custom-control-input" type="radio" id="pass" name="inspection_status"
                  value="passed" checked>
                <label for="pass" class="custom-control-label text-success fw-bold">PASSED</label>
              </div>
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="fail" name="inspection_status"
                  value="failed">
                <label for="fail" class="custom-control-label text-danger fw-bold">FAILED</label>
              </div>
            </div>
          </div>

          <div class="form-group mb-4">
            <label class="fw-bold mb-2">Select Resolution Type</label>
            <select name="resolution_type" class="form-control select2">
              <option value="refund">Issue Refund</option>
              <option value="replacement">Send Replacement</option>
              <option value="store_credit">Add Store Credit</option>
            </select>
          </div>

          <div class="form-group">
            <label class="fw-bold mb-2">Detailed Condition Notes</label>
            <textarea name="inspection_notes" class="form-control" rows="4"
              placeholder="Describe the item condition, any damage found, etc."></textarea>
            <small class="text-muted italic">These notes are for internal warehouse records.</small>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4 fw-bold">Submit Inspection</button>
        </div>
      </form>
    </div>
  </div>
</div>
