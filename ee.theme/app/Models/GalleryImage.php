<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GalleryImage
 *
 * @property int $id
 * @property int $gallery_id
 * @property string $path
 * @property string|null $alt
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereGalleryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class GalleryImage extends Model
{
    protected $table = 'gallery_images';
    protected $guarded = [];

}
