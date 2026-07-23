@extends('layouts.admin')
@section('title', 'লেখক সম্পাদনা')
@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-edit mr-2"></i>লেখক সম্পাদনা: {{ $author->name }}</h3>
            <a href="{{ route('admin.authors.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> ফিরে যান</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.authors.update', $author) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-group"><label>লেখকের নাম *</label><input type="text" name="name" class="form-control" value="{{ old('name', $author->name) }}" required></div>
                <div class="form-group"><label>জাতীয়তা</label><input type="text" name="nationality" class="form-control" value="{{ old('nationality', $author->nationality) }}"></div>
                <div class="form-group"><label>জীবনী / বিবরণ</label><textarea name="bio" class="form-control" rows="4">{{ old('bio', $author->bio) }}</textarea></div>
                <div class="form-group">
                    <label>বর্তমান ছবি</label><br>
                    <img src="{{ $author->image_url }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover" class="mb-2"><br>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ $author->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">লেখক সক্রিয় রাখুন</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> আপডেট করুন</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
