<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    protected $setting;

    public function __construct(Setting $setting) {
        $this->setting = $setting;
    }

    /**
     * Find a setting by its section.
     *
     * This method retrieves a setting from the database using its unique section.
     *
     * @param string $section The section of the settings to find.
     *
     * @return null|Setting Returns the Setting model if found, null otherwise.
     */
    public function findBySection(string $section): ?Setting {
        return $this->setting::where('section', $section)->first();
    }
}
