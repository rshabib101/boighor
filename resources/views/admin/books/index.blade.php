@extends('layouts.admin')
@section('title', 'বই ম্যানেজমেন্ট')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-book mr-2"></i>সব বই</h3>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> নতুন বই</a>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:250px" placeholder="বইয়ের নাম খুঁজুন...">
            <select name="category" class="form-control" style="width:180px">
                <option value="">সব ক্যাটাগরি</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach
            </select>
            <button class="btn btn-secondary"><i class="fas fa-search"></i> খুঁজুন</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">রিসেট</a>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th style="width:60px">কভার</th><th>বইয়ের নাম</th><th>লেখক</th><th>ক্যাটাগরি</th><th>ডাউনলোড</th><th>রেটিং</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td><img src="{{ $book->cover_url }}" style="width:40px;height:55px;object-fit:cover;border-radius:4px"></td>
                        <td><strong>{{ Str::limit($book->title, 40) }}</strong><br><small class="text-muted">{{ $book->file_format }} · {{ $book->file_size_formatted }}</small></td>
                        <td>{{ $book->author?->name ?? '-' }}</td>
                        <td>{{ $book->category?->name }}</td>
                        <td>{{ number_format($book->download_count) }}</td>
                        <td>{{ $book->rating > 0 ? '⭐ ' . $book->rating : '-' }}</td>
                        <td><span class="badge badge-{{ $book->is_active ? 'success' : 'danger' }}">{{ $book->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span></td>
                        <td>
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-xs btn-secondary" target="_blank"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" style="display:inline" onsubmit="return confirm('মুছবেন কি?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">কোনো বই পাওয়া যায়নি</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $books->appends(request()->query())->links() }}
    </div>
</div>
@endsection
