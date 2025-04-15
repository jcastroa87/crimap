@if($entry->status == 'pending')
<a href="{{ route('crime-report.approve', $entry->id) }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Approve this report">
    <i class="la la-check"></i> Approve
</a>
@endif
