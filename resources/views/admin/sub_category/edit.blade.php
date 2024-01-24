
@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid my-2">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Sub Category</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- Main content -->

  <section class="content">
    <!-- Default box -->
    <div class="container-fluid">
      <form action="" method="post" id="subCategoryForm" name="subCategoryForm">
        @csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="name">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                  @if ($categories->isNotEmpty())

                  @foreach ( $categories as $category )
                  <option {{ ($subCategory->category_id == $category->id ) ? 'selected' : '' }} value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach

                  @endif

                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" value="{{$subCategory->name}}" name="name" id="name" class="form-control" placeholder="Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="slug">Slug</label>
                <input type="text" value="{{$subCategory->slug}}"readonly name="slug" id="slug" class="form-control" placeholder="Slug">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" >
                  <option {{ ($subCategory->status == 1 ) ? 'selected' : '' }} value="1">Active</option>
                  <option {{ ($subCategory->status == 0 ) ? 'selected' : '' }}  value="0">Block</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
      </div>
    </form>
    </div>
    <!-- /.card -->
  </section>
  <!-- /.content -->
</div>

@endsection

@section('customJs')

<script>

  $("#subCategoryForm").submit(function(event) {
    console.log("event")
    event.preventDefault();
    var element = $(this);
    $.ajax({
      url: '{{ route("sub-categories.update","$subCategory->id") }}',
      type: 'put',
      data: element.serializeArray(),
      dataType: 'json',
      success: function(response) {
        if (response["status"] == true) {
          window.location.href = "{{ route('sub-categories.index') }}"; // Redirect to the index page
        }
      },
      error: function(jqXHR, exception) {
        console.log('something went wrong');
      }
    });
  });

    $('#name').change(function(){
      $element = $(this);
      $.ajax({
        url: '{{ route("getSlug") }}',
        type: 'get',
        data: {'title': $element.val()},
        dataType: 'json',
        success: function(response){
          if(response['status'] == 'true'){
            $("#slug").val(response["slug"]);
          }
        }
      });

    });

  </script>

@endsection

