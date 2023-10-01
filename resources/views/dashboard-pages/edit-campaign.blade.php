@extends('layouts.user_type.auth')

@section('content')

<form x-data='$store.edit_campaign_store' @submit="submitCreateCampaign" class="container-fluid py-4" action="{{ route('update-campaign') }}" method="POST" role="form text-left">

            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Update Campaign</h5>

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
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="user-name" class="form-control-label">Title</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control first_sec_required" value="{{$campaign->title}}" type="text" placeholder="Title" id="campaign-title" name="title">
                                            @error('name')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                    </div>
                                </div>
                            </div>

                         <div x-data="{ id: document.getElementById('fetchId').value }">

                            <input type="hidden" name="id" id="fetchId" value="{{$campaign->id}}">
                            
                        </div>


                                <!-- <input type="hidden" name="id" id="fetchId" value="{{$campaign->id}}"> -->


                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control first_sec_required" name="website_type"  value="{{$campaign->website_type}}">
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

                                        <input class="form-check-input mx-auto first_sec_required" type="checkbox" id="flexSwitchCheckDefault" checked>

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
                                            <textarea class="form-control first_sec_required" id="about" rows="3"  value="{{$campaign->description}}" placeholder="Say something about yourself" name="description">{{$campaign->description}}</textarea>
                                        </div>
                                    </div>
                            </div>

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
                                        <select class="form-control web_sec_required" @change="handleWebsiteTypeChange" name="website_type" id="websiteType">
                                            <option value="{{$campaign->website_type}}">{{$campaign->type}}</option>
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
                                            <option value="{{$campaign->website_id}}">{{$campaign->website_name}} || {{$campaign->website_url}}</option>
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
                                            <option value="{{$campaign->post_type}}">{{$campaign->post_type}}</option>
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
                                            <!-- <option value="{{$campaign->wp_template_id}}">{{$campaign->templateName}}</option> -->
                                            <template x-for="template_option in avl_templates"> 
                                                <option x-bind:value="template_option.id" x-text="`${template_option.title} - #${template_option.id}`"></option>

                                            </template>
                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                             <input type="hidden" name="template_name" id="templateName" value="{{$campaign->templateName}}">
                             <textarea class="d-none" name="variables" id="templateTextArea" value="{{$campaign->variables}}">{{$campaign->variables}}</textarea>

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

                                <template x-if="data_source_id == null">
                                    <div class="form-group">
                                        <div class="@error('user.phone') border border-danger rounded-3 @enderror">

                                            
                                            <template x-if="ds_source_type == 'existing'">
                                                <div class="existing-datasource">
                                                    <select @change="set_ds_id" class="form-control datasource_sec_required" name="data_source_id" id="dataSource">
                                                        <option value="{{$campaign->data_source_id}}">{{$campaign->name}}({{$campaign->data_source_type}})</option>

                                                        @foreach($allDatasources as $ds)
                                                            <option value="{{ $ds->id }}" name="{{ $ds->name }} ( {{$ds->type}} )">{{ $ds->name }} ( {{$ds->type}} )</option>
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

                                                                                <option value="">Existing sheet options here</option>
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
                                                                <h6>Seems like your google account is not connected</h6>
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

                            </div>

                            <!-- <div class="col-md-3">
                                @if(isset($absolute_file_path))
                                    <label for="user.location" class="form-control-label d-block mb-0">Existing file</label>
                                    <div class="form-group" id="fileShow">
                                        <div class="alert bg-gray-200 border border-solid border-2 text-black alert-dismissible fade show" role="alert">
                                            <span class="alert-text">{{$campaign->dataSourceName}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close text-dark" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div> -->

 
                            <!-- <div class="col-12"  x-show="ds_loading == true" >

                                <div class="col-4 offset-8 d-flex align-items-center justify-content-end">

                                    <div class="spinner-border text-dark mr-2" style="width: 1.5rem;height: 1.5rem; margin-right : 8px;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>

                                    <span>Processing...</span>

                                </div>

                            </div> -->


                            <input type="hidden" name="data_source_name" id="dataSourceName" value="">
                            <!-- <input type="hidden" name="data_source_id" id="dataSourceId" value=""> -->
                            <textarea class="d-none" name="data_source_headers" id="sourceTextArea" value="{{$campaign->data_source_headers}}">{{$campaign->data_source_headers}}</textarea>
                            <input type="hidden" id="variableArrayInput" name="variableArray" value="">
                            <input type="hidden" id="datasourceArrayInput" name="sourceArray" value="">

                    </div>


                </div>
            </div>

            <!-- <div class="card mb-4 d-none newdatasource">
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
            </div> -->


        <div class="card mb-4 mapdata" x-show="requiresMapping == true">

            <template x-if="requiresMapping == true">
                <div class="card mb-4 mapdata" x-data="editCampaign">
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
                                                    <select class="form-control .sourceFieldClass" @change="handle_source_field_change" x-bind:vartarget="variable" name="selected_field">
                                                        <template x-for="field in datasourceFields">
                                                            <option x-bind:selected="field === variablesMap[variable]?.source_field ? 'true' : null" x-bind:value="field" x-text="field"></option>
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

            <!-- <div class="card mb-4 mapdata">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Map Data Fields</h6>
                </div>

                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-8 offset-md-2">

                            <div class="row" id="mapDataFields">
                                <div class="col-md-6">

                                    <span class="text-bold">Template fields</span>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <input class="form-control" type="text" id="tempVariablesInput" name="template"  value="{{$campaign->variables}}">
                                        
                                        </div>

                                    </div>



                                </div>


                                <div class="col-md-6">
                                    <span class="text-bold">Source field</span>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="form-control csv-headers" name="source_field" id="csvHeaders"  value="{{$campaign->data_source_headers}}">
                                                <option value="{{$campaign->data_source_headers}}">{{$campaign->data_source_headers}}</option>
                                            </select>
                                         
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                   

                </div>
            </div> -->


            <div class="card SaveAndStart" >

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Lets save and start</h6>
                </div>


                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex justify-content-start">
                                <input type="hidden" name="data_maps_json" x-bind:value="dataMapJson" />  
                                <input type="hidden" name="selected_datasource_id" x-bind:value="data_source_id.id" />
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4 submit-form-btn">Update &amp; Start Sync</button>
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
     
        var mapData = <?php echo json_encode($mapData); ?>;
        var post_type = <?php echo json_encode($campaign->post_type); ?>;
        // console.log(post_type);
        var template_id = <?php echo json_encode($campaign->wp_template_id); ?>;
        var ds_id = <?php echo json_encode($campaign->data_source_id); ?>;

    </script>



    <script id="alpine_js" defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.js"></script>

    <script id="edit_campaign_script" src="{{ asset('js/edit_campaign_script.js') }}" ></script>


@endsection
