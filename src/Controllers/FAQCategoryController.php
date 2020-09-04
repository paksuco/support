<?php

namespace Paksuco\Support\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Paksuco\Support\Models\FAQCategory;
use Paksuco\Support\Models\FAQItem;

class FAQCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("support-ui::backend.categories", [
            "extends" => config("support-ui.backend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // not implemented on separate page
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "title" => "required|filled",
            "slug" => "unique:faq_categories,slug,NULL,id",
            "description" => "present"
        ]);

        $category = new FAQCategory();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = 0;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();

        return redirect()->route("paksuco.faqcategory.index")
            ->with("success", "FAQ Category successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faq = FAQItem::findOrFail($id);

        return view("support-ui::frontend.show", [
            "faq" => $faq,
            "extends" => config("support-ui.frontend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faq = FAQItem::findOrFail($id);

        return view("support-ui::backend.form", [
            "extends" => config("support-ui.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "faq" => $faq,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FAQCategory $category)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "title" => "required|filled",
            "slug" => "unique:faq_categuries,faq_slug,{$category->id},id",
            "content" => "required|filled",
            "category_id" => "required|filled",
            "publish" => "required|filled",
        ]);

        $category->faq_title = $request->title;
        $category->faq_content = $request->content;
        $category->faq_slug = Str::slug($request->title);
        $category->faq_excerpt = Str::limit($request->content, 200, '...');
        if ($request->publish != "0") {
            $category->published = $request->publish == "1" ? true : false;
        }
        $category->save();

        return redirect()->route("paksuco.faqcategory.index")->with("success", "Category modifications saved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FAQCategory $category)
    {
        $category->delete();

        return redirect()
            ->route("paksuco.faqcategory.index")
            ->with("success", "Category has been successfully deleted.");
    }
}
