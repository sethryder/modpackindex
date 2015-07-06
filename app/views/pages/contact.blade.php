@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 ">

                    <div class="portlet">

                        <h2 class="portlet-title">
                            <u>Contact Us</u>
                        </h2>

                        <div class="portlet-body">

                            <p>You can reach us on our <a href="https://webchat.esper.net/?channels=ModpackIndex"
                                                          target="_blank">IRC Channel</a> or by using the form below.
                            </p>

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
                                    <strong>Sent!</strong> Thank you for your message. We will get back to you as soon
                                    as we can.
                                </div> <!-- /.alert -->
                            @endif

                            {{ Form::open(array('url' => '/contact', 'class' => 'form parsley-form')) }}

                            <div class="form-group">
                                {{ Form::label('name','Your Name') }}:
                                {{ Form::text('name', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('email','Your Email') }}:
                                {{ Form::text('email', null, array('class' => 'form-control'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                {{ Form::label('message','Your Message:') }}:
                                {{ Form::textarea('message', null, array('class' => 'form-control', 'data-required' => 'true'))}}
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group" style="margin-left: auto; margin-right: auto; display: block;">
                                {{ Form::label('captcha','Are you a human?') }}
                                {{ Form::captcha(array('style' => 'margin-left: auto; margin-right: auto;'))}}
                            </div>

                            {{ Form::submit('Send Message', ['class' => 'btn btn-danger']) }}

                            {{ Form::close() }}

                        </div>
                        <!-- /.portlet-body -->

                    </div>
                    <!-- /.portlet -->

                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->


    </div> <!-- .content -->

@stop