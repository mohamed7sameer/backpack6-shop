{{-- <ul class="category-menu">
    @foreach($categories as $category)
        <li>
            <a href="/backpack-shop/categories/{{ $category->slug }}/">{{ $category->name }}</a>
            @if(mohamed7sameer\BackpackShop\Models\ProductCategory::where(parent_id,$category))

        </li>
    @endforeach
<ul> --}}


{!! $menu !!}
