<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $connection = 'web_system';
    protected $table = 'galleries';

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id', 'id');
    }

    public function galleryable()
    {
        return $this->morphTo();
    }
}
