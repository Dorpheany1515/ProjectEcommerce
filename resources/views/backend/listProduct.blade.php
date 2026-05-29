@extends('backend.master')
@section('content')
<div class="content-wrapper">
    @section('site-title')
      Admin | List Post
    @endsection
    @section('page-main-title')
      List Post
    @endsection

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
          <div class="table-responsive text-nowrap">
            <table class="table text-center">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Qty</th>
                  <th>Regular Price</th> 
                  <th>Sale Price</th>   
                  <th>Views</th>
                  <th>Category</th>
                  <th>Admin</th>
                  <th>Image</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($products as $product)
                <tr>
                  <td><strong>{{ $product->id }}</strong></td>
                  <td>{{ $product->product_name }}</td>
                  <td>{{ $product->qty }}</td>
                  <td>{{ $product->regular_price }}$</td>         
                  <td>{{ $product->sale_price }}$</td>
                  <td>{{ $product->views }}</td>
                  <td>
                      {{ $post->category->category_name ?? 'No Category' }}
                  </td>
                  <td>
                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center justify-content-center">
                       <img width="40" src="{{ $product->profile }}" alt="Admin" class="rounded-circle">
                    </ul>
                  </td>
                  <td>
                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center justify-content-center">
                       <img width="40" src="{{ $product->image }}" alt="Product" class="rounded">
                    </ul>
                  </td>
                  <td>
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('editProduct', $product->id) }}">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                          </a>
                          <a class="dropdown-item remove-post-key" data-value="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#basicModal" href="javascript:void(0);">
                            <i class="bx bx-trash me-1"></i> Delete
                          </a>
                        </div>
                      </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="mt-3 d-flex justify-content-end">
            {{ $products->links() }}
        </div>
        <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
          <form action="{{ route('deleteProduct') }}" method="post">
            @csrf
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Are you sure to remove this post?</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                  <input type="hidden" id="remove-val" name="remove_id">
                  <button type="submit" class="btn btn-danger">Confirm</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('.remove-post-key').click(function(){
            var id = $(this).attr('data-value');
            $('#remove-val').val(id);
        });
    });
</script>
@endsection