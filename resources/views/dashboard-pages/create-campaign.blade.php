@extends('layouts.user_type.auth')

@section('content')

    <form x-data='$store.create_campaign_store' @submit="submitCreateCampaign" class="container-fluid py-4" action="{{ route('store-campaign') }}" method="POST" role="form text-left">
            <div class="card mb-4" x-show="currentStep > 0">
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
                            <button type="button" @click="action_handle_campaign_info_step" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                        </div>

                        <div class="mt-3  alert alert-primary alert-dismissible fade show d-none firstError" role="alert">
                            <span class="alert-text text-white">You have to fill all the required fields to proceed next.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                </div>
            </div>


            <div class="card mb-4 website" x-show="currentStep > 1">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Choose Website</h6>
                </div>

                <div class="card-body pt-4 p-3">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Select Website Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" @change="handleWebsiteTypeChange" name="website_type" id="websiteType">
                                            <option value=""></option>
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
                                        <select class="form-control web_sec_required" name="website_id" @change="handleWebsiteIdChange" id="selectWebSite">
                                            <option value="">-- Select a website --</option>
                                            <template x-for="website_option in avl_websites">
                                                <option x-bind:value="website_option.id" x-text="website_option.name + ' ( ' + website_option.url + ' )'">Select a website</option>

                                            </template>

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
                                        <select class="form-control web_sec_required" name="post_type" @change="handleWebsitePostTypeChange" id="postType">
                                            <option value="">-- Select post type --</option>
                                            <template x-for="post_type_option in avl_post_types">
                                                <option x-bind:value="post_type_option" x-text="post_type_option"></option>
                                            </template>
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
                                        <select class="form-control web_sec_required" @change="handleTemplateFieldChange" name="wp_template_id" id="template">
                                            <option value="">-- Select Template --</option>

                                            <template x-for="template_option in avl_templates"> 
                                                <option x-bind:value="template_option.id" x-text="`${template_option.title} - #${template_option.id}`"></option>

                                            </template>
                                            <!--<option value="2">Template title - #19</option>
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
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Page Status</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" @change="handleTemplateFieldChange" name="pg_status" id="pg_status">
                                            <option value="">-- Select Page Status --</option>
                                            <option value="draft"> Draft </option>
                                            <option value="publish"> Publish </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-start">
                                <button type="button" @click="action_handle_website_submit_step" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                        </div>

                        <div class="col-12"  x-show="wb_loading == true" >

                            <div class="col-4 offset-8 d-flex align-items-center justify-content-end">

                                <div class="spinner-border text-dark mr-2" style="width: 1.5rem;height: 1.5rem; margin-right : 8px;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>

                                <span>Processing...</span>

                            </div>

                        </div>

                        <div class="mt-3  alert alert-primary alert-dismissible fade show d-none secondError" role="alert">
                            <span class="alert-text text-white">You have to fill 'Website' section completely to proceed next.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                </div>
            </div>




            <div class="card mb-4 datasource" x-show="currentStep > 2">
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
                                <template x-if="data_source_id == null">
                                    <div class="form-group">
                                        <div class="@error('user.phone') border border-danger rounded-3 @enderror">

                                            
                                            <template x-if="ds_source_type == 'existing'">
                                                <div class="existing-datasource">
                                                    <select @change="set_ds_id" class="form-control datasource_sec_required" name="data_source_id" id="dataSource">
                                                        <option value="">-- Choose a Source --</option>

                                                        @foreach($allDatasources as $ds)
                                                            @if($ds->type == 'csv')
                                                                <option value="{{ $ds->id }}" name="{{ $ds->name }} ( {{$ds->type}} )">{{ $ds->name }} ( {{$ds->type}} )</option>
                                                            @endif
                                                        @endforeach

                                                    </select>

                                                    <div class="text-end mt-1">
                                                        <span style="font-size: 0.8rem;">61 records</span>
                                                    </div>

                                                    <div class="mt-2">
                                                        <button class="btn ds-new" @click='(e) => { action_switch_ds_type(e, "new") }'>Add new datasource</button>
                                                    </div>

                                                </div>

                                            </template>




                                            <template x-if="ds_source_type == 'new'">
                                                
                                                <div class="new-datasource">
                                                
                                                    <div class="col-12" id="">
                                                        <span class="h6">New Data source type:</span>

                                                        <div class="row mt-3" x-show="new_ds_type == null">
                                                            <div class="col-6 datasource-type-select g-sheet" @click="set_new_ds_type('google_sheet')" style="cursor:pointer;">
                                                                <div class="ds-type-inner p-4">
                                                                    <div class="icon-holder">
                                                                        <i class="fas fa-file-excel"></i>
                                                                    </div>
                                                                    <span class="d-block text-bold">Google Sheet</span>

                                                                    <div class="d-block action-btn mt-1">
                                                                        <span x-text="google_acc_connected ? 'Launch' : 'Connect'"></span><i class="fas fa-arrow-right"></i>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-6 datasource-type-select csv" @click="set_new_ds_type('csv')" style="cursor:pointer;">
                                                                <div class="ds-type-inner p-4">
                                                                    <div class="icon-holder">
                                                                        <i class="fas fa-file-csv"></i>
                                                                    </div>
                                                                    <span  class="d-block text-bold">CSV File</span>

                                                                    <div class="d-block action-btn mt-1">
                                                                        <span>Upload</span><i class="fas fa-arrow-right"></i>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    
                                                    <template x-if="new_ds_type == 'google_sheet'">

                                                        <div class="col-sm-12 mt-3">
                                                            
                                                            <template x-if="google_acc_connected">
                                                                <div>
                                                                    <div class="form-group d-grid" style="grid-template-columns:1fr 1fr 1fr;">
                                                                        <label class="form-check-label" x-bind:class="gsheet_type == 'existing_sheet' ? 'text-bold' : ''" for="flexSwitchCheckChecked">Use Existing Sheet</label>
                                                                            <div class="form-check form-switch">
                                                                                
                                                                                <input class="form-check-input mx-auto" @change="set_google_sheet_type" style="border-color: rgba(58, 65, 111, 0.95);background-color: rgba(58, 65, 111, 0.95);" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                                                                
                                                                            </div>
                                                                        <label class="form-check-label" for="flexSwitchCheckChecked" x-bind:class="gsheet_type == 'new_sheet' ? 'text-bold' : ''">Create a new one</label>
                                                                    </div>


                                                                    <template x-if="gsheet_type == 'existing_sheet'">

                                                                        <div class="from-group">

                                                                            <label class="d-block">Lets choose a Google Sheet</label>

                                                                            <select class="form-control" name="select_existing_sheet">

                                                                                <option value="">-- Choose Existing sheet --</option>

                                                                                @foreach($allDatasources as $ds)
                                                                                    @if($ds->type == 'google_sheet')
                                                                                        <option value="{{ $ds->id }}" name="{{ $ds->name }} ( {{$ds->type}} )">{{ $ds->name }} ( {{$ds->type}} )</option>
                                                                                    @endif
                                                                                @endforeach

                                                                            </select>

                                                                        </div>
                                                                    </template>


                                                                    <template x-if="gsheet_type == 'new_sheet'">
                                                                        <div class="from-group">
                                                                            
                                                                            <label>Name your new Google Sheet (Put a unique one)</label>
                                                                            
                                                                            <div class="">
                                                                                <input type="text" @change='new_sheet_name = $el.value' class="form-control" name="new_sheet_name" /> 

                                                                                <div class="mt-2">
                                                                                    <button class="btn btn-dark" @click="action_create_new_sheet_with_vars">Create Sheet</button>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>

                                                                </div>


                                                            </template>


                                                            <template x-if="!google_acc_connected">
                                                                <h6>Seems like your google account is not connected. <a class="text-underline" target="_blank" href="/sheets/init"> Connect here</a></h6>
                                                                <span> Lets connect your Google account first</span>
                                                                
                                                                <div class="mt-3">

                                                                    <a target="blank" href="/sheets/init"  class="btn secondary">Connect Google Account</a>

                                                                </div>
                                                            </template>
                                                            

                                                        </div>


                                                    </template>




                                                    <template x-if="new_ds_type == 'csv'">

                                                        <span>Upload a CSV File</span>


                                                    </template>



                                                    <div class="mt-4">
                                                        <button class="btn ds-existing" @click='(e) => { action_switch_ds_type(e, "existing") }'>Use existing datasource</button>
                                                    </div>
                                                
                                                </div>

                                            </template>


                                            @error('type')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        
                                    </div>


                                </template>


                                <template x-if="data_source_id != null">
                                    <div class="">

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2" style="margin-right: 8px;font-size: 17px;">
                                                        <i class="fas fa-check" aria-hidden="true"></i>
                                                    </span>

                                                    <span>Connected Datasource: <span class="text-bold" x-text="getSelectedDSLabel()"></span></span>

                                                </div>

                                                <p class="card-text"><span @click="action_removeSelectedDataSource"  style="font-size: 14px; cursor:pointer;" class="text-decoration-underline">Remove</span></p>
                                            </div>                                          
                                        </div>


                                    </div>
                                    


                                </template>
                                
                                <template x-data="message" x-init="getSheetChanges()">
                                    <div class="">
                                    </div>
                                </template>

                            <!-- </div> -->
                            
                        </div>

                        <div class="col-12"  x-show="ds_loading == true" >

                            <div class="col-4 offset-8 d-flex align-items-center justify-content-end">

                                <div class="spinner-border text-dark mr-2" style="width: 1.5rem;height: 1.5rem; margin-right : 8px;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>

                                <span>Processing...</span>

                            </div>

                        </div>
                       
                        <input type="hidden" name="data_source_name" id="dataSourceName" value="">
                        <!-- <input type="hidden" name="data_source_id" id="dataSourceId" value=""> -->
                        <textarea class="d-none" name="data_source_headers" id="sourceTextArea" value=""></textarea>
                        <input type="hidden" id="variableArrayInput" name="variableArray" value="">
                        <input type="hidden" id="datasourceArrayInput" name="sourceArray" value="">
                        

                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="button" @click="action_handle_ds_step" class="btn bg-gradient-dark btn-md mt-4 mb-4 d-none">Next</button>
                    </div>

                    <div class="mt-3  alert alert-primary alert-dismissible fade show d-none thirdError" role="alert">
                        <span class="alert-text text-white">You have to fill 'Data Source' section completely to proceed next.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
            </div>



            <div class="card mb-4 mapdata" x-show="currentStep > 3 && requiresMapping == true">

                <template x-if="requiresMapping == true">

                    <div class="">
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

                                            <template x-for="variable in getAvailableVariablesNames()">
                                                
                                                <tr>
                                                    <td x-text="variable"></td>
                                                    <td>

                                                        <select class="form-control" @change="handle_source_field_change" x-bind:vartarget="variable" name="selected_field">

                                                            <template x-for="field in datasourceFields">
                                                                <option x-bind:selected="field == variablesMap[variable].source_field ? 'true' : null" x-bind:value="field" x-text="field"></option>

                                                            </template> 
                                                        
                                                        </select>
                                                    
                                                    </td>
                                                    <td x-text="variablesMap[variable] ? variablesMap[variable].preview_row_data : 'Select a field'"></td>
                                                </tr>


                                            </template>
                                        
                                        
                                        
                                        </tbody>

                                    </table>

                                </div>
                                
                            </div>

                            <div class="d-flex justify-content-start">
                                <button type="button" @click="action_handle_map_step" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
                            </div>

                            <div class="mt-3  alert alert-primary alert-dismissible fade show d-none fifthError" role="alert">
                                <span class="alert-text text-white">You have to fill 'Map Data' section completely to proceed next.</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>

                        </div>

                    </div>

                </template>


                <template>
                    <div class="">
                        
                        <p>As you created a New Google Sheet, your template variables will be added as a header row in your sheet.</p>

                    </div>
                </template>


            </div>


            <div class="card SaveAndStart" x-show="currentStep  > 4">

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Lets save and start</h6>
                </div>


                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex justify-content-start">
                                <input type="hidden" name="data_maps_json" x-bind:value="dataMapJson" />  
                                <input type="hidden" name="selected_datasource_id" x-bind:value="data_source_id ? data_source_id.id : ''" />
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4 submit-form-btn">Save &amp; Start Sync</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

    </form>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




@endsection


@section("javascript")
    
    <script>
        
        var google_acc_connected = @if($google_acc_connected) true @else false @endif ;

    </script>



    <script id="alpine_js" defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.js"></script>

    <script id="create_campaign_script" src="{{ asset('js/create_campaign_script.js') }}" ></script>


@endsection