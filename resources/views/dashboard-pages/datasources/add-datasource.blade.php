@extends('layouts.user_type.auth')

@section('content')

    <form class="container-fluid py-4" action="{{ route('store-add-website') }}" method="POST" role="form text-left">
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
                                        <select class="form-control" name="type">
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




            <div class="card mb-4">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Preview your Database</h6>
                </div>

                <div class="card-body pt-4 p-3">
                    
                    <div class="row">
                        
                        <div class="col-md-10">
                            
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
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