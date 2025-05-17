<?php

namespace App\Traits;

use App\Models\Setting;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;

trait SettingValidationsTrait
{
    /**
     * Check if a setting exists.
     *
     * This method verifies whether the provided setting instance exists.
     * If the setting is null, it returns a response indicating that the setting was not found.
     *
     * @param Setting|null $setting The setting instance to check for existence.
     *
     * @return JsonResponse|null Returns a JSON response indicating the result, or null if the setting exists.
     */
    protected function existsSection(?Setting $setting): ?JsonResponse {
        if (!$setting) {
            return GeneralHelper::response(
                __('messages.settings_not_found'),
                [],
                404
            );
        }

        return null;
    }
}
