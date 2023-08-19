<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;
// use Google\Client;
// use Sheets\Facades\Sheets;


class SheetsController extends Controller
{
    //  

    public function init(Request $request) {
        
        Sheets::spreadsheetByTitle("Sheet New")->addSheet('New Sheet Title');

        die("HELLO WORLD");
    
    }

}
