<?php

namespace App\Http\Controllers;

use DB;
use Sheets;
use App\Models\GoogleSheet;
use Illuminate\Http\Request;

class GoogleSheetController extends Controller
{
    public function sheets()
    {
        
        $spreadsheetId = '1x9d70jSVllQYzscivrhY-WJWai8gI-52-fWlem3HTIg';
        $sheetName = 'Demo';
        // Retrieve Google Sheets data
        $response = Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->get();

        $array = $response->toArray(); 
        $array['sheetId'] = $spreadsheetId; 
        $array['sheetName'] = $sheetName; 

        return response()->json($array);
    } 

    public function storeSheetData(Request $request)
    {
        return $request;
        $sheetId = $request->input('sheet_id');
        $sheetName = $request->input('sheet_name');
        
        $sheet = new GoogleSheet();
        $sheet->sheet_id = $sheetId;
        $sheet->sheet_name = $sheetName;
        $sheet->save();

        // return response()->json(['message' => 'Sheet data stored in the database.']);
    }

}
