<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Setting;

class SettingsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get settings data and share it with all views
        try {
            $settings = Setting::first();
        } catch (\Exception $e) {
            $settings = null;
        }
        
        // Provide default values if no settings exist
        if (!$settings) {
            $settings = (object) [
                'time_format' => '24-hour',
                'date_format' => 'Y-m-d',
                'currency' => 'USD',
                'working_hour' => 8,
                'logo' => null,
                'task_presets' => []
            ];
        }
        
        $view->with('globalSettings', $settings);
    }
}
