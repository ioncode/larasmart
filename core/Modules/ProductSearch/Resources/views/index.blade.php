@extends('productsearch::layouts.master')

@section('content')
    {{--<h1>{{$search}}</h1>--}}
    {{ Form::open(array('url' => 'productsearch/invoke/', 'method' => 'get')) }}
    {{ Form::text('product_name', $search) }}
    {{ Form::close() }}
    @isset($products)
        <div class="grid-container">
            @foreach ($products as $product)
                <div class="grid-item product">

                    {{ Form::open(array('url' => 'productsearch/store', 'method' => 'post')) }}
                    {{ Form::hidden('product_name', $product->product_name) }}
                    {{ Form::hidden('external_id', $product->external_id) }}
                    {{ Form::hidden('categories', $product->categories) }}
                    {{ Form::hidden('image_url', $product->image_url) }}
                    {{ Form::submit('Save') }}
                    {{ Form::close() }}

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script type="text/javascript">

    $(function () {
        $('.product form').on('submit', function (event) {
            event.preventDefault();
            //console.log([this, $(this), event, $('[name="external_id"]', this).val()]);
            form = this;
            $.ajax({
                url: event.target.action,
                type: "POST",
                data: {
                    "_token": $('[name="_token"]', this).val(),
                    external_id: $('[name="external_id"]', this).val(),
                    product_name: $('[name="product_name"]', this).val(),
                    categories: $('[name="categories"]', this).val(),
                    image_url: $('[name="image_url"]', this).val(),
                },
                success: function (response, status, jqXHR) {
                    //console.log([response, this, jqXHR, form]);
                    if (response.success) {
                        $('[type="submit"]', form).val(response.success)
                    }
                },
            });
        });


    });


</script>
