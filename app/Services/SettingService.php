<?php

namespace App\Services;

use App\Models\Setting;
use App\Helpers\GeneralHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SettingResource;
use App\Repositories\SettingRepository;
use App\Traits\SettingValidationsTrait;
use App\Http\Requests\Setting\UpdateRequest;

class SettingService
{
    use SettingValidationsTrait;

    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Retrieve the application rules from the settings repository.
     *
     * This method fetches the settings data associated with the 'rules' section
     * and returns it in a structured JSON response. The settings are returned as
     * properties of the corresponding section.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the 'rules' settings data.
     */
    public function rules(): JsonResponse
    {
        // Retrieve settings for the 'rules' section from the repository
        $setting = $this->settingRepository->findBySection('rules');

        // Return the 'rules' properties in a JSON response
        return GeneralHelper::response(
            null,
            $setting->properties,
            201
        );
    }

    /**
     * Get all settings.
     *
     * This method retrieves all settings from the database and returns them
     * as a collection in the JSON response.
     *
     * @return JsonResponse Returns a JSON response containing the collection of settings.
     */
    public function index(): JsonResponse
    {
        return GeneralHelper::response(
            null,
            SettingResource::collection(Setting::all()),
            201
        );
    }

    /**
     * Update settings based on the provided request data.
     *
     * This method updates multiple settings based on the input received in the request.
     * Each section's properties are updated with the provided values.
     *
     * @param UpdateRequest $request The request containing the updated settings data.
     *
     * @return JsonResponse Returns a JSON response with a success message upon completion.
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        // Catch all data of request in array
        $data = $request->toArray();

        foreach ($data as $section => $properties) {
            $setting = $this->settingRepository->findBySection($section);

            $this->existsSection($setting);

            // Update setting section with all properties
            $setting->properties = $properties;
            $setting->save();
        }

        return GeneralHelper::response(
            __('messages.settings_updated_success'),
            [],
            201
        );
    }
}
