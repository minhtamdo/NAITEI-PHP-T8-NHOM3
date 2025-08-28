<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('category')->get(); // Include category for display
            return response()->json(['success' => true, 'data' => $products, 'message' => 'Get products successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve products', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
                'author' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Generate a unique filename with .png extension
                $filename = 'product_' . time() . '.png';
                $path = Storage::disk('public')->putFileAs('products', $file, $filename);
                
                $validated['image_url'] = $path; // Store the relative path (e.g., products/product_123456789.png)
            }

            $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'data' => $product->load('category'),
                'message' => 'Create product success'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Get product successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:products,name,' . $id,
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'stock' => 'sometimes|required|integer|min:0',
                'author' => 'sometimes|required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if it exists (except no_image.png)
                if ($product->image_url && !str_contains($product->image_url, 'no_image.png')) {
                    Storage::disk('public')->delete($product->image_url);
                }
                
                $file = $request->file('image');
                
                // Generate a unique filename with .png extension
                $filename = 'product_' . time() . '.png';
                $path = Storage::disk('public')->putFileAs('products', $file, $filename);
                
                $validated['image_url'] = $path; // Store the relative path
            }

            $product->update($validated);

            return response()->json([
                'success' => true,
                'data' => $product->fresh()->load('category'),
                'message' => 'Update product successfully'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            if ($product->orderItems()->exists() || $product->reviews()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product because it has associated orders or reviews'
                ], 400);
            }

            // Delete image file if it exists (but keep no_image.png)
            if ($product->image_url && !str_contains($product->image_url, 'no_image.png')) {
                Storage::disk('public')->delete($product->image_url);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delete product successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addReview(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'author' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $reviews = session("reviews.{$product->id}", []);
        $reviews[] = $validated;

        session(["reviews.{$product->id}" => $reviews]);

        return redirect()
            ->route('products.show', $product->id)
            ->with('success', 'Đánh giá đã được gửi!');
    }

    public function viewall(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('keyword')) {
            $search = $request->input('keyword');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'price-low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $products = $query->paginate(20)->appends($request->query());
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function detail($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Chuyển đổi và lưu ảnh dưới dạng PNG
     */
    private function saveImageAsPng($file, $destinationPath)
    {
        // Lấy thông tin ảnh
        $imageInfo = getimagesize($file->getPathname());
        $mimeType = $imageInfo['mime'];
        
        // Tạo resource ảnh từ file upload
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case 'image/png':
                $image = imagecreatefrompng($file->getPathname());
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file->getPathname());
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file->getPathname());
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }
        
        // Đảm bảo nền trong suốt cho PNG
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        // Lưu dưới dạng PNG
        $result = imagepng($image, $destinationPath);
        
        // Giải phóng bộ nhớ
        imagedestroy($image);
        
        if (!$result) {
            throw new \Exception('Failed to save image as PNG');
        }
    }
}

