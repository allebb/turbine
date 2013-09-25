<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar', array('title' => $title))

        <!-- Start of content -->
        <div class="container theme-showcase">
            <div class="jumbotron">
                <div class="container">
                    <p>This appliance is currently running version X of Turbine, it has <Strong>7</strong> <a href="#">reverse proxy rules</a> configured, 3 of which are configured using network load-balancing. This server has been online for X days and is running (OS NAME HERE).</p>
                </div>
            </div>
            @include('includes/copyright')
        </div>
        <!-- End content -->

        @include('includes/footer')
    </body>
</html>