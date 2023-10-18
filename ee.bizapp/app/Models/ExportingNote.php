<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportingNote extends Model
{

    public function details()
    {
        return $this->hasMany(ExportingNoteDetail::class, 'note_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }
}
