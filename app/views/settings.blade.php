<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar')

        <!-- Start of content -->
        <div class="container theme-showcase">

            <div class="page-header">
                <h1>Settings</h1>
            </div>

            <!-- ALERTS --
            <div class="alert alert-success">...</div>
            <div class="alert alert-info">...</div>
            <div class="alert alert-warning">...</div>
            <div class="alert alert-danger">...</div>
            -->

            <form role="form">
                <div class="form-group">
                    <label for="exampleInputEmail1">Origin address (Host header)</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="eg. www.mydomain.com or *.mydomain.com">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Target server</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="eg. 192.168.0.12 or web1.internal.local">
                    <p class="help-block">You can add multiple target servers (a network load-balanced set-up) after you create this initial rule.</p>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Enabled
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Save changes</button>
            </form>
        </form>
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>