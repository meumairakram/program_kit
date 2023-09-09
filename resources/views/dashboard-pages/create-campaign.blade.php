@extends('layouts.user_type.auth')

@section('content')

    <form class="container-fluid py-4" action="{{ route('store-campaign') }}" method="POST" role="form text-left">
            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Create a new Campaign</h5>

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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="user-name" class="form-control-label">Title</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control first_sec_required" value="" type="text" placeholder="Title" id="campaign-title" name="title">
                                            @error('name')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control first_sec_required" name="type">
                                            <option value="wordpress">Wordpress</option>
                                            <option value="webflow">Webflow</option>
                                            <option value="bubble">Bubble</option>
                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>    -->

                            <div class="col-md-4">
                                <div class="form-group ps-0 form-switch">
                                    <label class="d-block">Status</label>
                                    <div class="d-flex align-items-center">
                                        <label class="my-0">Paused</label>

                                        <input class="form-check-input mx-auto" type="checkbox" id="flexSwitchCheckDefault" checked>

                                        <label class="my-0">Active</label>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="user-email" class="form-control-label">Description</label>
                                        <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                            <textarea class="form-control first_sec_required" id="about" rows="3" placeholder="Say something about yourself" name="description">{{ auth()->user()->about_me }}</textarea>
                                        </div>
                                    </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="button" id="nextButton" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                        </div>

                        <div class="mt-3  alert alert-primary alert-dismissible fade show d-none firstError" role="alert">
                            <span class="alert-text text-white">You have to fill all the required fields to proceed next.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                </div>
            </div>


            <div class="card mb-4 website">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Choose Website</h6>
                </div>

                <div class="card-body pt-4 p-3">
                     <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Select Website Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" name="website_type" id="websiteType">
                                            <option value=""> </option>
                                            <option value="wordpress">Wordpress</option>
                                            <!-- <option value="wordpress">Webflow</option>
                                            <option value="wordpress">Bubble</option> -->
                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Select Website</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" name="website_id" id="selectWebSite">
                                            <option value="">Select a website</option>

                                            <!-- @foreach($allWebsites as $website)
                                                <option value="{{ $website->id }}">{{$website->website_name}} ({{$website->website_url}})</option>
                                            @endforeach -->

                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Post type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" name="post_type" id="postType">
                                            <option value=""> </option>
                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Template</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" name="wp_template_id" id="template">
                                            <option value="">Template title</option>
                                            <!-- <option value="1">Template title - #18</option>
                                            <option value="2">Template title - #19</option>
                                            <option value="3">Bubble</option> -->
                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="template_name" id="templateName" value="">
                            <textarea class="d-none" name="variables" id="templateTextArea" value=""></textarea>

                        </div>
                        <div class="d-flex justify-content-start">
                                <button type="button" id="webNext" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                        </div>

                        <div class="mt-3  alert alert-primary alert-dismissible fade show d-none secondError" role="alert">
                            <span class="alert-text text-white">You have to fill 'Website' section completely to proceed next.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                </div>
            </div>




            <div class="card mb-4 datasource">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Connect/Choose Data Source</h6>
                </div>

                <div class="card-body pt-4 p-3">

                    <div class="row">


                        <div class="col-md-6">
                            <!-- <div class="card-header pb-0 px-3"> -->
                                <!-- <h6 class="mb-0">Choose existing Data source</h6> -->
                            <!-- </div> -->
                            <!-- <div class="card-body"> -->
                                <div class="form-group">
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control datasource_sec_required" name="data_source_id" id="dataSource">
                                            <option value="">-- Choose a Source --</option>

                                            @foreach($allDatasources as $ds)
                                                <option value="{{ $ds->id }}" name="{{ $ds->name }} ( {{$ds->type}} )">{{ $ds->name }} ( {{$ds->type}} )</option>
                                            @endforeach

                                        </select>

                                        <div class="text-end mt-1">
                                            <span style="font-size: 0.8rem;">61 records</span>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn">Add new datasource</button>
                                        </div>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            <!-- </div> -->
                            
                        </div>
                       
                            <input type="hidden" name="data_source_name" id="dataSourceName" value="">
                            <!-- <input type="hidden" name="data_source_id" id="dataSourceId" value=""> -->
                            <textarea class="d-none" name="data_source_headers" id="sourceTextArea" value=""></textarea>
                            <input type="hidden" id="variableArrayInput" name="variableArray" value="">
                            <input type="hidden" id="datasourceArrayInput" name="sourceArray" value="">

                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="button" id="datasourceNext" class="btn bg-gradient-dark btn-md mt-4 mb-4 d-none">Next</button>
                    </div>

                    <div class="mt-3  alert alert-primary alert-dismissible fade show d-none thirdError" role="alert">
                        <span class="alert-text text-white">You have to fill 'Data Source' section completely to proceed next.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
            </div>



            <div class="card mb-4 d-none newdatasource">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Connect new Data Source</h6>
                </div>

                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.phone" class="form-control-label">Type</label>
                                <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                    <select class="form-control" name="type" id="selectType">
                                        <option value="csv"> </option>
                                        <option value="csv">CSV</option>
                                        <option value="airtable">Airtable</option>
                                        <option value="google_sheet">Google sheet</option>
                                    </select>
                                    
                                    @error('type')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 uploadCSV d-none">
                           <div class="card mb-4">
                                <div class="card-header pb-0 px-3">
                                    <h6 class="mb-0">Upload your database</h6>
                                </div>
                                <div class="card-body pt-4 p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="user.location" class="form-control-label d-block mb-0">Upload file</label>
                                                <span class="text-xs mb-2 d-block ms-1">Upload your csv</span> 
                                                <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                                    <input class="form-control" type="file" placeholder="Location" id="name" name="csv_file" value="">
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 uploadGoogleSheets d-none">
                            <div class="card mb-4">
                                <div class="card-header pb-0 px-3">
                                    <h6 class="mb-0">Google sheets</h6>
                                </div>
                                <div class="card-body pt-4 p-3">
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-start">
                                                    <button type="button" class="btn btn-dark bg-gradient-dark gsheets">Create Google Sheet</button>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="button" id="newDatasourceNext" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                    </div>

                    <div class="mt-3  alert alert-primary alert-dismissible fade show d-none fourthError" role="alert">
                        <span class="alert-text text-white">You have to fill 'Data Source' section completely to proceed next.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
            </div>



            <div class="card mb-4 mapdata">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Map Data Fields</h6>
                </div>

                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-12">
                            <table class="data-map-table table table-hover">
                                <thead>
                                    <tr>
                                        <th>Variable</th>
                                        <th>Data source header</th>
                                        <th>Preview</th>
                                    </tr>

                                </thead>

                                <tbody>
                                
                                
                                
                                </tbody>

                            </table>

                        </div>
                        
                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="button" id="mapdataNext" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                    </div>

                    <div class="mt-3  alert alert-primary alert-dismissible fade show d-none fifthError" role="alert">
                        <span class="alert-text text-white">You have to fill 'Map Data' section completely to proceed next.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
            </div>


            <div class="card SaveAndStart">

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Lets save and start</h6>
                </div>


                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex justify-content-start">
                                <input type="hidden" name="data_maps_json" value="" />  
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4 submit-form-btn">Save &amp; Start Sync</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

    </form>


@endsection
