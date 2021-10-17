<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * Formats the image path attribute
     * @param $value
     * @return string
     */
    public function getPathAttribute($value): string
    {
        return "storage/" . $value;
    }
}
