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
            @include('includes/flashmsgs')
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
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>