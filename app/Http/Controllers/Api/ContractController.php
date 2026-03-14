<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\RenewContractRequest;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Models\Contract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ContractController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Contract::query()->with(['tenant', 'room'])->paginate(20));
    }

    public function store(StoreContractRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $activeExists = Contract::query()
            ->where('room_id', $payload['room_id'])
            ->where('status', 'active')
            ->exists();

        if ($activeExists) {
            return response()->json(['message' => 'Room already has an active contract'], 422);
        }

        $contract = Contract::query()->create(array_merge($payload, ['status' => 'active']));

        return response()->json($contract, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Contract::query()->with(['tenant', 'room', 'invoices'])->findOrFail($id));
    }

    public function renew(RenewContractRequest $request, int $id): JsonResponse
    {
        $contract = Contract::query()->findOrFail($id);
        if ($contract->status !== 'active') {
            return response()->json(['message' => 'Only active contract can be renewed'], 422);
        }

        $payload = $request->validated();
        if (Carbon::parse($payload['end_date'])->lte(Carbon::parse($contract->end_date))) {
            return response()->json(['message' => 'end_date must be greater than current end_date'], 422);
        }

        $contract->update([
            'end_date' => $payload['end_date'],
            'monthly_rent' => $payload['monthly_rent'] ?? $contract->monthly_rent,
            'deposit_amount' => $payload['deposit_amount'] ?? $contract->deposit_amount,
        ]);

        return response()->json($contract->refresh());
    }

    public function terminate(int $id): JsonResponse
    {
        $contract = Contract::query()->findOrFail($id);
        $contract->update(['status' => 'terminated']);

        return response()->json($contract->refresh());
    }
}
