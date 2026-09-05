@extends('layouts.app')
@section('title', 'Post an Ad')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 w-full">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Post an Ad</h1>
        <p class="text-slate-500">Fill in the details to publish your listing.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc pl-4 text-sm">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 p-7 flex flex-col gap-6 shadow-soft">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Ad Title *</label>
            <input name="title" type="text" value="{{ old('title') }}" placeholder="e.g. iPhone 15 Pro Max 256GB" required class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary" />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">Category *</label>
                <select name="category_id" required class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20">
                    <option value="">Choose category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">Location *</label>
                <select name="location_id" required class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20">
                    <option value="">Choose location</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">Price (₦) *</label>
                <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" required class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">Condition *</label>
                <select name="condition_state" required class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20">
                    <option value="new" {{ old('condition_state') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="used" {{ old('condition_state', 'used') === 'used' ? 'selected' : '' }}>Used</option>
                    <option value="refurbished" {{ old('condition_state') === 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Description *</label>
            <textarea name="description" rows="5" required placeholder="Describe your item in detail..." class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Brand (optional)</label>
            <input name="brand" type="text" value="{{ old('brand') }}" placeholder="e.g. Apple, Samsung, Toyota" class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Images (up to 6)</label>
            <div class="border-2 border-dashed border-slate-200 hover:border-primary rounded-xl p-8 text-center cursor-pointer transition-colors" onclick="document.getElementById('imageInput').click()">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2 block">add_photo_alternate</span>
                <p class="text-sm text-slate-500">Click to upload images</p>
                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP up to 5MB each</p>
            </div>
            <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden" />
            <div id="preview" class="flex flex-wrap gap-2 mt-3"></div>
        </div>
        <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all">Post Ad Now</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function() {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative w-16 h-16 rounded-lg overflow-hidden border border-slate-200';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
