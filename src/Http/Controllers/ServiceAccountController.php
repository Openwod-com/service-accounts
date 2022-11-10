<?php

namespace Openwod\ServiceAccounts\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Openwod\Quickmetrics\Quickmetrics;
use Openwod\ServiceAccounts\Models\ServiceAccount;

class ServiceAccountController extends Controller
{
    public function store(Request $request)
    {
        if(!$this->checkPermission($request, 'service-accounts.create')) {
            return abort(401, "Authentication denied");
        }

        // Returns error if invalid.
        $request->validate([
            'name' => 'required|alpha_dash',
            // format something.somthing,something.something.something
            // Not allowed to end with dot or comma
            'permissions' => 'required|regex:/^(([a-z0-9-]+\.?)+(?<!\.),?)+(?<!,)$/i',
        ]);

        $permissions = explode(',', $request->permissions);
        // One account has always one token.
        try {
            $svc = new ServiceAccount([
                "name" => $request->name
            ]);
            $svc->save();
        } catch (QueryException $ex) {
            return ["status" => "error", "error" => "Specified name is already in use"];
        }
        $token = $svc->createToken($request->name, $permissions)->plainTextToken;
        return ["token" => $token];
    }
}
