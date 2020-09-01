<?php

namespace Paksuco\Support\Models;

use \Illuminate\Database\Eloquent\Model;

class FAQCategory extends Model
{
    public function getRouteKeyName()
    {
        return "slug";
    }

    public function items()
    {
        return $this->hasMany(FAQItem::class, 'category_id', 'id');
    }
}
