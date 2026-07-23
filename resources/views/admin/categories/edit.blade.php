@extends('layouts.admin')
@section('title', 'ক্যাটাগরি সম্পাদনা')
@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-edit mr-2"></i>ক্যাটাগরি সম্পাদনা: {{ $category->name }}</h3>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> ফিরে যান</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf @method('PUT')
                <div class="form-group"><label>বাংলা নাম *</label><input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required></div>
                <div class="form-group"><label>ইংরেজি নাম</label><input type="text" name="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}"></div>
                <div class="form-group"><label>আইকন (Font Awesome class)</label><input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}"></div>
                <div class="form-group"><label>রঙ</label><input type="color" name="color" class="form-control" value="{{ old('color', $category->color ?: '#6366f1') }}" style="height:40px"></div>
                <div class="form-group"><label>ক্রম</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}"></div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">ক্যাটাগরিটি সক্রিয় রাখুন</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> আপডেট করুন</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
