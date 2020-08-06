@extends('productsearch::layouts.master')

@section('content')
    {{--<h1>{{$search}}</h1>--}}
    {{ Form::open(array('url' => 'productsearch/invoke/', 'method' => 'get')) }}
    {{ Form::text('product_name', $search) }}
    {{ Form::close() }}
    @foreach ($products as $product)
        <p>Name: {{ $product->product_name }}</p>
        <p>Image: {{ $product['image_url'] }}</p>
        @php
            //print_r($product);
        @endphp
        <br>
    @endforeach
    <p>
        This view is loaded from module: {!! config('productsearch.name') !!}
    </p>
@endsection
