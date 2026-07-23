@extends('layouts.admin')
@section('title', 'বই সম্পাদনা')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-2"></i>বই সম্পাদনা: {{ Str::limit($book->title, 40) }}</h3>
        <div class="card-tools"><a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> ফিরে যান</a></div>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group"><label>বইয়ের নাম *</label><input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required></div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>ক্যাটাগরি *</label><select name="category_id" class="form-control" required>@foreach($categories as $c)<option value="{{ $c->id }}" {{ $book->category_id==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>লেখক</label><select name="author_id" class="form-control"><option value="">নির্বাচন করুন</option>@foreach($authors as $a)<option value="{{ $a->id }}" {{ $book->author_id==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>প্রকাশনী</label><select name="publisher_id" class="form-control"><option value="">নির্বাচন করুন</option>@foreach($publishers as $p)<option value="{{ $p->id }}" {{ $book->publisher_id==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach</select></div></div>
                        <div class="col-md-3"><div class="form-group"><label>প্রকাশের বছর</label><input type="number" name="publication_year" class="form-control" value="{{ $book->publication_year }}"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>পৃষ্ঠা</label><input type="number" name="pages" class="form-control" value="{{ $book->pages }}"></div></div>
                    </div>
                    <div class="form-group"><label>বিবরণ</label><textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea></div>
                    <div class="form-group"><label>ক্রয়ের লিংক</label><input type="url" name="buy_link" class="form-control" value="{{ old('buy_link', $book->buy_link) }}"></div>
                    <div class="row">
                        <div class="col-md-4"><div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ $book->is_active?'checked':'' }}><label class="custom-control-label" for="is_active">সক্রিয়</label></div></div>
                        <div class="col-md-4"><div class="custom-control custom-switch"><input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" value="1" {{ $book->is_featured?'checked':'' }}><label class="custom-control-label" for="is_featured">ফিচারড</label></div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>কভার ছবি (বর্তমান)</label>
                        <img src="{{ $book->cover_url }}" style="width:100%;max-height:200px;object-fit:contain;border-radius:8px;margin-bottom:10px;border:1px solid #e2e8f0">
                        <input type="file" name="cover_image" class="form-control-file" accept="image/*">
                    </div>
                    @if($book->pdf_path)
                    <div class="alert alert-info py-2 px-3"><i class="fas fa-check-circle mr-1"></i>PDF আপলোড করা আছে</div>
                    @endif
                    <div class="form-group"><label>নতুন PDF (ঐচ্ছিক)</label><input type="file" name="pdf_file" class="form-control-file" accept=".pdf"></div>
                    @if($book->epub_path)
                    <div class="alert alert-info py-2 px-3"><i class="fas fa-check-circle mr-1"></i>EPUB আপলোড করা আছে</div>
                    @endif
                    <div class="form-group"><label>নতুন EPUB (ঐচ্ছিক)</label><input type="file" name="epub_file" class="form-control-file" accept=".epub"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> আপডেট করুন</button>
            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-secondary" target="_blank"><i class="fas fa-eye"></i> সাইটে দেখুন</a>
        </form>
    </div>
</div>
@endsection
