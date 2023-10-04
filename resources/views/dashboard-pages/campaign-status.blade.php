@extends('layouts.user_type.auth')

@section('content')

    <div class="container-fluid">

        <div class="">
            <div class="contianer-fluid">
                <div class="card">

                    <div class="card-body">
                        
                        <h3>Initializing campaign...</h3>  
                        
                        
                        <div class="mt-2 mb-2">
                            <span><strong>some new campaign</strong></span>                        
                        </div>

                        <div class="">
                            <span>Website url: <strong>https://erererere</strong></span>                        
                        </div>
                    
                        <div class="">
                            <span>Type: <strong>Google Sheets</strong></span>                        
                        </div>  

                        <div class="my-2">
                            <span>Verifying Website status...</span>
                        </div>

                        
                        
                    </div>
                
                </div>
            
            </div>
        </div>
    
    
    
    </div>


@endsection



@section("javascript")
    
    



    <script id="alpine_js" defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.js"></script>

    <script id="create_campaign_script" src="{{ asset('js/create_campaign_script.js') }}" ></script>


@endsection

