<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Invoice::query()->with(['contract', 'payments'])->paginate(20));
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $electricityUsage = $payload['electricity_current'] - $payload['electricity_previous'];
        $waterUsage = $payload['water_current'] - $payload['water_previous'];

        $total = $payload['rent']
            + $electricityUsage * $payload['electricity_price']
            + $waterUsage * $payload['water_price']
            + $payload['service_fee'];

        $invoice = Invoice::query()->create(array_merge($payload, [
            'invoice_no' => 'INV-'.date('YmdHis').'-'.random_int(100, 999),
            'electricity_usage' => $electricityUsage,
            'water_usage' => $waterUsage,
            'total_amount' => $total,
            'status' => 'unpaid',
        ]));

        return response()->json($invoice, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Invoice::query()->with(['contract.tenant', 'payments'])->findOrFail($id));
    }
}
