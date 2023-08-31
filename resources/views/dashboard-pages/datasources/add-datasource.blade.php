@extends('layouts.user_type.auth')

@section('content')

    <form class="container-fluid py-4" action="{{ route('store-datasource') }}" method="POST" enctype="multipart/form-data" role="form text-left">
            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Connect a new Datasource</h5>

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
                                    <label for="user-name" class="form-control-label">Name</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" value="" type="text" placeholder="Title" id="campaign-title" name="name">
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
                        </div>
                </div>
            </div>


            <div class="card mb-4 uploadCSV d-none">
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

            <div class="card mb-4 uploadGoogleSheets d-none">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Google sheets</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- <label for="user.location" class="form-control-label d-block mb-0">Upload sheet</label> -->
                                <!-- <span class="text-xs mb-2 d-block ms-1">Upload your Google sheet</span>  -->
                                <div class="d-flex justify-content-start">
                                    <button type="button" class="btn btn-dark bg-gradient-dark gsheets">Create Google Sheet</button>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>

            <div id="input-container"></div>


            <div class="card mb-4 data-preview d-none">
                <div class="container-fluid">
                    <div class="row pb-0 px-3 pt-3">
                        <div class="col-md-6">
                            <h6 class="mb-0">Primary Key</h6>
                            <div class="primaryKey"></div>
                            <select type="search" class="form-control csv-headers" name="sourceHeader" id="csvHeaders" data-live-search="true">
                                <option value=""> </option>
                            </select>
                            <input type="hidden" id="primary_id" name="primaryKey" value="">
                        </div>
                    </div>
                </div>

                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Preview your Database</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 preview-data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Name
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Type
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No. of Records 
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Last synced
                                            </th>

                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Creation Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">45</p>
                                            </td>
                                            <td>                                          
                                                <p class="text-xs font-weight-bold mb-0">Some anme</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">CSV type</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">345</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Active</p>
                                            </td>

                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Yesterday</p>
                                            </td>
                                            
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Yesterday</p>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Save Data source</button>
                            </div>
                        </div> 

                    </div> 
                </div>
            </div>      
    </form>


        

        
       
@endsection


@section('javascript')

    <script>
        
        jQuery(function($) {

            $('input[name="csv_file"]').change(function(e) {

                var formValues = new FormData();

               
                formValues.append('csv_file', e.target.files[0]);
                formValues.append('name',"Umair");

                jQuery.ajax({
                    method: "POST",
                    url: "/api/csv-preview",
                    data: formValues,
                    processData: false,
                    contentType: false, // Set contentType to false to allow the browser to set the correct content type for the FormData object

                    success: function(res) {


                        if(!res.success ) {

                            if(!$('.data-preview').hasClass('d-none')) {
                                
                                $('.data-preview').addClass('d-none');
                                
                            }

                            $('.data-preview thead tr').html("");
                            $('.data-preview tbody tr').html("");
                        }


                        var headers = res.data.headers;

                        var rows = res.data.preview_rows;


                        if(headers.length < 1){
                            return false;
                        }


                        $('.data-preview').removeClass('d-none');

                        $('.data-preview thead tr').html("");
                        $('.data-preview tbody tr').html("");


                        var headersToPreview = headers.length < 7 ? headers : headers.splice(0, 6);

                        const csvHeadersSelect = $('#csvHeaders');
                        csvHeadersSelect.empty();

                        headersToPreview.forEach(function(item, index) {
                            $('.data-preview thead tr').append(`<th>${item}</th>`);
                            csvHeadersSelect.append($(`<option value="${item}">${item}</option>`));
                        });

                        
                        rows.forEach(function(item, index) {

                            elemRow = $('<tr></tr>');

                            item.forEach(function(rowItem, rowIndex) {
                                elemRow.append(`<td>${rowItem}</td>`)
                            })
                            
                            
                            $('.data-preview tbody').append(elemRow);

                            // elemRow
                        })
                        const randomUniqueNumber = generateRandomUniqueNumber();
                        console.log(randomUniqueNumber);
                        $('.primaryKey').append('"' + randomUniqueNumber +'"');
                        $('#primary_id').val(randomUniqueNumber);
                    }
                })

            })
        
        })

        function generateRandomUniqueNumber() {
            const timestamp = new Date().getTime(); // Current timestamp in milliseconds
            const randomPart = Math.floor(Math.random() * 100000000); // Random number between 0 and 99999999
            const uniqueNumber = parseInt(`${timestamp}${randomPart}`, 10) % 100000000; // Ensure 8 digits
            return uniqueNumber.toString().padStart(8, '0'); // Pad with zeros if necessary
        }

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

        $('.gsheets').on('click', function(e) {
            jQuery.ajax({
                    method: "GET",
                    url: "/sheets",
                    success: function(response) {
                        console.log(response);
                        var sheetId = response.spreadsheetId; 
                        var sheetName = response.sheetName; 

                        var formValues = new FormData();
                        formValues.append('sheet_id', JSON.stringify(sheetId));
                        formValues.append('sheet_name', sheetName);

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');
                        jQuery.ajax({
                            method: "POST",
                            url: "/store-sheet-data",
                            data: formValues,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
                            }
                        });
                    }
            });

        });  

    </script>


    <style>
        tr td, tr th {
            text-align:center;
        }

    </style>

@endsection