
@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid my-2">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Category</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="container-fluid">
      <form action="{{ route('categories.store') }}" method="post" id="categoryForm" name="categoryForm">
        @csrf
        <div class="card">
          <div class="card-body">
            <div class="col">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="name">Name</label>
                  <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}" placeholder="Name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="slug">Slug</label>
                  <input type="text" readonly name="slug" id="slug" value="{{$category->slug}}" class="form-control" placeholder="Slug">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status">Status</label>
                  <select name="status" id="status" class="form-control" >
                    <option {{($category->status ==1) ? 'selected' : '' }} value="1">Active</option>
                    <option {{($category->status ==0) ? 'selected' : '' }} value="0">Block</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <input type='hidden' id="image_id" name="image_id" value="">
                  <label for="image">Image</label>
                  <div id="image" class="dropzone dz-clickable">
                    <div class="dz-message needsclick">
                        <br>Drop files here or click to upload.<br><br>
                    </div>
                </div>
                </div>
              </div>

              @if (!empty($category->image))
              <div >
                <img style="width: 200px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="Category Image">
              </div>
              @else
              <p style="color: red; font-weight: bold;">Category has no image!</p>
              @endif

            </div>
          </div>

        </div>
        <div class="pb-5 pt-3">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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

$("#categoryForm").submit(function(event) {
  event.preventDefault();
  var element = $(this);
  $.ajax({
    url: '{{ route("categories.update" , $category->id) }}',
    type: 'put',
    data: element.serializeArray(),
    dataType: 'json',
    success: function(response) {
      // if (response["status"] == true) {
        window.location.href = "{{ route('categories.index') }}"; // Redirect to the index page
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

