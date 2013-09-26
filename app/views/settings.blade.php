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

            <form role="form">
                @foreach($settings as $setting)
                <div class="form-group">
                    <label for="{{ $setting->name }}">{{{ $setting->friendlyname }}}</label>
                    <input type="text" class="form-control" id="{{ $setting->name }}" placeholder="eg. www.mydomain.com or *.mydomain.com"@if($setting->svalue) value="{{{ $setting->svalue }}}"@endif>
                    <p class="help-block">{{{ $setting->description }}}</p>
                </div>
                @endforeach
                <!-- End of options -->
                <button type="submit" class="btn btn-default">Save changes</button>
            </form>
        </form>
    </div>
    <!-- End content -->

    @include('includes/footer')
</body>
</html>