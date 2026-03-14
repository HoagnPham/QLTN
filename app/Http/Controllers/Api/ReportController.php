<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $totalRevenue = Invoice::query()->where('status', 'paid')->sum('total_amount');

        $monthlyRevenue = Invoice::query()
            ->select('billing_month', DB::raw('SUM(total_amount) as revenue'))
            ->where('status', 'paid')
            ->groupBy('billing_month')
            ->orderBy('billing_month')
            ->get();

        $totalRooms = Room::query()->count();
        $availableRooms = Room::query()->where('status', 'available')->count();
        $vacancyRate = $totalRooms > 0 ? round(($availableRooms / $totalRooms) * 100, 2) : 0;

        return response()->json([
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'vacancy_rate' => $vacancyRate,
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
        ]);
    }
}
