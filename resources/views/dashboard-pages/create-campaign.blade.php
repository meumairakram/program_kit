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


            <div class="card mb-4 d-none website">
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




            <div class="card mb-4 d-none datasource">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Connect/Choose Data Source</h6>
                </div>

                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="card-header pb-0 px-3">
                                <h6 class="mb-0">Add Data source</h6>
                            </div>
                            <div class="form-group">
                                <div class="container @error('user.phone')border border-danger rounded-3 @enderror">
                                    <button type="button" id="add_new_ds" class="btn bg-gradient-primary btn-md mt-4 mb-4">Add new</button>

                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card-header pb-0 px-3">
                                <h6 class="mb-0">Choose existing Data source</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control datasource_sec_required" name="data_source_id" id="dataSource">
                                            <option value="">-- Choose --</option>

                                            @foreach($allDatasources as $ds)
                                                <option value="{{ $ds->id }}" id="{{ $ds->file_path }}" name="{{ $ds->name }} ( {{$ds->type}} )">{{ $ds->name }} ( {{$ds->type}} )</option>
                                            @endforeach

                                        </select>

                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            
                        </div>
                       
                            <input type="hidden" name="data_source_name" id="dataSourceName" value="">
                            <input type="hidden" name="data_source_id" id="dataSourceId" value="">
                            <textarea class="d-none" name="data_source_headers" id="sourceTextArea" value=""></textarea>

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



            <div class="card mb-4 d-none mapdata">
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
                                        <div class="col-md-12" id="tempVariables">
                                            <input class="form-control" value="" type="text" id="tempVariablesInput" name="template_variables">
                                            <!-- <select class="form-control" name="website">
                                                <option value="wordpress">Some name (CSV)</option>
                                                <option value="wordpress">Some Name (Google Sheet)</option>
                                                <option value="wordpress">Bubble</option>
                                            </select> -->
                                        </div>

                                    </div>



                                </div>


                                <div class="col-md-6">
                                    <span class="text-bold">Source field</span>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="form-control csv-headers" name="source_Headers" id="csvHeaders">
                                                <option value=""> </option>
                                            </select>
                                            <!-- <select class="form-control" name="website">
                                                <option value="wordpress">Some name (CSV)</option>
                                                <option value="wordpress">Some Name (Google Sheet)</option>
                                                <option value="wordpress">Bubble</option>
                                            </select> -->
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Template field</td>
                                        <td>Data source field</td>

                                    </tr>

                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control" name="website">
                                                <option value="wordpress">Some name (CSV)</option>
                                                <option value="wordpress">Some Name (Google Sheet)</option>
                                                <option value="wordpress">Bubble</option>
                                            </select>
                                        </td>

                                       <td>
                                            <select class="form-control" name="website">
                                                <option value="wordpress">Empty</option>
                                                <option value="wordpress">Default value</option>
                                                <option value="wordpress">Bubble</option>
                                            </select>
                                        </td>


                                    </tr>

                                </tbody>



                            </table> -->



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


            <div class="card d-none SaveAndStart">

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Lets save and start</h6>
                </div>


                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex justify-content-start">
                                <button type="submit" id="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Save &amp; Start Sync</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

    </form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // website type
    document.getElementById('websiteType').addEventListener('change', function(e)
    {
            const selectedType = this.value;
            if (selectedType === '') {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('type', selectedType);

            $.ajax({
                method: "POST",
                url: "/websites_type",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#selectWebSite').html('<option value=""> </option>');
                        return;
                    }

                    const websiteSelect = $('#selectWebSite');
                    websiteSelect.empty();

                    const websites = response.websites;
                    websiteSelect.append('<option value=""> </option>');
                    websites.forEach(websites => {
                        websiteSelect.append(`<option value="${websites.id}">${websites.website_name} | ${websites.website_url}</option>`);
                    });

                    // Update the hidden input value with the selected website ID
                    websiteSelect.on('change', function() {
                        const selectedWebsiteId = $(this).val();
                        $('#selectWebSite').val(selectedWebsiteId);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#selectWebSite').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });

    // geting post types by selecting website
    document.getElementById('selectWebSite').addEventListener('change', function(e)
    {
            $.ajax({
                method: "get",
                url: "/api/get_post_types",
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#postType').html('<option value=""> </option>');
                        return;
                    }

                    const postType = $('#postType');
                    postType.empty();

                    const type = response.data.post_types; // Corrected access to the array
                    postType.append('<option value=""> </option>');

                    type.forEach(typeItem => { // Use typeItem as an element in the forEach loop
                        postType.append(`<option value="${typeItem}">${typeItem}</option>`);
                    });
                    postType.on('change', function() {
                        const postTypeId = $(this).val();
                        // alert(postTypeId);
                        $('#postType').val(postTypeId);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#postType').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });

    // geting template by post type
    document.getElementById('postType').addEventListener('change', function(e)
    {
            const selectedType = this.value;
            if (selectedType === '') {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('post_type', selectedType);

            $.ajax({
                method: "POST",
                url: "/api/get_templates_by_type",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#template').html('<option value=""> </option>');
                        return;
                    }

                    const template = $('#template');
                    template.empty();

                    const get_templates_by_type = response.data.posts;
                    template.append('<option value=""> </option>');

                    get_templates_by_type.forEach(get_templates_by_type => {
                        template.append(`<option name="${get_templates_by_type.title}" value="${get_templates_by_type.id}">${get_templates_by_type.title}</option>`);
                    });

                    template.on('change', function() {
                        const templateId = $(this).val();
                        const templateName = $(this).find('option:selected').attr('name');
                        // alert(templateId);
                        $('#template').val(templateId);
                        $('#templateName').val(templateName);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#template').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });


    // Template variables section
    const variableData = [];
    const templateTextArea = $('#templateTextArea');
    const mapDataFields = $('#mapDataFields');

    document.getElementById('template').addEventListener('change', function(e) {
        const selectedType = this.value;
        console.log(selectedType);
        if (selectedType === '') {
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('post_id', selectedType);

        $.ajax({
            method: "POST",
            url: "/api/get_template_vars",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response)
                if (!response.success) {
                    console.error('Error: ', response.message);
                    $('#tempVariables').val('');
                    return;
                }

                const variables = response.data.variables;
                const collectedVariables = [];

                variables.forEach(variable => {
                    const variableText = variable.replace(/[{}"]/g, '');
                    collectedVariables.push(variableText);
                    console.log(variableText);
                    
                    const variableDiv = `<div name="${variableText}" class="variable-div">${variableText}</div>`;
                    variableData.push(variableDiv);
                });

                templateTextArea.val(collectedVariables.join('\n'));
                const tempVariablesInput = $('#tempVariablesInput').addClass('d-none');


                // Clear and populate mapDataFields with the variableData
                variableData.forEach(variableDiv => {
                    const csvHeadersSelect = $('#csvHeaders').clone();

                    source_headers.forEach(header => {
                            const csv = csvHeadersSelect.append($(`<option value="${header}">${header}</option>`));
                    });

                    const rowDiv = $('<div>', {
                        class: 'row'
                    }).append(
                        $('<div>', { class: 'col-md-6 mt-2' }).append(variableDiv),
                        $('<div>', { class: 'col-md-6 mt-2' }).append(csvHeadersSelect)
                    );
                    
                    mapDataFields.append(rowDiv);
                });
            }
        });
    });


    // Existing data source
    var source_headers = [];
    document.getElementById('dataSource').addEventListener('change', function(e)
    {
        $('#datasourceNext').removeClass('d-none');

        const csvHeadersSelectId = this.value;
        $('#csvHeaders').val(csvHeadersSelectId);
        const dataSource = $(this).find('option:selected').attr('name');
        $('#dataSourceName').val(dataSource);

        const selectedFilePath =  $(this).find('option:selected').attr('id');
        if (selectedFilePath === '') {
            return;
        }

        const formData = new FormData();
        formData.append('csv_file', selectedFilePath);

        $.ajax({
            method: "POST",
            url: "/api/csv-extract",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log(res)
                if (!res.success) {
                    console.error('Error: ', res.message);
                    $('#csvHeaders').html('<option value="">Nothing Fetched</option>');
                    return;
                }

                const csvHeadersSelect = $('#csvHeaders');
                csvHeadersSelect.empty();

                const headers = res.data.headers;
                console.log(headers);
                csvHeadersSelect.append('<option value=""> </option>');
                source_headers.push(headers);

                headers.forEach(header => {
                   const csvdata = csvHeadersSelect.append($(`<option value="${header}">${header}</option>`));   
                });
                    // updateCsvHeadersOptions('', headers)

                    $('#sourceTextArea').val(headers.join('\n'));

            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the AJAX error if needed
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        });
    });


    // New data source
    $('input[name="csv_file"]').change(function(e) {
        var formValues = new FormData();
        formValues.append('csv_file', e.target.files[0]);
        formValues.append('name',"Umair");

        jQuery.ajax({
            method: "POST",
            url: "/api/csv-preview",
            data: formValues,
            processData: false,
            contentType: false, 
            success: function(res) {
                console.log(res)
                if (!res.success) {
                    console.error('Error: ', res.message);
                    $('#csvHeaders').html('<option value="">Nothing Fetched</option>');
                    return;
                }

                const csvHeadersSelect = $('#csvHeaders');
                csvHeadersSelect.empty();

                const headers = res.data.headers;
                console.log(headers);
                csvHeadersSelect.append('<option value=""> </option>');
                source_headers.push(headers);

                headers.forEach(header => {
                    csvHeadersSelect.append($(`<option value="${header}">${header}</option>`));
                });
                    $('#sourceTextArea').val(headers.join('\n'));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        
        })
    })

        // handle sections based on selected type
        document.getElementById('selectType').addEventListener('change', function(e) 
        {
            var selectedValue = $(this).val();
            if(selectedValue == 'csv'){
                $('.uploadCSV').removeClass('d-none');
                $('.uploadGoogleSheets').addClass('d-none');
            }
            else if(selectedValue == 'google_sheet'){
                $('.uploadGoogleSheets').removeClass('d-none');
                $('.uploadCSV').addClass('d-none');
            }
        });


    // next button dependency section starts
    $('#nextButton').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.first_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.website').removeClass('d-none');
            $('.firstError').addClass('d-none');
        }
        else{
            $('.firstError').removeClass('d-none');
        }
    });

    $('#webNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.web_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.datasource').removeClass('d-none');
            $('.secondError').addClass('d-none');
        }
        else{
            $('.secondError').removeClass('d-none');
        }
    });

    $('#datasourceNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.datasource_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.mapdata').removeClass('d-none');
            $('.thirdError').addClass('d-none');
        }
        else{
            $('.thirdError').removeClass('d-none');
        }
    });

    $('#add_new_ds').on('click', function(e) {
        e.preventDefault();
       
        $('.newdatasource').removeClass('d-none');
        
    });

    $('#newDatasourceNext').on('click', function(e) {
        e.preventDefault();
        $('.mapdata').removeClass('d-none');

        // var isValid = true;
        // $('.datasource_sec_required').each(function() {
        //     if ($(this).val() === '') {
        //         isValid = false;
        //     }
        // });
        // if(isValid == true){
        //     $('.mapdata').removeClass('d-none');
        //     $('.fourthError').addClass('d-none');
        // }
        // else{
        //     $('.fourthError').removeClass('d-none');
        // }
    });

    $('#mapdataNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.mapdata_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.SaveAndStart').removeClass('d-none');
            $('.fifthError').addClass('d-none');
        }
        else{
            $('.fifthError').removeClass('d-none');
        }
    });
    // next button dependency section ends


</script>




@endsection
