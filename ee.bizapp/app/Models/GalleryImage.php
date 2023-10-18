<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $connection = 'web_system';
    protected $table = 'gallery_images';
}
