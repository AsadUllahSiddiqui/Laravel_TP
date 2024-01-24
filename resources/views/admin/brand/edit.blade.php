
@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid my-2">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit brand</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="container-fluid">
      <form action="{{ route('brands.store') }}" method="post" id="brandForm" name="brandForm">
        @csrf
        <div class="card">
          <div class="card-body">
            <div class="col">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="name">Name</label>
                  <input type="text" name="name" id="name" class="form-control" value="{{$brand->name}}" placeholder="Name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="slug">Slug</label>
                  <input type="text" readonly name="slug" id="slug" value="{{$brand->slug}}" class="form-control" placeholder="Slug">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status">Status</label>
                  <select name="status" id="status" class="form-control" >
                    <option {{($brand->status ==1) ? 'selected' : '' }} value="1">Active</option>
                    <option {{($brand->status ==0) ? 'selected' : '' }} value="0">Block</option>
                  </select>
                </div>
              </div>

            </div>
          </div>

        </div>
        <div class="pb-5 pt-3">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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

$("#brandForm").submit(function(event) {
  event.preventDefault();
  var element = $(this);
  $.ajax({
    url: '{{ route("brands.update" , $brand->id) }}',
    type: 'put',
    data: element.serializeArray(),
    dataType: 'json',
    success: function(response) {
      // if (response["status"] == true) {
        window.location.href = "{{ route('brands.index') }}"; // Redirect to the index page
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

</script>
@endsection

