@extends('layout')
@section('content')
            @if($record)
            {{ Form::open(array('route' => array('rules.update', $record->id), 'method' => 'PUT', 'role' => 'form')) }}
            <div class="form-group">
                <label for="origin_address">Origin address (Host header)</label>
                <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="eg. www.mydomain.com or *.mydomain.com" value="{{{ $record->hostheader }}}">
            </div>

            <p>&nbsp;</p>

            <h4>Existing target(s)</h4>
            @if($targets)
            <table class="table table-bordered">
                <tr>
                    <th>Target server</th>
                    <th>Max fails</th>
                    <th>Fail timeout</th>
                    <th>Weight</th>
                    <th>Actions</th>
                </tr>
                @foreach($targets->nlb_servers as $target)
                <tr>
                    <td>
                        <input type="text" class="form-control input-xlarge" name="target_{{ md5($target->target) }}" id="target_{{ md5($target->target) }}" value="{{ $target->target }}">
                    </td>
                    <td>
                        <input type="text" class="form-control input-mini" name="maxfails_{{ md5($target->target) }}" id="maxfails_{{ md5($target->target) }}" value="{{ $target->max_fails }}">
                    </td>
                    <td>
                        <input type="text" class="form-control input-mini" name="failtimeout_{{ md5($target->target) }}" id="failtimeout_{{ md5($target->target) }}" value="{{ $target->fail_timeout }}">
                    </td>
                    <td>
                        <input type="text" class="form-control input-mini" name="weight_{{ md5($target->target) }}" id="weight_{{ md5($target->target) }}" value="{{ $target->weight }}">
                    </td>
                    <td>
                        @if(count($targets->nlb_servers) > 1)<a href="{{ URL::action('UtilController@getDeleteTarget', array($record->id, md5($target->target))) }}" type="button" class="btn btn-danger">Delete</a>@else<a href="#" type="button" class="btn btn-danger disabled">Delete</a>@endif
                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <p>There are currently zero targets configured.</p>
            @endif
            {{ Form::submit('Save changes', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}

            <p>&nbsp;</p>
            <h4>Add new target</h4>
            {{ Form::open(array('action' => array('UtilController@postAddTarget', $record->id), 'method' => 'POST', 'role' => 'form')) }}
            <table class="table table-bordered">
                <tr>
                    <td>
                        <input type="text" class="form-control" name="target" id="target" placeholder="eg. backend.example.com or 192.168.0.3:8080">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="maxfails" id="maxfails" placeholder="eg. {{ Setting::getSetting('node_maxfails') }}">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="failtimeout" id="failtimeout" placeholder="eg. {{ Setting::getSetting('node_failtimeout') }}">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="weight" id="weight" placeholder="eg. {{ Setting::getSetting('node_weight') }} ">
                    </td>
                    <td>
                        {{ Form::submit('Add target', array('class' => 'btn btn-primary')) }}
                    </td>
                </tr>
            </table>
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
@stop