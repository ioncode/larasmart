@extends('productsearch::layouts.master')

@section('content')
    {{--<h1>{{$search}}</h1>--}}
    {{ Form::open(array('url' => 'productsearch/invoke/', 'method' => 'get')) }}
    {{ Form::text('product_name', $search) }}
    {{ Form::close() }}
    @isset($products)
        <div class="grid-container">
            @foreach ($products as $product)
                <div class="grid-item">
                    @if($product['image_url'])
                        {{ Html::image($product['image_url'], $product->product_name, array('class' => 'css-class', 'height'=>'120px')) }}
                    @endif
                    <p>{{ $product->product_name }}</p>
                    {{--<p>Image: {{ $product['image_url'] }}</p>--}}
                    @php
                        //print_r($product);
                    @endphp
                </div>
            @endforeach
        </div>
    @endisset

    @empty($products)
        <p>There is no items</p>
    @endempty

    {{--<p>
        This view is loaded from module: {!! config('productsearch.name') !!}
    </p>--}}
@endsection
