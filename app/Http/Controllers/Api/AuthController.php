<?php
/* app\Http\Controllers\Api\AuthController.php */

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CustomerInfo;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use App\Http\Resources\userDataResource;
use App\Models\userProfile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{


    public function register(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => ['string', 'max:255'],
            'birthDate' => ['date'],
        ]);

        try {

            $user = DB::transaction(function () use ($validated) {

                $user = User::create([
                    'name' => $validated['firstName'] . ' ' . $validated['lastName'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                userProfile::create([
                    'user_id' => $user->id,
                    'first_name' => $validated['firstName'],
                    'last_name' => $validated['lastName'],
                    'phone_number' => $validated['email'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'age' => Carbon::parse($validated['birthDate'])->age ?? null,
                ]);
                return $user;
            });

            DB::commit();

            $auth_token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'auth_token' => $auth_token,
                'message' => 'تم إنشاء الحساب بنجاح',
                'status' => 'success',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
                'status' => 'error',
            ], 500);
        }
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => ['required'],
            'password' => ['required', 'string', 'min:6'],
        ]);


        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'بيانات الدخول غير صحيحة',
                'status' => 'failed',
            ], 422);
        }


        $user = User::where('email', $request->email)->first();


        $auth_token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'user' => $user,
            'auth_token' => $auth_token,
            'message' => 'تم تسجيل الدخول بنجاح',
            'status' => 'success',
        ], 200);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
            'status' => 'success',

        ], 200);
    }


    public function reset_pass(Request $request)
    {
        $user = User::where("email", $request["email"])->first();
        if ($user) {
            $user->password = Hash::make($request["new_password"]);
            $user->save();
            return response()->json([
                'stus' => 'reset',
            ], 200);
        } else {
            return response()->json([
                'stus' => 'failed',
            ], 200);
        }
    }


    public function CompanyData(Request $request)
    {


        return response()->json(
            [
                "data" => [
                    "phone_number_1" => env('PHONENOONE'),
                    "phone_number_2" => env('PHONENOTWO'),
                    "email" => env('EMAIL'),
                ],
                "message" => "data fetched correctrly",
                "stus" => "success",
                "code" => 200,
            ],
            200
        );
    }



    public function speacialRegister(Request $request)
    {


        $request->validate([
            'name' => "required|string|max:255",
            "phonenum" => "required|string|max:20",
            "Country" => "required|string|max:255",
            "city" => "required|string|max:255",
            "street" => "required|string|max:255",
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->phonenum,
            'password' => Hash::make($request->phonenum),
        ]);




        $userProfile = userProfile::create([
            'user_id' => $user->id,
            'first_name' => $request->name,
            'last_name' => "",
            'phone_number' => $request->phonenum,
        ]);

        return response()->json([
            'user' => $user,
            'message' => 'تم إنشاء الحساب بنجاح',
            'status' => 'success',
        ], 200);
    }


    public function getAdmins()
    {
        $admins = User::where('is_admin', true)->get();

        return response()->json([
            'admins' => $admins,
            'message' => 'Admins fetched successfully',
            'status' => 'success',
        ], 200);
    }



    public function getUsersInfo(Request $request)
    {
        $users = User::with(['userProfile', 'userAddresses'])->get();

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'customer_info' => $user->userProfile ? [
                        'phone' => $user->userProfile->phone_number,
                        'phone2' => $user->userProfile->phone_number2,
                        'address1' => trim($user->userAddresses->first()->addressCountry . ' ' . $user->userAddresses->first()->addresscity . ' ' . $user->userAddresses->first()->addressstreet . ' ' . $user->userAddresses->first()->addressbuildingNumber . ' ' . $user->userAddresses->first()->addressfloorNumber . ' ' . $user->userAddresses->first()->addressApartmentNumber),
                        'address2' => trim($user->userAddresses->get(1)->addressCountry2 . ' ' . $user->userAddresses->get(1)->addresscity2 . ' ' . $user->userAddresses->get(1)->addressstreet2 . ' ' . $user->userAddresses->get(1)->addressbuildingNumber2 . ' ' . $user->userAddresses->get(1)->addressfloorNumber2 . ' ' . $user->userAddresses->get(1)->addressApartmentNumber2),
                    ] : null,
                ];
            }),

            'message' => 'Users fetched successfully',
            'status' => 'success',
        ], 200);
    }


}


