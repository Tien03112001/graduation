<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Gallery
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $published
 * @property string $galleryable_type
 * @property string $galleryable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $galleryable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereGalleryableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereGalleryableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Gallery extends Model
{
    protected $table = 'galleries';
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id', 'id');
    }

    public function galleryable()
    {
        return $this->morphTo();
    }
}
