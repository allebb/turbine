<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::route('.index') }}">Turbine</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @if(Request::segment(1) == 'rules')
                <li class="active"><a href="{{ URL::route('rules.index') }}">Sites &amp; Servers</a></li>
                @else
                <li><a href="{{ URL::route('rules.index') }}">Sites &amp; Servers</a></li>
                @endif
                @if(Request::segment(1) == 'settings')
                <li class="active"><a href="{{ URL::route('settings.index') }}">Settings</a></li>
                @else
                <li><a href="{{ URL::route('settings.index') }}">Settings</a></li>
                @endif
                <li><a href="{{ URL::action('UtilController@getLogout') }}">Logout</a></li>
            </ul>
        </div>
    </div>
</div>