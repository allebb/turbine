{{-- This include handles the display of notice, infomation and error notices --}}
@if (Session::has('flash_error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Error!</strong> {{ Session::get('flash_error') }}
</div>
<br />
@endif

@if (Session::has('flash_notice'))
<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> {{ Session::get('flash_notice') }}
</div>
<br />
@endif

@if (Session::has('flash_info'))
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Info!</strong> {{ Session::get('flash_info') }}
</div>
@endif

@if (Session::has('flash_success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Success!</strong> {{ Session::get('flash_success') }}
</div>
@endif