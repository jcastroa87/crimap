@if($entry->status == 'pending')
<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $entry->id }}" title="Reject this report">
    <i class="la la-times"></i> Reject
</button>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $entry->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $entry->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel{{ $entry->id }}">Reject Crime Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('crime-report.reject', $entry->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="admin_notes">Reason for rejection (optional)</label>
            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Please provide a reason why this report is being rejected..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Reject Report</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
