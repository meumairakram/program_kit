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
{{--                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">--}}
{{--                                        Description--}}
{{--                                    </th>--}}
{{--                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">--}}
{{--                                        Type--}}
{{--                                    </th>--}}
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
                            <tbody>

                                @foreach ($campaigns as $camp)

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
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->dataSourceName}} ({{$camp->dataSourceType}})</p>
                                        </td>
{{--                                        <td class="text-center">--}}
{{--                                            <p class="text-xs font-weight-bold mb-0">{{$camp->description}}</p>--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center">--}}
{{--                                            <p class="text-xs font-weight-bold mb-0">{{$camp->type}}</p>--}}
{{--                                        </td>--}}
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{$camp->status}}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                @php
                                                   echo ($camp->created_at);
                                                @endphp

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('edit-campaign')}}/{{$camp->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit Campaign">
                                                <i class="fas fa-user-edit text-secondary"></i>
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
