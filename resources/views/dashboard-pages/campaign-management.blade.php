@extends('layouts.user_type.auth')

@section('content')

<div>
    <!-- <div class="alert alert-secondary mx-4" role="alert">
        <span class="text-white">
            <strong>Add, Edit, Delete features are not functional!</strong> This is a
            <strong>PRO</strong> feature! Click <strong>
            <a href="https://www.creative-tim.com/live/soft-ui-dashboard-pro-laravel" target="_blank" class="text-white">here</a></strong>
            to see the PRO product!
        </span>
    </div> -->



    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-4">

                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Campaigns</h5>
                        </div>
                        <a href="{{url('create-campaign')}}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Create New</a>
                    </div>

                    <div class="row pt-3px">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label ms-md-0 ">Search by Title and Website URL</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                        <div class="ms-md-0 pe-md-3 d-flex align-items-center">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="search" value="" placeholder="Search here...">
                                                <span class="input-group-text text-body" id="searchInput"><i class="fas fa-search" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                        
                                    </div>
                                </div>
                            </div>
                   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Filter by Status</label>
                                    <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                    <select class="form-control" name="filter" id="myInput" value="Filter Data" placeholder="Filter Data">
                                        <option value="">Filter</option>
                                        <option value="Paused">Paused</option>
                                        <option value="Active">Active</option>
                                        <option value="ready">Ready</option>
                                    </select>
                        
                                    </div>
                                </div>
                            </div>
                    </div>

                </div>
                @if(session('message'))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                        {{ session('message') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>


                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Title
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Website URL
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Data Source
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Creation Date
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="myTable">
                                @foreach($campaigns as $camp)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">CP-{{$camp->id}}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->title}}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->website_url}}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->name}} ({{$camp->type}})</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->status}}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                @php
                                                    $lastSynced = date_create($camp->created_at);
                                                    if ($lastSynced) {
                                                        echo $lastSynced->format('m/d/Y');
                                                    } else {
                                                        // Handle invalid date here
                                                        echo "Invalid Date";
                                                    }
                                                @endphp

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('edit-campaign')}}/{{$camp->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit Campaign">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill text-secondary" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                </svg>
                                                <!-- <i class="fas fa-user-edit text-secondary"></i> -->
                                            </a>
                                            <span>
                                            <a href="{{ url('delete-campaign')}}/{{$camp->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete Campaign">
                                                <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                            </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


