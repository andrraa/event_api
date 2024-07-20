<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MasterEventResource;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\UserRoleResource;
use App\Models\Category;
use App\Models\MasterEvent;
use App\Models\Province;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\JsonResponse;

class UtilityController extends Controller
{
    public function getListCategory(): JsonResponse
    {
        try {
            $category = Category::query()->get();

            if ($category->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'CATEGORY_NOT_FOUND',
                ], 404);
            }

            $categoryCollection = CategoryResource::collection($category);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_GET_LIST_CATEGORY',
                'data' => $categoryCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getListMasterEvent(): JsonResponse
    {
        try {
            $masterEvent = MasterEvent::query()->get();

            if ($masterEvent->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'MASTER_EVENT_NOT_FOUND',
                ], 404);
            }

            $masterEventCollection = MasterEventResource::collection($masterEvent);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_GET_LIST_MASTER_EVENT',
                'data' => $masterEventCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getListProvince(): JsonResponse
    {
        try {
            $province = Province::query()->get();

            if ($province->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'PROVINCE_NOT_FOUND',
                ], 404);
            }

            $provinceCollection = ProvinceResource::collection($province);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_GET_LIST_PROVINCE',
                'data' => $provinceCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getListUserRole(): JsonResponse
    {
        try {
            $userRole = UserRole::query()->get();

            if ($userRole->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'USER_ROLE_NOT_FOUND'
                ], 404);
            }

            $userRoleCollection = UserRoleResource::collection($userRole);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_GET_LIST_USER_ROLE',
                'data' => $userRoleCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
