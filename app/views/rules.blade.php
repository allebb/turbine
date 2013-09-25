<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar')

        <!-- Start of content -->
        <div class="container theme-showcase">

            <div class="page-header">
                <h1>Sites &amp; Applications</h1>
            </div>

            <!-- ALERTS --
            <div class="alert alert-success">...</div>
<div class="alert alert-info">...</div>
<div class="alert alert-warning">...</div>
<div class="alert alert-danger">...</div>
            -->

            <h2>Create new rule</h2>
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
                <button type="submit" class="btn btn-default">Create rule</button>
            </form>
        </form>
        <p>&nbsp;</p>
        <h2>Existing rules</h2>
        <table class="table table-hover">
            <tr><th>Host header</th><th>Enabled</th><th>Target(s)</th><th>Load-balanced</th><th></th></tr>
            <tr><td>*.bobbyallen.me</td><td></td><td>172.25.87.2 [80]<br>172.25.87.5 [80]</td><td><span class="glyphicon glyphicon-ok"></span></td><td><a href="#edit" class="btn btn-xs btn-default">Edit</a> <a href="#delete" class="btn btn-xs btn-danger">Delete</a</td></tr>
            <tr><td>www.bassrocket.com</td><td><span class="glyphicon glyphicon-ok"></span></td><td>172.25.87.3 [8081]</td><td></td><td><a href="#edit" class="btn btn-xs btn-default">Edit</a> <a href="#delete" class="btn btn-xs btn-danger">Delete</a</td></tr>
            <tr><td>api.bassrocket.com</td><td><span class="glyphicon glyphicon-ok"></span></td><td>172.25.87.9 [8081]</td><td></td><td><a href="#edit" class="btn btn-xs btn-default">Edit</a> <a href="#delete" class="btn btn-xs btn-danger">Delete</a></td></tr>
        </table>
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>