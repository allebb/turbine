<!DOCTYPE html>
<html lang="en">
    @include('includes/head')
    <body>
        @include('includes/navbar')

        <!-- Start of content -->
        <div class="container theme-showcase">
            <div class="page-header">
                <h1>Edit rule</h1>
            </div>
            @include('includes/flashmsgs')
            @if($record)
            {{ Form::open(array('route' => array('rules.update', $record->id), 'method' => 'PUT', 'role' => 'form')) }}
            <div class="form-group">
                <label for="origin_address">Origin address (Host header)</label>
                <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="eg. www.mydomain.com or *.mydomain.com" value="{{{ $record->hostheader }}}">
            </div>

            <p>&nbsp;</p>
            <div class="form-group">
                <h4>Existing target(s)</h4>
                <div class="row">
                    <div class="col-lg-3">
                        <label>Target server</label>
                    </div>
                    <div class="col-lg-1">
                        <label>Max fails</label>
                    </div>
                    <div class="col-lg-1">
                        <label>Fail timeout</label>
                    </div>
                    <div class="col-lg-1">
                        <label>Weight</label>
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
                @if($targets)
                @foreach($targets->nlb_servers as $target)
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="target_{{ md5($target->target) }}" id="target_{{ md5($target->target) }}" value="{{ $target->target }}">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="maxfails_{{ md5($target->target) }}" id="maxfails_{{ md5($target->target) }}" value="{{ $target->max_fails }}">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="failtimeout_{{ md5($target->target) }}" id="failtimeout_{{ md5($target->target) }}" value="{{ $target->fail_timeout }}">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="weight_{{ md5($target->target) }}" id="weight_{{ md5($target->target) }}" value="{{ $target->weight }}">
                        </div>
                        <div class="col-lg-3">
                            @if(count($targets->nlb_servers) > 1)<a href="{{ URL::action('UtilController@getDeleteTarget', array($record->id, md5($target->target))) }}" type="button" class="btn btn-danger">Delete target</a>@endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <p>There are currently zero targets configured.</p>
                @endif
                {{ Form::submit('Save changes', array('class' => 'btn btn-primary')) }}
                {{ Form::close() }}

                <p>&nbsp;</p>
                {{ Form::open(array('action' => array('UtilController@postAddTarget', $record->id), 'method' => 'POST', 'role' => 'form')) }}
                <div class="form-group">
                    <h4>Add new target</h4>
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="target" id="target" placeholder="eg. backend.example.com or 192.168.0.3:8080">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="maxfails" id="maxfails" placeholder="eg. {{ Setting::getSetting('maxfails') }}">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="failtimeout" id="failtimeout" placeholder="eg. {{ Setting::getSetting('failtimeout') }}">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="weight" id="weight" placeholder="eg. 1">
                        </div>
                        <div class="col-lg-3">
                            {{ Form::submit('Add target', array('class' => 'btn btn-default')) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
                <p>&nbsp;</p>
                <a name="delete"></a>
                <h2>Delete rule</h2>
                <p>If you wish to delete this rule and stop routing to the backend server(s) press the delete button and confirm you wish to destroy this rule.</p>
                {{ Form::open(array('route' => array('rules.destroy', $record->id), 'method' => 'DELETE', 'role' => 'form')) }}
                {{ Form::submit('Delete rule', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}
                <p>&nbsp;</p>
                @else
                <p>&nbsp;</p>
                <p>Sorry no rule with that UID exists, please return to the <a href="{{ URL::route('rules.index') }}">rules page</a>!</p>
                <p>&nbsp;</p>
                @endif

                @include('includes/copyright')
            </div>
            <!-- End content -->

            @include('includes/footer')
    </body>
</html>