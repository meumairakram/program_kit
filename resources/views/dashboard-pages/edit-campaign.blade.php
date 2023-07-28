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
                                        <input class="form-control" value="{{$campaign->title}}" type="text" placeholder="Title" id="campaign-title" name="title">
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
                                        <select class="form-control" name="type"  value="{{$campaign->type}}">
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
                                            <textarea class="form-control" id="about" rows="3"  value="{{$campaign->description}}" placeholder="Say something about yourself" name="description">{{ auth()->user()->about_me }}</textarea>
                                        </div>
                                    </div>
                            </div>

                        </div>
                    

                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Choose Website</h6>
                </div>

                <div class="card-body pt-4 p-3">
                     <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Select Website Type</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control" name="website"  value="{{$campaign->website}}">
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
                                        <select class="form-control" name="website"  value="{{$campaign->website}}">
                                            <option value="">Select a website</option>
                                            
                                            @foreach($allWebsites as $website)

                                                <option value="{{ $website->id }}">{{$website->website_name}} ({{$website->website_url}})</option>

                                            @endforeach

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
                                        <select class="form-control" name="wp_template_id"  value="{{$campaign->wp_template_id}}">
                                            <option value="1">Template title - #18</option>
                                            <option value="2">Template title - #19</option>
                                            <option value="3">Bubble</option>
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
                                        <select class="form-control" name="wp_template_id"  value="{{$campaign->wp_template_id}}">
                                            <option value="1">Template title - #18</option>
                                            <option value="2">Template title - #19</option>
                                            <option value="3">Bubble</option>
                                        </select>
                                        
                                        @error('type')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                        
                                    </div>
                                </div>  
                            </div>  

                        </div>
                </div>


            </div>

            


            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Connect/Choose Data Source</h6>
                </div>

                <div class="card-body pt-4 p-3">
                    
                    <div class="row">
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Data source</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <select class="form-control" name="data_source" id="dataSource"  value="{{$campaign->data_source}}">
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

                   

                </div>

               

            </div>



            <div class="card mb-4">
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
                                            <select class="form-control" name="template_field"  value="{{$campaign->template_field}}">
                                                <option value="wordpress">Some name (CSV)</option>
                                                <option value="wordpress">Some Name (Google Sheet)</option>
                                                <option value="wordpress">Bubble</option>
                                            </select>
                                        </div>

                                    </div>



                                </div>


                                <div class="col-md-6">
                                    <span class="text-bold">Source field</span>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="form-control csv-headers" name="source_field" id="csvHeaders"  value="{{$campaign->source_field}}">
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

                   

                </div>

            </div>


            <div class="card">

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.getElementById('dataSource').addEventListener('change', function(e) {
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the AJAX error if needed
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        });
    });
</script>
        

        
       
@endsection