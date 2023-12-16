<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\{Request, Response, JsonResponse};
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * getProduct
     *
     * @param  mixed $product
     * @return void
     */
    private function getProduct($product)
    {
        return new ProductResource($product);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $product = Product::latest()->get();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Load Data!',
                'data' => $this->getProduct($product)
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'product_name' => ['required', 'string'],
                'product_price' => ['required'],
                'product_code' => ['required', 'string'],
            ]);

            if (!$validate->fails()) {
                $product = Product::create([
                    'product_name' => $request->product_name,
                    'product_price' => $request->product_price,
                    'product_code' => $request->product_code,
                ]);
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Data Saved!',
                    'data' => $this->getProduct($product)
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $validate->getMessageBag()
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Successfully Load Data!',
                'data' => $this->getProduct($product)
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'product_name' => ['required', 'string'],
                'product_price' => ['required'],
                'product_code' => ['required', 'string'],
            ]);

            if (!$validate->fails()) {
                $data = $product->find(request()->segment(3));
                $product = Product::where('product_id', $data->product_id)->update([
                    'product_name' => $request->product_name,
                    'product_price' => $request->product_price,
                    'product_code' => $request->product_code,
                ]);
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Data Updated!',
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $validate->getMessageBag()
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $data = $product->find(request()->segment(3));
            Product::destroy($data->product_id);
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Deleted!',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }
}
