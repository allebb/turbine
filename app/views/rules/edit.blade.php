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
            {{ Form::open(array('route' => array('rules.update', $record->id), 'action' => 'PUT', 'role' => 'form')) }}
            <div class="form-group">
                <label for="origin_address">Origin address (Host header)</label>
                <input type="text" class="form-control" name="origin_address" id="origin_address" placeholder="eg. www.mydomain.com or *.mydomain.com" value="{{{ $record->hostheader }}}">
                <div class="checkbox">
                    <label>
                        <input name="enabled" id="enabled" type="checkbox" @if($record->enabled)checked="checked"@endif> Enabled
                    </label>
                </div>
            </div>

            <p>&nbsp;</p>
            <div class="form-group">
                <h4>Existing target(s)</h4>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="target_">Target server</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="maxfails_">Max fails</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="failtimeout_">Fail timeout</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="weight_">Weight</label>
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="target_" id="target_">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="maxfails_" id="maxfails_">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="failtimeout_" id="failtimeout_">
                        </div>
                        <div class="col-lg-1">
                            <input type="text" class="form-control" name="weight_" id="weight_">
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-danger">Delete target</button>
                        </div>
                    </div>
                </div>

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
                            <button type="button" class="btn btn-default">Add target</button>
                        </div>
                    </div>

                </div>
                {{ Form::submit('Save changes', array('class' => 'btn btn-primary')) }}
                {{ Form::close() }}
                </form>
                <p>&nbsp;</p>

                <h2>Delete rule</h2>
                <p>If you wish to delete this rule and stop routing to the backend server(s) press the delete button and confirm you wish to destroy this rule.</p>
                {{ Form::open(array('route' => array('rules.destroy', $record->id), 'action' => 'DELETE', 'role' => 'form')) }}
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