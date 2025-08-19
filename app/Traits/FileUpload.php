<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUpload{
    public function handleImageDelete($image)
    {
        if($image && Storage::disk('public')->exists($image)){
            Storage::disk('public')->delete($image);
        }
    }
}
