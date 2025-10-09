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

                CustomerInfo::create([
                    'user_id'   => $user->id,
                    'firstName' => $validated['firstName'],
                    'lastName'  => $validated['lastName'],
                    'email'     => $validated['email'],
                    'age' => isset($validated['birthDate'])
                        ? Carbon::parse($validated['birthDate'])->age
                        : null,
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



    public function update_user_data(Request $request)
    {

        $user = $request->user();

        $user_info = CustomerInfo::where("user_id",  $user->id)->frist();

        $user_info->update($request->except('profileImage'));

        if ($request->hasFile('profileImage'))
        {
            if (!empty($user_info->profileImage))
            {
                $oldPath = public_path('uploads/profile_photo/' . $user_info->profileImage);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $newFileName = 'user_' . $user->id . '.' . $request->profileImage->getClientOriginalExtension();
            $request->profileImage->move(public_path('uploads/profile_photo'), $newFileName);

            $user_info->profileImage = $newFileName;
        }
        $user_info->save();

        return new userDataResource($user_info);
    }




    public function get_user_data(Request $request)
    {

        $user = $request->user();

        $user_info = CustomerInfo::where("user_id",  $user->id)->first();

        return new userDataResource($user_info);
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




    public function update_user_photo() {}
}
