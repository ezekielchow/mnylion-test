<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function checkAccess(Request $request)
    {
        try {
            if (empty($request->query('email')) || empty($request->query('featureName'))) {
                throw new \Exception('Missing required parameters');
            }

            if (empty($user = User::firstWhere('email', $request->query('email')))) {
                throw new \Exception('User not found');
            }

            if (empty($feature = Feature::firstWhere('name', $request->query('featureName')))) {
                throw new \Exception('Feature not found');
            }

            $canAccess = $user->features()->whereIsEnabled(true)->whereName($request->query('featureName'))->exists();

            return response()->json(compact('canAccess'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'featureName' => 'required|exists:features,name',
                'email' => 'required|exists:users,email',
                'enable' => 'required|boolean'
            ]);

            $user = User::firstWhere('email', $validated['email']);
            $feature = Feature::firstWhere('name', $validated['featureName']);

            $user->features()->updateExistingPivot($feature->id, [
                'is_enabled' => intval($validated['enable'])
            ]);

            return response()->json(null, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 304);
        }
    }
}
