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
            <form role="form" action="{{ URL::route('rules.store') }}" method="POST">
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
        @if($rules->count()>0)
        <table class="table table-hover">
            <tr><th>Host header</th><th>Enabled</th><th>Target(s)</th><th>Load-balanced</th><th></th></tr>
            @foreach($rules as $rule)
            <tr><td>{{{ $rule->hostheader }}}</td><td>@if($rule->enabled)<span class="glyphicon glyphicon-ok"></span>@endif</td><td>To be loaded from file!</td><td>@if($rule->nlb)<span class="glyphicon glyphicon-ok"></span>@endif</td><td><a href="{{{ URL::route('rules.edit', $rule->id) }}}" class="btn btn-xs btn-default">Edit</a></td></tr>
            @endforeach
        </table>
        @else
        <p>&nbsp;</p>
        <p>There are currently no rules configured on this appliance!</p>
        <p>&nbsp;</p>
        @endif
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>