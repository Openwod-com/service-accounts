<?php

namespace Openwod\ServiceAccounts\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkPermission(Request $request, string $permission)
    {
        if(str_contains($request->header('Authorization'), 'ServiceAccount ')) {
            // Format ServiceAccount TOKEN
            if(explode('ServiceAccount ', $request->header('Authorization'))[1] == config('service-accounts.api_token')) {
                return true;
            }
        }
        $svc = auth()->guard('svc')->user();
        if ($svc == null) {
            return false;
        }
        return $svc->tokenCan($permission);
    }
}
