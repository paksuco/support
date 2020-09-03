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
        // not implemented
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|filled",
            "parent" => "present|nullable",
        ]);

        $category = new FAQCategory();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = 0;
        $category->parent_id = $request->parent ?? null;
        $category->save();

        return redirect()->route("paksuco.faqcategory.index")
            ->with("status", "FAQ Category successfully created.");
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
    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required|filled",
            "content" => "required|filled",
            "category_id" => "required|filled",
            "publish" => "required|filled",
        ]);

        $faq = FAQItem::findOrFail($id);
        $faq->faq_title = $request->title;
        $faq->faq_content = $request->content;
        $faq->faq_slug = Str::slug($request->title);
        $faq->faq_excerpt = Str::limit($request->content, 200, '...');
        if ($request->publish != "0") {
            $faq->published = $request->publish == "1" ? true : false;
        }
        $faq->save();

        return redirect()->route("paksuco.faqs.index")->with("status", "success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = FAQItem::find($id);
        if ($faq instanceof FAQItem) {
            $faq->delete();
        }
        return redirect()->route("paksuco.faqs.index")->with("sucess", "Faq has been successfully deleted.");
    }
}
