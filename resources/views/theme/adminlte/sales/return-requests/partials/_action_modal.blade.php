<!-- Generic Action Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header text-white" id="modalHeader">
        <h5 class="modal-title fw-bold" id="modalTitle">Action</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST"
        class="ajax-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="action_type" id="modalActionType">

        <div class="modal-body">
          <div id="modalInstructions" class="alert alert-light py-2 px-3 small mb-4 border shadow-none"></div>

          <!-- File Upload Container (Explicitly for Shipping Labels) -->
          <div class="form-group d-none" id="shippingLabelContainer">
            <label class="fw-bold mb-2">Upload Shipping Label (PDF/Image)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="shippingLabel" name="shipping_label"
                accept="image/*,.pdf">
              <label class="custom-file-label" for="shippingLabel">Choose file</label>
            </div>
            <small class="text-muted italic d-block mt-2">Upload the label here. The customer will be able to download
              it.</small>
          </div>

          <div class="form-group mb-0">
            <label class="fw-bold mb-2" id="modalNoteLabel">Add Note for Customer (Optional)</label>
            <textarea name="admin_notes" class="form-control" rows="4"
              placeholder="Ex: Your request has been approved. Please follow the shipping instructions..."></textarea>
            <small class="text-muted italic d-block mt-2">The customer will see this note in their order
              history.</small>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn px-4 fw-bold" id="modalSubmitBtn">Confirm Action</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
  <script src="{{ asset('theme/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      // 1. Bind actions first (Critical Path)
      $('.btn-action-trigger').on('click', function() {
        const type = $(this).data('type');
        const title = $(this).data('title');
        const instructions = $(this).data('instructions');
        const btnClass = $(this).data('btn-class') || 'btn-primary';
        const headerClass = $(this).data('header-class') || 'bg-primary';
        const noteLabel = $(this).data('note-label') || 'Add Note for Customer (Optional)';
        const placeholder = $(this).data('placeholder') || '';

        $('#modalActionType').val(type);
        $('#modalTitle').text(title);
        $('#modalInstructions').text(instructions);
        $('#modalSubmitBtn').attr('class', 'btn px-4 fw-bold ' + btnClass).text(title);
        $('#modalHeader').attr('class', 'modal-header text-white ' + headerClass);
        $('#modalNoteLabel').text(noteLabel);
        $('#actionModal textarea').attr('placeholder', placeholder);

        // Show/Hide Shipping Label Input based on action
        if (type === 'accept') {
          $('#shippingLabelContainer').removeClass('d-none');
        } else {
          $('#shippingLabelContainer').addClass('d-none');
        }

        $('#actionModal').modal('show');
      });

      // 2. Initialize optional plugins with safety check
      if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
      }
    });
  </script>
@endpush
