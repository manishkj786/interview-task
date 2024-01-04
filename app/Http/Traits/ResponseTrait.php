<?php
namespace App\Http\Traits;

trait ResponseTrait{
    public function respondWithSuccess($data=[], $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    public function respondWithError($message='',$data=[], $statusCode = 500)
    {
        return response()->json($data, $statusCode);
    }
}
