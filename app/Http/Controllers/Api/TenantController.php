<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $keyword = $request->query('keyword');

        $query = Tenant::query()->with(['room', 'contracts']);
        if ($keyword) {
            $query->where(function ($q) use ($keyword): void {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('id_card', 'like', "%{$keyword}%");
            });
        }

        return response()->json($query->paginate(20));
    }

    public function store(StoreTenantRequest $request): JsonResponse
    {
        $tenant = Tenant::query()->create($request->validated());

        return response()->json($tenant, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            Tenant::query()->with(['room', 'contracts'])->findOrFail($id)
        );
    }

    public function update(StoreTenantRequest $request, int $id): JsonResponse
    {
        $tenant = Tenant::query()->findOrFail($id);
        $payload = $request->validated();
        unset($payload['id_card']);

        $tenant->update($payload);

        return response()->json($tenant->refresh());
    }
}
