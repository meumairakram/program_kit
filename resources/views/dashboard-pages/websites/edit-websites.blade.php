@extends('layouts.user_type.auth')

@section('content')

    <form class="container-fluid py-4" action="{{ route('update-website') }}" method="POST" role="form text-left">
            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Update website</h5>

                        <div class="d-flex align-items-center justify-content-center">
                            <span class="me-2 text-xs font-weight-bold">20%</span>
                            <div>
                            <div class="progress" style="width:140px;height:3px;margin:0;">
                                <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-3">
                   
                        @csrf
                        @if($errors->any())
                            <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                                <span class="alert-text text-white">
                                {{$errors->first()}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                                <span class="alert-text text-white">
                                {{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user-name" class="form-control-label">Title</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" value="{{$websites->website_name}}" type="text" placeholder="Title" id="campaign-title" name="title">
                                            @error('name')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control" name="type" value="{{$websites->type}}">
                                            <option value="wordpress">Wordpress</option>
                                            <option value="webflow" disabled>Webflow</option>
                                            <option value="bubble" disabled>Bubble</option>
                                        </select>
                                        
                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                        
                                    </div>
                                </div>
                            </div>                            
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user.phone" class="form-control-label">Website URL</label>
                                        <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                            <input class="form-control website_url" value="{{$websites->website_url}}" type="text" placeholder="eg: https://www.example.com" id="website_url" name="website_url">
                                                
                                                @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                                
                                        </div>
                                        <span class="text-xs">Make sure to add the protocol as well eg http OR https</span>

                                    </div>
                            </div>


                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user.phone" class="form-control-label">AJAX URL</label>
                                        <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                            <input class="form-control ajax_url" value="{{$websites->request_url}}" type="text" placeholder="eg: https://www.example.com/wp-admin/admin-ajax.php" id="ajax_url" name="ajax_url">
                                            
                                            @error('type')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                            
                                        </div>
                                        <span class="text-xs">Paste your Wordpress Ajax URL.</span>
                                    </div>
                            </div>

                        </div>

                    <input type="hidden" name="id" value="{{$websites->id}}">
                        
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Authenticate your website</h6>
                </div>

                <div class="card-body pt-4 p-3">
                    
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.location" class="form-control-label d-block mb-0">Authentication Key</label>
                                <span class="text-xs mb-2 d-block ms-1">Can be found in your Wordpress dashboard under the plugin</span> 
                                <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                    <input class="form-control authentication_key" type="text"  placeholder="Location" id="name" name="authentication_key" value="{{$websites->authentication_key}}">
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.location" class="form-control-label d-block mb-0">Verification Status</label>
                                <div class="mt-2 ms-1">
                                    <span class="text-bold verification_status" >Pending</span>
                                    <input type="hidden" name="verified" value="0" class="website-verified" />
                                </div>
                            </div>
                        </div>



                        
                        <div class="d-flex justify-content-start d-none save-button-wrap">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Update Website</button>
                        </div>
                    </div>

                   

                </div>

               

            </div>



           
    </form>


        

        
       
@endsection


@section("javascript")

    <script>
        jQuery(function($) {

            $('.website_url').change(autoFillAjaxUrl);

            function autoFillAjaxUrl(e) {

                var website_url = $(e.target).val();
                    
                var websiteUrl = new URL(website_url);


                $('.ajax_url').val(websiteUrl.origin + "/wp-admin/admin-ajax.php")
            
            }



            var fieldsToWatch = [".website_url",".ajax_url", ".authentication_key"];

            function handleWebsiteAuthentication(e) {

                var website_url = $('.website_url').val();
                var ajax_url = $('.ajax_url').val();

                var auth_key = $('.authentication_key').val();

                var urlToAuthenticate = ajax_url + "?action=pseo_validate_auth_key" + "&auth_key="  + auth_key;

                if(website_url == "" || ajax_url == "" || auth_key == "") {
                    
                    return false;
                
                }


            
                $.ajax({

                    method:"GET",
                    url: urlToAuthenticate,
                    success: function(response) {
                        
                        if(response.success) {
                            $('.verification_status').html("VERIFIED");
                            $('.save-button-wrap').removeClass('d-none');
                            $('.website-verified').val('1');


                        } else {
                            $('.verification_status').html("FAILED");
                        }
                        // console.log(response);
                    
                    },
                    error: function(error) {

                        $('.verification_status').html("FAILED");

                        console.log(error);
                    
                    }
                });
                    
                    
            }


            fieldsToWatch.forEach(function(item) {

                $(item).change(handleWebsiteAuthentication);
            
            });
        
        })

    </script>


@endsection