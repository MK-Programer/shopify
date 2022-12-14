@extends('layouts.admin')
@section('title') Edit Products  @endsection
@section('header') Edit Product | {{$product->name}}  @endsection
@section('body')

<div class="table-responsive">
    <form action="/admin/updateProductForm/{{$product->id}}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" value="{{$product->name}}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="{{$product->description}}" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" class="form-control" name="type" id="type" placeholder="Type" value="{{$product->type}}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="{{$product->price}}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>   

@endsection