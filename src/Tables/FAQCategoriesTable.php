<?php

namespace Paksuco\Support\Tables;

use Paksuco\Support\Models\FAQCategory;

class FAQCategoriesTable extends \Paksuco\Table\Contracts\TableSettings
{
    public $model = FAQCategory::class;
    public $relations = ["parent"];
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
            "name" => "title",
            "type" => "field",
            "format" => "string",
            "class" => "w-full bg-gray-50",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => 'description',
            "type" => "field",
            "format" => "string",
            "sortable" => false,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "parent",
            "type" => "callback",
            "format" => FAQCategoriesTable::class . "::getParentTitle",
            "sortable" => true,
            "queryable" => true,
            "filterable" => true,
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
            "format" => FAQCategoriesTable::class . "::getActions",
            "sortable" => false,
            "queryable" => false,
            "filterable" => false,
        ],
    ];

    public static function getParentTitle($category)
    {
        if ($category->parent instanceof FAQCategory) {
            return $category->parent->title;
        }

        return __("(No Parent)");
    }

    public static function getActions($category)
    {
        return "<button class='mr-1 rounded px-3 py-1 bg-blue-700 text-white shadow'
                wire:click='\$emit(\"editCategory\"," . $category->id . ")'>" . __("Edit") .
        "</button>" .
        "<button class='rounded px-3 py-1 bg-red-700 text-white shadow'
                wire:click='\$emit(\"deleteCategory\"," . $category->id . ")'>" . __("Delete") .
            "</button>";
    }
}
