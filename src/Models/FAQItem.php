<?php

namespace Paksuco\Support\Models;

use \Illuminate\Database\Eloquent\Model;

class FAQItem extends Model
{

    protected $table = "faq_items";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function category()
    {
        return $this->belongsTo(FAQCategory::class, "category_id", "id");
    }
}
