<?php

namespace Paksuco\Support\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Paksuco\Support\Models\FAQCategory;
use Paksuco\Support\Models\FAQItem;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("support-ui::backend.index", [
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
        return view("support-ui::backend.form", [
            "extends" => config("support-ui.backend.template_to_extend", "layouts.app"),
            "edit" => false,
            "categories" => FAQCategory::all()
        ]);
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
            "title" => "required|filled"
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "slug" => "unique:faq_items,slug,NULL,id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $faq = new FAQItem();
        $faq->category_id = $category_id ?? null;
        $faq->question = $request->title;
        $faq->slug = Str::slug($request->title);
        $faq->answer = $request->content;
        $faq->published = $request->publish == "1" ? true : false;
        $faq->order = 0;
        $faq->likes = 0;
        $faq->dislikes = 0;
        $faq->visits = 0;
        $faq->save();

        return redirect()->route("paksuco.faq.index")->with("success", "FAQ Item has been successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FAQItem $faq)
    {
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
    public function edit(FAQItem $faq)
    {
        return view("support-ui::backend.form", [
            "extends" => config("support-ui.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "faq" => $faq,
            "categories" => FAQCategory::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FAQItem $faq)
    {
        $request->validate([
            "title" => "required|filled"
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "slug" => "unique:faq_items,slug,".$faq->id.",id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $faq->question = $request->title;
        $faq->answer = $request->content;
        $faq->slug = Str::slug($request->title);
        if ($request->publish != "0") {
            $faq->published = $request->publish == "1" ? true : false;
        }
        $faq->save();

        return redirect()->route("paksuco.faq.index")->with("success", "FAQ Item successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FAQItem $faq)
    {
        $faq->delete();
        return redirect()->route("paksuco.faq.index")->with("success", "FAQ Item has been successfully deleted");
    }

    public function upload(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->all(),
            ], 400);
        }

        $image = $request->file('file');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $path = config('support-ui::backend.image_upload_folder', public_path('uploads'));
        $image->move($path, $new_name);

        $url = str_replace(public_path(), '', $path . "/" . $new_name);

        return response()->json([
            'location' => $url
        ]);
    }
}
