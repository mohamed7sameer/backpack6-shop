<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script>
        @include('mohamed7sameer.backpack-shop::backpack_shop.files.js.tailwindcss')
    </script>
    @stack('styles')

    <title>@yield('title','BackShop')</title>
</head>
<body>


    @yield('content')

    <script>
        document.querySelectorAll('img').forEach(element => {
            element.addEventListener('error',function(){
                this.src='https://6valley.6amtech.com/public/assets/front-end/img/image-place-holder.png'
            })
        });
    </script>
    @stack('scripts')

</body>
</html>
