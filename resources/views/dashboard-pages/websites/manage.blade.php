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
                            <h5 class="mb-0">All Websites</h5>
                        </div>
                        <a href="{{url('add-website')}}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Add New</a>
                    </div>

                    <div class="row pt-3px">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user.phone" class="form-control-label ms-md-0 ">Search by Name or ID</label>
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

                    </div>

                </div>

                        @if($errors->any())
                            <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                                <span class="alert-text text-white">
                                {{$errors->first()}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
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
                                        Name
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Type
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        URL
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

                                @foreach ($websites as $web)

                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">WS-{{$web->id}}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{$web->website_name}}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{$web->type}}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{$web->website_url}}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{$web->is_authenticated}}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                @php
                                                   echo ($web->created_at)->format('m/d/Y');
                                                @endphp

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('edit-website')}}/{{$web->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit Website">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                            <a href="{{ url('delete-website')}}/{{$web->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete Website">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){   

        $("#searchInput").on("click", function() {
            var value = $('#search').val().toLowerCase();
            $("#myTable tr").filter(function() {
                var id = $(this).find("td:eq(0)").text().toLowerCase(); // Search by the first column (id)
                var name = $(this).find("td:eq(1)").text().toLowerCase(); // Search by the second column (name)
                $(this).toggle(id.indexOf(value) > -1 || name.indexOf(value) > -1);
            });
        });

        $("#search").on("input", function() {
            var value = $('#search').val().toLowerCase();
            if (value === "") {
                $("#myTable tr").show();
            }
        });


    });
</script>

@endsection
