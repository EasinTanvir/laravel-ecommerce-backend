<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'quantity'  ];




      public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders()
{
    return $this->belongsToMany(Order::class)
        ->withPivot('quantity', 'price')
        ->withTimestamps();
}


    }