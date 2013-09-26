<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar', array('title' => $title))

        <!-- Start of content -->
        <div class="container theme-showcase">
            <div class="jumbotron">
                <div class="container">
                    <p>This appliance is currently running version <strong>{{{ $turbineversion }}}</strong> of Turbine, it has <Strong>{{{ $rulestotal }}}</strong> <a href="{{ URL::route('rules.index') }}">reverse proxy rules</a> configured, <strong>{{{ $rulesnlbtotal }}}</strong> of which are configured using network load-balancing. This server is running <strong>{{ $os }}</strong>.</p>
                </div>
            </div>
            @include('includes/copyright')
        </div>
        <!-- End content -->

        @include('includes/footer')
    </body>
</html>