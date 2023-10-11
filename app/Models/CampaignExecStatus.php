<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignExecStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "campaign_exec_status";


    public static function setCampaignStatus(int $campaign_id, string $status) : void {
        
        $execStatus = self::where(['campaign_id' => $campaign_id])->first();

        if($execStatus) {
            $execStatus->update([
                'status' => $status,
                'last_status' => $execStatus->status
            ]);
        }
        
    
    }



}
