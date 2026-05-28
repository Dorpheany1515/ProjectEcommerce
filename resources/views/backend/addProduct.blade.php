@extends('backend.master')

{{-- ១. កំណត់ Title ឱ្យបានត្រឹមត្រូវ --}}
@section('site-title')
    Admin | {{ isset($getCate) ? 'Edit Post' : 'Add Post' }}
@endsection

@section('page-main-title')
    {{ isset($getCate) ? 'Edit Post' : 'Add Post' }}
@endsection

{{-- ២. ចាប់ផ្តើម Section Content --}}
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="col-xl-12">
                <form action="{{ route('addProductSubmit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                       @if(session('success'))
                            <div class="alert alert-success text-dark alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card-body">
                            @if (isset($getCate))
                                <input type="hidden" name="id" value="{{ $getCate->id }}">
                            @endif
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="product_name" value="{{ old('product_name', $getCate->product_name ?? '') }}" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Quantity</label>
                                    <input class="form-control" type="number" min="1" name="qty" value="{{ old('qty', $getCate->qty ?? '') }}" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Regular Price</label>
                                    <input class="form-control" type="text" name="regular_price" value="{{ old('regular_price', $getCate->regular_price ?? '') }}" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Sale Price</label>
                                    <input class="form-control" type="number" min="1" name="sale_price" value="{{ old('sale_price', $getCate->sale_price ?? '') }}"/>
                                </div>
                               <<div class="mb-3 col-6">
                                <label class="form-label">Category</label>
                                <select name="cate_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($category as $categoryItem)
                                        <option value="{{ $categoryItem->id }}" {{ (isset($getCate) && $getCate->cate_id == $categoryItem->id) ? 'selected' : '' }}>
                                            {{ $categoryItem->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label text-danger">Image</label>
                                    <input class="form-control" type="file" name="image" id="image"/>
                                    @if (isset($getCate))
                                        <input type="hidden" name="old_image" value="{{ $getCate->image }}">
                                    @endif
                                </div>
                                <div class="mb-3 col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" cols="30" rows="10">{{ old('description', $getCate->description ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" id="btnAdd" name="btn" value="Add Product" class="btn btn-primary">Add Product</button>
                                <button type="submit" id="btnEdit" name="btn" value="Edit Product" class="btn btn-success">Edit Product</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection {{-- ត្រូវបិទ Section Content នៅទីនេះមុននឹងសរសេរកូដផ្សេង --}}

{{-- ៣. ដាក់ JavaScript ក្នុង Section ផ្ទាល់ខ្លួន (ប្រសិនបើ master.blade មាន yield('script')) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        const url = window.location.href;
        // ពិនិត្យមើលពាក្យ edit-product ក្នុង Link ដើម្បីប្តូរឈ្មោះប៊ូតុង
        if(url.includes('edit-product')){
            $('#title').html('Edit Product');
            $('#btnAdd').hide();
            $('#btnEdit').show();
        } else {
            $('#title').html('Add Product');
            $('#btnAdd').show();
            $('#btnEdit').hide();
        }
    });
</script>