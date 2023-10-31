

<p><b>id: </b>{{$category->id}}</p>
<p><b>name: </b>{{$category->name}}</p>
<p><b>description: </b>{{$category->description}}</p>

<hr>
<p>cover</p>
<p>
    @if($category->cover)
        <a href="{{Storage::disk('public')->url($category->cover)}}" target="_BLANK">
            <img src="{{Storage::disk('public')->url($category->cover)}}" alt="" width="100" height="100">
        </a>
    @endif
</p>
<hr>

<p><b>meta_title: </b>{{$category->meta_title}}</p>
<p><b>meta_description: </b>{{$category->meta_description}}</p>

<hr>

<p>meta_image</p>
<p>
    @if($category->cover)
        <a href="{{Storage::disk('public')->url($category->meta_image)}}" target="_BLANK">
            <img src="{{Storage::disk('public')->url($category->meta_image)}}" alt="" width="100" height="100">
        </a>
    @endif
</p>
<hr>

    <h2>
        products
    </h2>
    <ul>
        @foreach ($category->products as $product)
            <li>
                <a href="{{route('bs-product-show',$product->slug)}}">{{$product->name}}</a>
            </li>
        @endforeach
    </ul>



</ul>
