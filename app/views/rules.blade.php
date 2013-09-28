<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar')

        <!-- Start of content -->
        <div class="container theme-showcase">

            <div class="page-header">
                <h1>Rules</h1>
            </div>
            @include('includes/flashmsgs')

            <h2>Existing rules</h2>
            @if($total_rules > 0)
            <table class="table table-hover">
                <tr><th>Host header</th><th>Enabled</th><th>Target(s)</th><th>Load-balanced</th><th></th></tr>
                @foreach($rules as $rule)
                <tr><td>{{{ $rule->hostheader }}}</td><td>@if($rule->enabled)<span class="glyphicon glyphicon-ok"></span>@endif</td><td>{{ $rule->targets }}</td><td>@if($rule->nlb)<span class="glyphicon glyphicon-ok"></span>@endif</td><td><a href="{{{ URL::route('rules.edit', $rule->id) }}}" class="btn btn-xs btn-info">Edit</a></td></tr>
                @endforeach
            </table>
            @else
            <p>&nbsp;</p>
            <p>There are currently no rules configured on this appliance!</p>
            <p>&nbsp;</p>
            @endif
            <h2>Create new rule</h2>
            {{ Form::open(array('route' => 'rules.store', 'action' => 'POST', 'role' => 'form')) }}
            <div class="form-group">
                <label for="origin_address">Origin address (Host header)</label>
                <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="eg. www.mydomain.com or *.mydomain.com">
            </div>
            <div class="form-group">
                <label for="target_address">Target server</label>
                <input type="text" class="form-control" name="target_address" id="target_address" placeholder="eg. 192.168.0.12 or web1.internal.local:8080">
                <p class="help-block">You can add multiple target servers (a network load-balanced set-up) after you create this initial rule using the 'edit' button below.</p>
            </div>
            <div class="checkbox">
                <label>
                    <input name="enabled" id="enabled" type="checkbox" checked="checked"> Enabled
                </label>
            </div>
            {{ Form::submit('Create rule', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </form>
        <p>&nbsp;</p>
        @include('includes/copyright')
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>