@extends('layouts.admin')
@section('title', 'নতুন বই যোগ করুন')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-2"></i>নতুন বই যোগ করুন</h3>
        <div class="card-tools"><a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> ফিরে যান</a></div>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group"><label>বইয়ের নাম *</label><input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="বইয়ের পূর্ণ নাম লিখুন"></div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>ক্যাটাগরি *</label><select name="category_id" class="form-control" required><option value="">নির্বাচন করুন</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>লেখক</label><select name="author_id" class="form-control"><option value="">নির্বাচন করুন</option>@foreach($authors as $a)<option value="{{ $a->id }}" {{ old('author_id')==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>প্রকাশনী</label><select name="publisher_id" class="form-control"><option value="">নির্বাচন করুন</option>@foreach($publishers as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div></div>
                        <div class="col-md-3"><div class="form-group"><label>প্রকাশের বছর</label><input type="number" name="publication_year" class="form-control" value="{{ old('publication_year') }}" min="1900" max="{{ date('Y') }}"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>পৃষ্ঠা সংখ্যা</label><input type="number" name="pages" class="form-control" value="{{ old('pages') }}" min="1"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>ভাষা</label><select name="language" class="form-control"><option value="Bengali" selected>বাংলা</option><option value="English">English</option><option value="Arabic">Arabic</option></select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>ISBN</label><input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}"></div></div>
                    </div>
                    <div class="form-group"><label>বইয়ের বিবরণ</label><textarea name="description" class="form-control" rows="4" placeholder="বইটি সম্পর্কে বিস্তারিত লিখুন...">{{ old('description') }}</textarea></div>
                    <div class="form-group"><label>ক্রয়ের লিংক</label><input type="url" name="buy_link" class="form-control" value="{{ old('buy_link') }}" placeholder="https://..."></div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked><label class="custom-control-label" for="is_active">সক্রিয়</label></div></div></div>
                        <div class="col-md-4"><div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" value="1"><label class="custom-control-label" for="is_featured">ফিচারড বই</label></div></div></div>
                        <div class="col-md-4"><div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" name="is_sponsored" class="custom-control-input" id="is_sponsored" value="1"><label class="custom-control-label" for="is_sponsored">স্পনসরড</label></div></div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>বইয়ের কভার</label>
                        <div style="border:2px dashed #e2e8f0;border-radius:8px;padding:20px;text-align:center;cursor:pointer" onclick="document.getElementById('coverInput').click()">
                            <img id="coverPreview" src="" style="max-width:100%;max-height:200px;display:none;margin-bottom:10px;border-radius:6px">
                            <div id="coverPlaceholder"><i class="fas fa-image fa-3x text-muted"></i><p class="mt-2 text-muted">কভার ছবি আপলোড করুন<br><small>JPG/PNG, max 5MB</small></p></div>
                            <input type="file" id="coverInput" name="cover_image" accept="image/*" style="display:none" onchange="previewImg(this,'coverPreview','coverPlaceholder')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-file-pdf text-danger"></i> PDF ফাইল</label>
                        <input type="file" name="pdf_file" class="form-control-file" accept=".pdf">
                        <small class="text-muted">সর্বোচ্চ 50MB</small>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-file-alt text-info"></i> EPUB ফাইল</label>
                        <input type="file" name="epub_file" class="form-control-file" accept=".epub">
                    </div>
                    <div class="form-group"><label>Meta Title (SEO)</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}"></div>
                    <div class="form-group"><label>Meta Description (SEO)</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description') }}</textarea></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> বই সংরক্ষণ করুন</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewImg(input, imgId, placeholderId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(imgId).src = e.target.result;
            document.getElementById(imgId).style.display = 'block';
            document.getElementById(placeholderId).style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
