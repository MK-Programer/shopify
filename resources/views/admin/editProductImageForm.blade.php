@extends('layouts.admin')
@section('title') Edit Products Images  @endsection
@section('header') Edit Image | {{$product->name}}  @endsection
@section('body')

<div class="table-responsive">

    @if ($errors->any())
     <ul>
        <li>
        {!! print_r($errors->all()) !!}
        </li>
     </ul>
    @endif
    <h2> Current Image </h2>
    <img src="{{Storage::disk('local')->url('images/products/'.$product->image)}}" width="100" height="100" alt="" />

    <form action="/admin/updateProductImage/{{$product->id}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="image">Update Image</label>
            <input type="file" class="form-control" name="image" id="image" required>
        </div>
       
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>   

@endsection