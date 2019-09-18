<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class SettingsController extends Controller
{

    private $hour = 23;

    private $minutes = 00;

    private $seconds = 0;

    private $file = "settings_archive.json";

    public function archive(Request $request)
    {

        $data = array(
            "hour" => $request->hour,
            "minute" => $request->minute,
            "days" => $request->days,
        );

        $data = \json_encode($data);

        Storage::put($this->file, $data);

        return response()->json($data);
    }

    public function getSettings()
    {

        $setting = Storage::get($this->file);

        $setting = \json_decode($setting);

        return response()->json($setting);
    }
}
