<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
use App\Http\Requests\Payment\UploadPaymentProofRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function uploadProof(UploadPaymentProofRequest $request): JsonResponse
    {
        $payment = Payment::query()->create(array_merge($request->validated(), [
            'status' => 'pending',
        ]));

        return response()->json($payment, 201);
    }

    public function confirm(ConfirmPaymentRequest $request, int $id): JsonResponse
    {
        $payment = Payment::query()->findOrFail($id);
        $user = $request->attributes->get('auth_user');
        $status = $request->validated('status');

        $payment->update([
            'status' => $status,
            'note' => $request->validated('note'),
            'confirmed_by' => $user->id,
            'confirmed_at' => now(),
        ]);

        if ($status === 'paid') {
            Invoice::query()->findOrFail($payment->invoice_id)->update(['status' => 'paid']);
        }

        return response()->json($payment->refresh());
    }

    public function history(): JsonResponse
    {
        return response()->json(Payment::query()->with(['invoice.contract', 'tenant'])->latest()->paginate(20));
    }
}
