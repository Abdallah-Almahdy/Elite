<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiTrait
{
    public function successCollection($resource, $data)
    {
        return response()->json([
            'data' =>  $resource::collection($data),
            'message' => config('api.ok_message'),
            'code' => config('api.ok_code')
        ], config('api.ok_code'));
    }

    public function success($data, $message = null)
    {

        return response()->json([
            'data' =>  $data,
            'message' => $message ?? config('api.ok_message'),
            'code' => config('api.ok_code')
        ], config('api.ok_code'));
    }
    public function Apiresponse($data = [], $message = null, $code = 500)
    {

        return response()->json([
            'data' =>  $data ?? [],
            'message' => $message,
            'code' => $code
        ], $code);
    }



    public function notFound($message = null)
    {

        return response()->json([
            'data' => [],
            'message' => $message ?? config('api.not_found_message'),
            'code' => config('api.not_found_code')
        ], config('api.not_found_code'));
    }



    public  function checkExists($model, $attr, $attrValue)
    {
        $exists = $model::where($attr, $attrValue)->exists();

        if (!$exists) {
            throw new HttpResponseException(
                $this->notFound()
            );
        }

        return $exists;
    }
}
