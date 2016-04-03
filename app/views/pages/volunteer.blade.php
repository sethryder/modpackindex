@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>Volunteer</u>
                </h3>

                <div class="portlet-body">
                    <p><b>Modpack Index</b> is looking for some awesome people to join us. If you are interested view
                        the available positions and requirements below.</p>

                    <p>&nbsp;</p>

                    <h4>Content Team</h4>

                    <p>Currently we are looking to start up our <b>Content Team</b>. This includes adding new Modpacks (and Mods),
                        updating Modpacks and Mods when needed (usually when new versions are released), and correcting
                        mistakes reported by our users. Also you can help provide input for new features and help test
                        features and changes before release. </p>

                    <p><b>Requirements:</b></p>

                    <ul>
                        <li>16 years or older.</li>
                        <li>Can dedicate a few hours a week.</li>
                        <li>Actively following the Minecraft modding scene.</li>
                        <li>Able to stay in contact via our IRC Channel or other methods.</li>
                        <li>Familiar with different clients (FTB Client, Curse Client, ATLauncher, etc).</li>
                    </ul>
                </div>

                <p>If you are interested fill out the form below and we will reach out to you as soon as possible.
                    If you have any questions you can use our contact form or even better message me (BigSAR) in our IRC Channel
                    <a href="https://webchat.esper.net/?channels=ModpackIndex" target="_blank">#ModpackIndex</a> on
                    <a href="https://www.esper.net/" target="_blank">EsperNet</a>.
                </p>

                <p>&nbsp;</p>

                <h3 class="portlet-title">
                    <u>Apply</u>
                </h3>

                <div class="portlet-body">
                    @if ( $errors->count() > 0 )
                        <div class="alert alert-danger">
                            <p>The following errors have occurred:</p>

                            <ul>
                                @foreach( $errors->all() as $message )
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div> <!-- /.alert -->
                    @endif

                    @if (isset($success))
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                            <strong>Sent!</strong> Thank you for your application. We will review it shortly and
                            reach out to you soon!
                        </div> <!-- /.alert -->
                    @endif

                    {{ Form::open(array('url' => action('StaticPagesController@postVolunteer'), 'class' => 'form parsley-form')) }}

                    <div class="form-group">
                        {{ Form::label('nickname','Nickname') }}:
                        {{ Form::text('nickname', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('email','Your Email') }}:
                        {{ Form::text('email', null, array('class' => 'form-control'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('age','Age') }}:
                        {{ Form::text('age', null, array('class' => 'form-control'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('position','Position') }}:
                        {{ Form::select('position', ['content' => 'Content Team', 'other' => 'Other',], null, array('class' => 'form-control'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        {{ Form::label('why','Why do you want to help out?') }}:
                        {{ Form::textarea('why', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group" style="margin-left: auto; margin-right: auto; display: block;">
                        {{ Form::label('captcha','Are you a human?') }}
                        {{ Form::captcha(array('style' => 'margin-left: auto; margin-right: auto;'))}}
                    </div>

                    {{ Form::submit('Send Message', ['class' => 'btn btn-danger']) }}

                    {{ Form::close() }}

                </div>
                <!-- /.portlet -->

            </div>
        <!-- /.container -->

        </div>
    </div><!-- .content -->
@stop