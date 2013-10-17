@if($versioninfo->result)
    @if(Setting::getSetting('version') >= $versioninfo->result->version)
    <p class="text-success">You are currently running the latest version (<strong>{{ Setting::getSetting('version') }}</strong>) of Turbine!</p>
    @else
    <p class="text-danger">There is an update (<strong>{{ $versioninfo->result->version }}</strong>) avaliable for Turbine, you're currently running version (<strong>{{ Setting::getSetting('version') }}</strong>) we recommend that all users <a href="{{ $versioninfo->result->upgrades }}" target="_blank">upgrade</a> at their earliest convenience.</p>
    @endif
@else
<p class="text-warning">There was an error connecting to the version checker web service, please check the version manually, you're currently running version (<strong>{{ Setting::getSetting('version') }}</strong>).</p>
@endif
<small>Last checked for updates at {{ $versioninfo->last_check }}.</small>