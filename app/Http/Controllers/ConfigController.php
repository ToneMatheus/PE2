<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Index_Value;
use App\Models\Consumption;
use App\Models\Meter;

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

    public function updateHost($ip)
    {
        $configPath = config_path('app.php');
        $config = File::get($configPath);

        $newConfig = preg_replace(
            "/'host_domain'/",
            "//'host_domain'",
            $config
        );

        File::put($configPath, $newConfig);
        $config2 = File::get($configPath);
        
        $secondConfig = str_replace(
            "//do not delete",
            "//do not delete\n\t'host_domain' => 'http://{$ip}',",
            $config2
        );

        File::put($configPath, $secondConfig);
        
        return redirect()->route('dashboard');
    }

    public function updateSeeder() {
        $indexID = Index_Value::insertGetId([
            'meter_id' => 2,
            'reading_date' => '2025-01-17',
            'reading_value' => '6700',
        ]);

        Consumption::insert([
            'start_date' => '2024-01-01',
            'end_date' => '2025-01-17',
            'consumption_value' => 100,
            'prev_index_id' => 6,
            'current_index_id' => $indexID
        ]);
        
        return redirect()->route('index-cron-job');
    }
}
