@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <h1 class="my-4">Products</h1>
    <form id="create-product-form" class="mb-4">
        <div class="form-group">
            <input type="text" id="product-name" class="form-control" placeholder="Product Name" required>
        </div>
        <div class="form-group">
            <input type="number" id="product-price" class="form-control" placeholder="Product Price" required>
        </div>
        <div class="form-group">
            <textarea id="product-description" class="form-control" placeholder="Product Description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
    <div id="products" class="mb-4"></div>
@endsection
