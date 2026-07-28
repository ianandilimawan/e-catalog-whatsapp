<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'app_logo',
        'logo_type',
        'logo_text',
        'favicon',
        'maintenance_mode',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'theme_default',
        'sidebar_style',
    ];

    protected $casts = [
        'logo_type' => 'string',
    ];

    /**
     * Get the current settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            // Create default settings if none exist
            $settings = self::create([
                'app_name' => 'Admin Panel',
                'app_logo' => null,
                'logo_type' => 'text',
                'logo_text' => 'Admin Panel',
            ]);
        }

        return $settings;
    }
}
