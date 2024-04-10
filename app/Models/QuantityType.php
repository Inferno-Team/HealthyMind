<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuantityType extends Model
{
    use HasFactory;

    protected $appends = ['title'];
    protected $fillable = [
        'en_title',
        'ar_title',
    ];
    public function title(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (request()->getLocale() == 'ar')
                    return $this->ar_title;
                return $this->en_title;
            }
        );
    }
    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class, 'qty_type_id');
    }
}
