<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;

use App\Models\DataSourceField;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class handleSendTemplateVars implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $name;
    /**
     * Create a new job instance.
     */
    public function __construct($name)
    {
        //  

        $this->name = $name;

        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // $request_url = "https://enalxzct1sfuc.x.pipedream.net";

        // Http::post($request_url, array('var1' => $this->name));

        $ds_field = DataSourceField::create(array(
            'campaign_id' => 100,
            'data_source' => 1,
            'data_source_headers' => $this->name
        ));

        
    }
}
