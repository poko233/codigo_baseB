<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\PermissionService;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function __construct(private PermissionService $permissionService) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => $this->permissionService->getSidebar($user),
        ]);
    }
}
