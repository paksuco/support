<?php

namespace Paksuco\Support\Models;

use \Illuminate\Database\Eloquent\Model;

class FAQCategory extends Model
{
    protected $table = "faq_categories";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function items()
    {
        return $this->hasMany(FAQItem::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(FAQCategory::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(FAQCategory::class, "parent_id", "id");
    }
}
