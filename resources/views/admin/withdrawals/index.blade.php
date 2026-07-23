@extends('layouts.admin')
@section('title', 'উইড্র ম্যানেজমেন্ট')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i>উইড্র রিকোয়েস্ট</h3>
        <div class="card-tools">
            <form method="GET" style="display:flex;gap:8px">
                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">সব</option>
                    <option value="pending" {{ request('status')==='pending'?'selected':'' }}>পেন্ডিং</option>
                    <option value="approved" {{ request('status')==='approved'?'selected':'' }}>অনুমোদিত</option>
                    <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>বাতিল</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead><tr><th>ইউজার</th><th>পয়েন্ট</th><th>টাকা</th><th>মাধ্যম</th><th>অ্যাকাউন্ট</th><th>তারিখ</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td>{{ $w->user->name }}<br><small class="text-muted">{{ $w->user->mobile }}</small></td>
                    <td>{{ number_format($w->points) }}</td>
                    <td>৳{{ $w->amount }}</td>
                    <td>{{ ucfirst($w->payment_method) }}</td>
                    <td>{{ $w->account_number }}</td>
                    <td>{{ $w->created_at->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $w->status === 'approved' ? 'success' : ($w->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ $w->status === 'approved' ? 'অনুমোদিত' : ($w->status === 'rejected' ? 'বাতিল' : 'পেন্ডিং') }}
                    </span></td>
                    <td>
                        @if($w->status === 'pending')
                        <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-xs btn-success" onclick="return confirm('অনুমোদন করবেন?')"><i class="fas fa-check"></i> অনুমোদন</button>
                        </form>
                        <button class="btn btn-xs btn-danger" onclick="rejectModal({{ $w->id }})"><i class="fas fa-times"></i> বাতিল</button>
                        @else
                        <small class="text-muted">{{ $w->admin_note }}</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">কোনো উইড্র রিকোয়েস্ট নেই</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $withdrawals->links() }}
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModalDiv"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5>উইড্র বাতিল করুন</h5><button class="close" data-dismiss="modal">&times;</button></div>
    <form id="rejectForm" method="POST">
        @csrf
        <div class="modal-body">
            <div class="form-group"><label>কারণ লিখুন *</label><textarea name="admin_note" class="form-control" rows="3" required placeholder="বাতিলের কারণ লিখুন..."></textarea></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
            <button type="submit" class="btn btn-danger">বাতিল করুন</button>
        </div>
    </form>
</div></div></div>
@endsection
@push('scripts')
<script>
function rejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/withdrawals/' + id + '/reject';
    $('#rejectModalDiv').modal('show');
}
</script>
@endpush
