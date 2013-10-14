<!DOCTYPE html>
<html lang="en">
    @include('partials/head')

    <body>
        @include('partials/navbar')

        <div class="container theme-showcase">

            <div class="page-header">
                <h1>{{{ $title }}}</h1>
            </div>
            @include('partials/flashmsgs')
            <!-- Start of content -->
            @yield('content')
            <!-- End content -->
            <p>&nbsp;</p>
            @include('partials/copyright')
        </div>


        @include('partials/footer')
    </body>
</html>