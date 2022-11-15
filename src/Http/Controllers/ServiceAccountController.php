<?php

namespace Openwod\ServiceAccounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Openwod\ServiceAccounts\Models\ServiceAccount;

class ServiceAccountController extends Controller
{
    public function store(Request $request)
    {
        if(!$this->checkPermission($request, 'service-accounts.create'))
            return abort(401, "Authentication denied");

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

    public function show(Request $request, $name)
    {
        if(!$this->checkPermission($request, 'service-accounts.show'))
            return abort(401, "Authentication denied");

        $svc = ServiceAccount::where('name', $name)->firstOrFail();

        if(count($svc->tokens) == 0)
            return abort(404, "No service account with specified name");

        return [
            "name" => $name,
            # Using 0 becuase this package does only support one token per account.
            "permissions" => $svc->tokens[0]["abilities"]
        ];
    }

    public function destroy(Request $request, $name)
    {
        if(!$this->checkPermission($request, 'service-accounts.destroy'))
            return abort(401, "Authentication denied");

        $svc = ServiceAccount::where('name', $name)->firstOrFail();
        $svc->delete();

        return ["status" => "success"];
    }
}
