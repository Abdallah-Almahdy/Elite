<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddressResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\userProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request)
    {

        $user = $request->user();
        return new UserResource($user->load('userProfile', 'userAddresses'));
    }

    public function updateUserProfile(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone_number' => 'string|max:20|nullable',
            'phone_number2' => 'string|max:20|nullable',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'gender' => 'in:male,female|nullable',
            'age' => 'integer|nullable',
        ]);

        $user = $request->user();
        $profile = userProfile::where('user_id', $user->id)->first();

        if ($request->hasFile('profile_picture') ) {

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $path;
        } elseif ($profile) {
            $validatedData['profile_picture'] = $profile->profile_picture;
        }

        $profile =  userProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        return new UserProfileResource($profile);
    }


    public function getUserAddresses(Request $request)
    {
        $user = $request->user();
        $addresses = $user->userAddresses;

        return UserAddressResource::collection($addresses);
    }

    public function createUserAddresses(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'address_country' => 'string|max:255',
            'address_city' => 'string|max:255',
            'address_street' => 'string|max:255',
            'address_building' => 'string|max:255|nullable',
            'address_floor' => 'string|max:255|nullable',
            'address_apartment' => 'string|max:255|nullable',
            'is_default' => 'boolean',
        ]);

        $userAddress = $user->userAddresses()->create($validatedData);

        return new UserAddressResource($userAddress);
    }

    public function updateUserAddresses(Request $request, $addressId)
    {
        $user = $request->user();
        $address = $user->userAddresses()->findOrFail($addressId);

        $validatedData = $request->validate([
            'address_country' => 'string|max:255',
            'address_city' => 'string|max:255',
            'address_street' => 'string|max:255',
            'address_building' => 'string|max:255|nullable',
            'address_floor' => 'string|max:255|nullable',
            'address_apartment' => 'string|max:255|nullable',
            'is_default' => 'boolean',
        ]);

        $address->update($validatedData);

        return new UserAddressResource($address);
    }

    public function deleteUserAddresses(Request $request, $addressId)
    {
        $user = $request->user();
        $address = $user->userAddresses()->findOrFail($addressId);

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully.']);
    }
}
