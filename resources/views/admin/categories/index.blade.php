@extends('layouts.admin')
@section('title', 'ক্যাটাগরি ম্যানেজমেন্ট')
@section('content')
<div class="row">
<div class="col-md-8">
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-th-large mr-2"></i>সব ক্যাটাগরি</h3></div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead><tr><th>আইকন</th><th>নাম</th><th>ইংরেজি নাম</th><th>Slug</th><th>বই সংখ্যা</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td><div style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:{{ $cat->color }}20;color:{{ $cat->color }}"><i class="{{ $cat->icon }}"></i></div></td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td>{{ $cat->name_en }}</td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td>{{ $cat->books_count }}</td>
                    <td><span class="badge badge-{{ $cat->is_active ? 'success' : 'secondary' }}">{{ $cat->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span></td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline" onsubmit="return confirm('মুছবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-3 text-muted">কোনো ক্যাটাগরি নেই</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
<div class="col-md-4">
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-2"></i>নতুন ক্যাটাগরি</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group"><label>বাংলা নাম *</label><input type="text" name="name" class="form-control" required placeholder="যেমন: ইসলামিক"></div>
            <div class="form-group"><label>ইংরেজি নাম *</label><input type="text" name="name_en" class="form-control" required placeholder="যেমন: Islamic"></div>
            <div class="form-group"><label>আইকন (Font Awesome class)</label><input type="text" name="icon" class="form-control" placeholder="fas fa-moon"></div>
            <div class="form-group"><label>রঙ</label><input type="color" name="color" class="form-control" value="#6366f1" style="height:40px"></div>
            <div class="form-group"><label>ক্রম</label><input type="number" name="sort_order" class="form-control" value="{{ $nextOrder }}"></div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> সংরক্ষণ করুন</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
