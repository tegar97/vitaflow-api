<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
</head>

<body>
    <div class="screen-cover d-none d-xl-none"></div>
    <div class="row">
        @include('includes.sidebar')

        <div class="col-12 col-xl-9">

            @include('includes.header')
            <main class=" " style="padding: 0 40px 10px 0px;">

                @yield('content')
            </,>
        </div>
    </div>


    @include('includes.script')
</body>

</html>
