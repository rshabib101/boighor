@extends('layouts.admin')
@section('title', 'লেখক ম্যানেজমেন্ট')
@section('content')
<div class="row">
<div class="col-md-8">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>সব লেখক</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead><tr><th>ছবি</th><th>নাম</th><th>জাতীয়তা</th><th>বই সংখ্যা</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
                <tbody>
                    @forelse($authors as $author)
                    <tr>
                        <td><img src="{{ $author->image_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover"></td>
                        <td><strong>{{ $author->name }}</strong><br><small class="text-muted"><code>{{ $author->slug }}</code></small></td>
                        <td>{{ $author->nationality ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $author->books_count }}</span></td>
                        <td><span class="badge badge-{{ $author->is_active ? 'success' : 'secondary' }}">{{ $author->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span></td>
                        <td>
                            <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" style="display:inline" onsubmit="return confirm('মুছবেন?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">কোনো লেখক পাওয়া যায়নি</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $authors->links() }}</div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-2"></i>নতুন লেখক যোগ করুন</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.authors.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group"><label>লেখকের নাম *</label><input type="text" name="name" class="form-control" required placeholder="যেমন: হুমায়ূন আহমেদ"></div>
                <div class="form-group"><label>জাতীয়তা</label><input type="text" name="nationality" class="form-control" value="Bangladeshi" placeholder="যেমন: Bangladeshi"></div>
                <div class="form-group"><label>জীবনী / বিবরণ</label><textarea name="bio" class="form-control" rows="3" placeholder="লেখকের সংক্ষিপ্ত পরিচিতি..."></textarea></div>
                <div class="form-group"><label>লেখকের ছবি</label><input type="file" name="image" class="form-control-file" accept="image/*"></div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">লেখক সক্রিয় রাখুন</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> সংরক্ষণ করুন</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
