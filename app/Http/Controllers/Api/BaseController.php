<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function sentPaginationResponse($data, $paginate = 0, $message = null,$status = 200)
    {
         return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'paginate' => $paginate,
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ]
        ]);
    }

    public function sentResponse($data, $message,$status=200){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function sentError($message, $status = 400)
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function sentServerError($title,$message)
    {
        Log::error($title, ['error' => $message]);

         return response()->json([
                'status' => 500,
                'message' => 'Server Error',
            ],500);
    }
}
