<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\RuleRequest;
use App\Http\Requests\Setting\IndexRequest;
use App\Http\Requests\Setting\UpdateRequest;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function rules(RuleRequest $request): JsonResponse
    {
        return $this->settingService->rules();
    }

    public function index (IndexRequest $request): JsonResponse
    {
        return $this->settingService->index();
    }

    public function update (UpdateRequest $request): JsonResponse
    {
        return $this->settingService->update($request);
    }
}
