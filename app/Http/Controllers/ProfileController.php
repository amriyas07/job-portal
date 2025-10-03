<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\EmployerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileTrait;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Exception;

class ProfileController extends Controller
{
    use FileTrait;
    public function edit(Request $request): View
    {
        return view('profiles.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'             => 'required|exists:users,id',
            'company_name'        => 'required|string|max:255',
            'company_website'     => 'nullable|url',
            'company_logo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'company_address'     => 'nullable|string',
            'company_description' => 'nullable|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $employer_profile = EmployerProfile::updateOrCreate(
                ['employer_id' => $request->user_id],
                [
                    'company_name'        => $request->company_name,
                    'company_website'     => $request->company_website,
                    'company_address'     => $request->company_address,
                    'company_description' => $request->company_description,
                ]
            );

            if ($request->hasFile('company_logo')) {
                if ($employer_profile->company_logo) {
                    $this->deleteFile($employer_profile->company_logo);
                }
                $folder = 'uploads/employer/' . Str::slug(Auth::user()->name, '_') . '_' . Auth::id() . '/company_logos';
                $logo = $this->storeFile($request->file('company_logo'), $folder);

                $employer_profile->company_logo = $logo;
                $employer_profile->save();
            }


            return response()->json([
                'status'  => true,
                'message' => 'Profile Completed Successfully',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to complete profile: ' . $e->getMessage()
            ], 500);
        }
    }
}
