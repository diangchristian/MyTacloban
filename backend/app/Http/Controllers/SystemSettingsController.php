<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\SystemSettingsRepositoryInterface;


class SystemSettingsController extends Controller
{
    
    protected $settings;

    public function __construct(SystemSettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }


    public function index(){
        return response()->json($this->settings->index());
    }


    public function update(Request $request){
        $validated = $request->validate([
            'system_name'   => 'required|string|max:255',
            'description'   => 'required|string',
            'logo'          => 'nullable|string',
        ]);

        if($this->settings->update($validated)){
            return response()->json([
                'message' => 'Settings updated successfully!'
            ]);
        }

        return response()->json([
            'message' => 'Error updating system settings'
        ]);


    }




}
