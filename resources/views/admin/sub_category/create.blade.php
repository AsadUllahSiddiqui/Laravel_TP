
@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid my-2">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Create Sub Category</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="subcategory.html" class="btn btn-primary">Back</a>
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
                  <option value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach

                  @endif

                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="slug">Slug</label>
                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" >
                  <option value="1">Active</option>
                  <option value="0">Block</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
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
      url: '{{ route("sub-categories.store") }}',
      type: 'post',
      data: element.serializeArray(),
      dataType: 'json',
      success: function(response) {
        // if (response["status"] == true) {
          window.location.href = "{{ route('sub-categories.index') }}"; // Redirect to the index page
        // }
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


    Dropzone.autoDiscover = false;
  const dropzone = $("#image").dropzone({
      init: function() {
          this.on('addedfile', function(file) {
              if (this.files.length > 1) {
                  this.removeFile(this.files[0]);
              }
          });
      },
      url:  "{{ route('temp-images.create') }}",
      maxFiles: 1,
      paramName: 'image',
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/gif",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
      }, success: function(file, response){
          $("#image_id").val(response.image_id);
          //console.log(response)
      }
  });


  </script>

@endsection

