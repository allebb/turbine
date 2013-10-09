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

            <a name="updates"></a>
            <h2>Up-to-date</h2>    
            <p class="text-success">You are currently running the latest version (<strong>X</strong>) of Turbine!</p>
            <p class="text-danger">There is an update (<strong>X</strong>) avaliable for Turbine, you're currently running version (<strong>X</strong>) we recommend that all users upgrade at their earliest convenience.</p>
            <p>&nbsp;</p>
            @include('includes/flashmsgs')
            <a name="settings"></a>
            <h2>Application settings</h2>
            {{ Form::open(array('route' => 'settings.store', 'action' => 'POST', 'role' => 'form')) }}
            @foreach($settings as $setting)
            <div class="form-group">
                <label for="{{ $setting->name }}">{{{ $setting->friendlyname }}}</label>
                <input type="text" class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}" placeholder="eg. www.mydomain.com or *.mydomain.com"@if($setting->svalue) value="{{{ $setting->svalue }}}"@endif>
                       <p class="help-block">{{{ $setting->description }}}</p>
            </div>
            @endforeach
            <!-- End of options -->
            {{ Form::submit('Save changes', array('class' => 'btn btn-default')) }}
            {{ Form::close() }}
        </form>
        <p>&nbsp;</p>

        <a name="password"></a>
        <h2>Admin password</h2>
        <p>You are recommended from time to time to reset your 'admin' password, this password enables you to login to Turbine as well as access the API remotely.</p>
        {{ Form::open(array('url' => 'password/reset', 'action' => 'POST', 'role' => 'form')) }}
        <div class="form-group">
            <label for="new_password">New password</label>
            <input type="password" class="form-control" name="new_password" id="new_password">
            <p class="help-block">Enter your new password that you'd like to use and confirm it below if you wish you make changes.</p>
        </div>
        <div class="form-group">
            <label for="new_password_conf">Confirm new password</label>
            <input type="password" class="form-control" name="new_password_conf" id="new_password_conf">
        </div>
        {{ Form::submit('Update password', array('class' => 'btn btn-default')) }}
        {{ Form::close() }}
        <p>&nbsp;</p>
        @include('includes/copyright')
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>