<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreMaintenanceRequest;
use App\Http\Requests\Maintenance\UpdateMaintenanceStatusRequest;
use App\Models\MaintenanceRequest;
use Illuminate\Http\JsonResponse;

class MaintenanceRequestController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(MaintenanceRequest::query()->with(['room', 'tenant'])->latest()->paginate(20));
    }

    public function store(StoreMaintenanceRequest $request): JsonResponse
    {
        $item = MaintenanceRequest::query()->create(array_merge($request->validated(), ['status' => 'pending']));

        return response()->json($item, 201);
    }

    public function updateStatus(UpdateMaintenanceStatusRequest $request, int $id): JsonResponse
    {
        $item = MaintenanceRequest::query()->findOrFail($id);
        $item->update(['status' => $request->validated('status')]);

        return response()->json($item->refresh());
    }
}
