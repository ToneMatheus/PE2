<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ConfigController extends Controller
{
    public function updateNow(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
    
        $newNow = $request->input('date');
    
        // Create a new Carbon instance with the updated date
        $newNowInstance = \Carbon\Carbon::parse($newNow);
    
        $configPath = config_path('app.php');
        $config = File::get($configPath);
    
        // Update the now value in the app config file
        $newConfig = preg_replace(
            "/'now' => Carbon\\\\Carbon::create\(\d{4}, \d{1,2}, \d{1,2}\),/",
            "'now' => Carbon\\\\Carbon::create({$newNowInstance->year}, {$newNowInstance->month}, {$newNowInstance->day}),",
            $config
        );
    
        File::put($configPath, $newConfig);
    
        return redirect()->route('index-cron-job');
    }
}
