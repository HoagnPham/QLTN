<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hostel\StoreHostelRequest;
use App\Models\Hostel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('auth_user');
        $hostels = Hostel::query()->where('owner_id', $user->id)->withCount('rooms')->get();

        return response()->json($hostels);
    }

    public function store(StoreHostelRequest $request): JsonResponse
    {
        $user = $request->attributes->get('auth_user');
        $hostel = Hostel::query()->create(array_merge(
            $request->validated(),
            ['owner_id' => $user->id]
        ));

        return response()->json($hostel, 201);
    }
}
