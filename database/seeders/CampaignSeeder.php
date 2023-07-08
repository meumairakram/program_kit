<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //  

        
        DB::table('campaigns')->insert([
                'id' => 1,
                'title' => 'Road Bike Pages',
                'description' => 'the first campaign',
                'wp_template_id' => 18,
                'type' => 'wordpress',
                'status' => 'ready',    
                'owner_id' => 3,            
                'created_at' => now(),
                'updated_at' => now()
        ]);

        DB::table('campaigns')->insert([
                'id' => 2,
                'title' => 'Vacation Rentals Client',
                'description' => 'the second campaign',
                'wp_template_id' => 18,
                'type' => 'wordpress',
                'status' => 'ready',       
                'owner_id' => 2,         
                'created_at' => now(),
              
                'updated_at' => now()
        ]);

        DB::table('campaigns')->insert([
                'id' => 3,
                'title' => 'Mobile phone skins',
                'description' => 'the third campaign',
                'wp_template_id' => 18,
                'type' => 'wordpress',
                'status' => 'ready',   
                'owner_id' => 2,             
                'created_at' => now(),
                'updated_at' => now()
        ]);


    }
}
