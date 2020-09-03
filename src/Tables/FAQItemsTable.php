<?php

namespace Paksuco\Support\Tables;

use Paksuco\Support\Models\FAQCategory;
use Paksuco\Support\Models\FAQItem;

class FAQItemsTable extends \Paksuco\Table\Contracts\TableSettings
{
    public $model = FAQItem::class;
    public $relations = ["category"];
    public $queryable = true;
    public $sortable = true;
    public $pageable = true;
    public $perPages = [10, 25, 50, 100];
    public $perPage = 10;

    public $fields = [
        [
            "name" => "id",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "category",
            "type" => "callback",
            "format" => FAQItemTable::class . "::getCategoryTitle",
            "sortable" => true,
            "queryable" => true,
            "filterable" => true,
        ],
        [
            "name" => 'title',
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "excerpt",
            "type" => "field",
            "format" => "string",
            "class" => "w-full bg-gray-50",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "likes",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "dislikes",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "views",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "published",
            "type" => "field",
            "format" => "checkbox",
            "sortable" => true,
            "queryable" => false,
            "filterable" => true,
        ],
        [
            "name" => "updated_at",
            "type" => "field",
            "format" => "datetime",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "created_at",
            "type" => "field",
            "format" => "datetime",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "actions",
            "type" => "callback",
            "format" => FAQItemsTable::class . "::getActions",
            "sortable" => false,
            "queryable" => false,
            "filterable" => false,
        ],
    ];

    public static function getCategoryTitle($item)
    {
        if ($item->category instanceof FAQCategory) {
            return $item->category->title;
        }

        return __("(No Category)");
    }

    public static function getActions($item)
    {
        return "<button class='mr-1 rounded px-3 py-1 bg-blue-700 text-white shadow'
                wire:click='\$emit(\"editCategory\"," . $item->id . ")'>" . __("Edit") .
        "</button>" .
        "<button class='rounded px-3 py-1 bg-red-700 text-white shadow'
                wire:click='\$emit(\"deleteCategory\"," . $item->id . ")'>" . __("Delete") .
            "</button>";
    }
}
