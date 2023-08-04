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
                                        <select class="form-control web_sec_required" name="website" id="websiteType">
                                            <option value="wordpress"> </option>
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
                                        <select class="form-control web_sec_required" name="website" id="selectWebSite">
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
                                        <select class="form-control web_sec_required" name="wp_template_id" id="postType">
                                            <option value="1"> </option>
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
                                            <option value="1">Template title</option>
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

                        </div>
                        <div class="d-flex justify-content-start">
                                <button type="button" id="webNext" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
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
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Data source</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control datasource_sec_required" name="website" id="dataSource">
                                            <option value="">-- Choose --</option>

                                            @foreach($allDatasources as $ds)
                                                <option value="{{ $ds->file_path }}">{{ $ds->name }} ( {{$ds->type}} )</option>
                                            @endforeach

                                        </select>
                                        
                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                        
                                    </div>
                                </div>  
                            </div>
                    </div> 
                    <div class="d-flex justify-content-start">
                        <button type="button" id="datasourceNext" class="btn bg-gradient-dark btn-md mt-4 mb-4">Next</button>
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

                            <div class="row">
                                <div class="col-md-6">
                                    
                                    <span class="text-bold">Template fields</span>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input class="form-control mapdata_sec_required" value="" type="text" id="tempVariables" name="template">
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
                                            <select class="form-control csv-headers mapdata_sec_required" name="website" id="csvHeaders">
                                                <option value="">-- Select Data Source First --</option>
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
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Save &amp; Start Sync</button>
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
                        websiteSelect.append(`<option value="${websites.id}">Name: ${websites.website_name} | URL:${websites.website_url}</option>`);
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
                        template.append(`<option value="${get_templates_by_type.id}">${get_templates_by_type.title}</option>`);
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

                const variables = response.data.variables.join(' '); 
                $('#tempVariables').val(variables); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the AJAX error if needed
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#tempVariables').val(''); // Clear the input field
            }
        });
    });

    // data source
    document.getElementById('dataSource').addEventListener('change', function(e) 
    {

        const selectedFilePath = this.value;
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
                csvHeadersSelect.append('<option value=""> </option>'); 
                headers.forEach(header => {
                    csvHeadersSelect.append(`<option value="${header}">${header}</option>`);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the AJAX error if needed
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        });
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
        }
        else{
            Swal.fire({
                text: "You have to fill all the required fields to proceed next.",
                icon: "error",
            });
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
        }
        else{
            Swal.fire({
                text: "You have to fill 'Website' section completely to proceed next.",
                icon: "error",
            });
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
        }
        else{
            Swal.fire({
                text: "You have to fill 'Data Source' section completely to proceed next.",
                icon: "error",
            });
        }
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
        }
        else{
            Swal.fire({
                text: "You have to fill 'Map Data' section completely to proceed next.",
                icon: "error",
            });
        }
    });
    // next button dependency section ends
       

</script>
        

        
       
@endsection