@extends('layouts.admin')
@section('title') Add Product  @endsection
@section('header') Add Product  @endsection
@section('body')

<div class="table-responsive">

    @if ($errors->any())
    <ul>
       <li>
       {!! print_r($errors->all()) !!}
       </li>
    </ul>
   @endif

    <form action="/admin/addProductForm" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="image">Update Image</label>
            <input type="file" class="form-control" name="image" id="image" required>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Product Name"required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" name="description" id="description" placeholder="Description" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" class="form-control" name="type" id="type" placeholder="Type" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" class="form-control" name="price" id="price" placeholder="Price" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>   

@endsection