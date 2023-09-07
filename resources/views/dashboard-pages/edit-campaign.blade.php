@extends('layouts.user_type.auth')

@section('content')

    <form class="container-fluid py-4" action="{{ route('update-campaign') }}" method="POST" role="form text-left">
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
                            <input type="hidden" name="id" value="{{$campaign->id}}">
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
                                        <select class="form-control web_sec_required" name="website_type"  value="" id="websiteType">
                                            <option value="{{$campaign->website_type}}">{{$campaign->website_type}}</option>
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
                                        <select class="form-control web_sec_required" name="website_id"  value="" id="selectWebSite">
                                            <option value="{{$campaign->website_id}}">{{$campaign->website_name}} || {{$campaign->website_url}}</option>

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
                                        <select class="form-control web_sec_required" name="post_type"  value="" id="postType">
                                            <option value="{{$campaign->post_type}}">{{$campaign->post_type}}</option>
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


                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Template</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control web_sec_required" name="wp_template_id"  value="{{$campaign->wp_template_id}}" id="template">
                                            <option value="{{$campaign->wp_template_id}}">{{$campaign->templateName}}</option>
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
                                <div class="form-group">
                                        <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                            <select class="form-control datasource_sec_required" name="data_source_id" id="dataSource" value="{{$campaign->data_source_id}}">
                                                <option value="{{$campaign->data_source_id}}">{{$campaign->dataSourceName}}</option>

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
                            </div>


                            <input type="hidden" name="data_source_name" id="dataSourceName" value="">
                            <input type="hidden" name="data_source_id" id="dataSourceId" value="">
                            <textarea class="d-none" name="data_source_headers" id="sourceTextArea" value="{{$campaign->data_source_headers}}">{{$campaign->data_source_headers}}</textarea>
                            <input type="hidden" id="variableArrayInput" name="variableArray" value="">
                            <input type="hidden" id="datasourceArrayInput" name="sourceArray" value="">

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

                </div>
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


            <div class="card SaveAndStart">

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Lets save and start</h6>
                </div>


                <div class="card-body pt-4 p-3">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Update &amp; Start Sync</button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

    </form>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

      // getting post types by selecting website
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

    // geting template Variables
    document.getElementById('template').addEventListener('change', function(e)
    {
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

                    const variableDiv = `<div class="variable-div">${variableText}</div>`;

                    $('#tempVariablesInput').addClass('d-none');
                    const csvHeadersClone = $('#csvHeaders').clone();

                    const rowDiv = $('<div>', {
                        class: 'row'
                    }).append(
                        $('<div>', { class: 'col-md-6 mb-2' }).append(variableDiv),
                        $('<div>', { class: 'col-md-6 mb-2' }).append(csvHeadersClone)
                    );
                    $('#mapDataFields').append(rowDiv);
                });

                $('#templateTextArea').val(collectedVariables.join('\n'));

            },

            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#tempVariables').val(''); // Clear the input field
            }
        });
    });

    document.getElementById('dataSource').addEventListener('change', function(e) {

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
                    $('#csvHeaders').html('<option value="">-- Choose --</option>');
                    return;
                }

                const csvHeadersSelect = $('#csvHeaders');
                csvHeadersSelect.empty();

                const headers = res.data.headers;
                csvHeadersSelect.append('<option value="">Choose Fields of selected file</option>');
                headers.forEach(header => {
                    csvHeadersSelect.append(`<option value="${header}">${header}</option>`);
                });

                $('#sourceTextArea').val(headers.join('\n'));

            },

            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the AJAX error if needed
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        });
    });

    // next button dependency section starts
     
    // next button dependency section ends

</script> -->




@endsection
