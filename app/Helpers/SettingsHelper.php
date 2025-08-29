<?php

if (!function_exists('globalSettings')) {
    /**
     * Get global settings data
     *
     * @param string|null $key
     * @return mixed
     */
    function globalSettings($key = null)
    {
        try {
            $settings = \App\Models\Setting::first();
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
        
        // If a specific key is requested, return that value
        if ($key) {
            return $settings->{$key} ?? null;
        }
        
        // Return the entire settings object
        return $settings;
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format date according to global settings
     *
     * @param string|Carbon $date
     * @return string
     */
    function formatDate($date)
    {
        try {
            $format = globalSettings('date_format') ?: 'Y-m-d';
            
            if ($date instanceof \Carbon\Carbon) {
                return $date->format($format);
            }
            
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            // Fallback to default format if there's an error
            if ($date instanceof \Carbon\Carbon) {
                return $date->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        }
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time according to global settings
     *
     * @param string|Carbon $time
     * @return string
     */
    function formatTime($time)
    {
        try {
            $format = globalSettings('time_format') === '12-hour' ? 'g:i A' : 'H:i';
            
            if ($time instanceof \Carbon\Carbon) {
                return $time->format($format);
            }
            
            return \Carbon\Carbon::parse($time)->format($format);
        } catch (\Exception $e) {
            // Fallback to 24-hour format if there's an error
            if ($time instanceof \Carbon\Carbon) {
                return $time->format('H:i');
            }
            return \Carbon\Carbon::parse($time)->format('H:i');
        }
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format currency according to global settings
     *
     * @param float $amount
     * @return string
     */
    function formatCurrency($amount)
    {
        try {
            $currency = globalSettings('currency') ?: 'USD';
        } catch (\Exception $e) {
            $currency = 'USD';
        }
        
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CHF' => 'CHF ',
            'CNY' => '¥',
            'INR' => '₹',
            'DKK' => 'kr',
        ];
        
        $symbol = $symbols[$currency] ?? $currency . ' ';
        
        return $symbol . number_format($amount, 0);
    }
}
