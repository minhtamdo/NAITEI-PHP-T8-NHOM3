<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        'image_url',
        'author',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Accessor: Lấy URL đầy đủ của ảnh
     */
    public function getImageUrlAttribute()
    {
        // Lấy giá trị từ database
        $imageUrl = $this->attributes['image_url'] ?? null;
        
        if (!$imageUrl) {
            // Nếu không có ảnh, sử dụng no_image.png
            return asset('images/no_image.png');
        }

        // Nếu đã là URL đầy đủ, trả về trực tiếp
        if (str_starts_with($imageUrl, 'http')) {
            return $imageUrl;
        }

        // Kiểm tra file có tồn tại trong public/storage không
        $fullPath = public_path('storage/' . $imageUrl);
        if (file_exists($fullPath)) {
            return asset('storage/' . $imageUrl);
        }

        // Nếu file không tồn tại, fallback về no_image
        return asset('images/no_image.png');
    }

    /**
     * Tạo ảnh no_image nếu chưa có
     */
    private function createNoImageIfNotExists()
    {
        $publicImagePath = public_path('images/no_image.png');
        
        // Tạo thư mục images nếu chưa có
        if (!is_dir(dirname($publicImagePath))) {
            mkdir(dirname($publicImagePath), 0755, true);
        }
        
        // Nếu chưa có file, tạo mới
        if (!file_exists($publicImagePath)) {
            $this->createNoImageFile($publicImagePath);
        }
        
        return asset('images/no_image.png');
    }

    /**
     * Tạo file ảnh no_image
     */
    private function createNoImageFile($path)
    {
        // Tạo ảnh no_image đơn giản
        $width = 400;
        $height = 300;
        $image = imagecreate($width, $height);
        
        // Màu nền xám
        $bgColor = imagecolorallocate($image, 220, 220, 220);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        $borderColor = imagecolorallocate($image, 180, 180, 180);
        
        // Vẽ border
        imagerectangle($image, 0, 0, $width-1, $height-1, $borderColor);
        
        // Text "No Image"
        $text = 'No Image';
        $fontSize = 5;
        $textWidth = strlen($text) * imagefontwidth($fontSize);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        // Vẽ icon camera đơn giản
        $iconSize = 40;
        $iconX = ($width - $iconSize) / 2;
        $iconY = $y - 60;
        
        // Thân camera
        imagefilledrectangle($image, $iconX, $iconY, $iconX + $iconSize, $iconY + 30, $textColor);
        // Lens
        imageellipse($image, $iconX + 20, $iconY + 15, 20, 20, $borderColor);
        
        // Lưu file
        imagepng($image, $path);
        imagedestroy($image);
    }

    /**
     * Xóa ảnh cũ
     */
    private function deleteOldImage()
    {
        $imageUrl = $this->attributes['image_url'] ?? null;
        
        if ($imageUrl && !str_contains($imageUrl, 'no_image.png')) {
            $imagePath = public_path('storage/' . $imageUrl);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            $product->deleteOldImage();
        });
    }
}

