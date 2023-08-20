@extends('layouts.app')




@section('auth')

    

    <main class="main-content">
        <div class="container-fluid py-4">

                <div class="row message-box">

                    @if($status === "success")



        
                        <div class="col-sm-12">
                            <h3>Connected Successfully!</h3>
                            <span>This window will automatically be closed or you can close and refresh the other page to get started.</span>

                            <button class="btn btn-dark btn-gradient-dark d-block mt-4 " onclick="window.close()">Close Window</button>
                        </div>

                    @elseif($status === "error") 

                        <div class="col-sm-12">
                            <h3>Ah, Snap!</h3>
                            <span>There seems to be some error connecting your google account with the app. Please click the button below and try again.</span>
                            <span>If it still doesn't work, please contact the admin</span>

                            <a class="btn btn-dark btn-gradient-dark d-inline-block mt-4" href="/sheets/init">Connect Google Account</a>
                        </div>


                    @elseif($status == "pre_connected")
                        
                        <div class="col-sm-12">
                            <h3>Your Google account is already connected!</h3>
                            <span>Your google account is already connected to the app.</span>
                            <!-- <span>If it still doesn't work, please contact the admin</span> -->

                            <button class="btn btn-dark btn-gradient-dark d-block mt-4 " onclick="window.close()">Close Window</button>
                        </div>

                    @elseif($status === "custom_error")


                        <div class="col-sm-12">
                            <h3>There seems to be some error!</h3>
                            <span>{{ $error_text }}</span>
                            <!-- <span>If it still doesn't work, please contact the admin</span> -->

                            <button class="btn btn-dark btn-gradient-dark d-block mt-4 " onclick="window.close()">Close Window</button>
                        </div>


                    @endif

                </div>


        </div>
    </main>



@endsection
