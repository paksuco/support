@extends($extends)
@section("content")
<div class="flex min-h-screen border-t">
    <div class="w-60 flex-shrink-0 p-2 pt-6">
        <form action="{{route('paksuco.faqcategory.store')}}"
            method="POST"
            id="new_category_form"
            x-data="{}">
            <h2 class="mx-3 mb-3 text-xl leading-6 font-semibold add_form_visible">Add New FAQ Category</h2>
            <h2 class="mx-3 mb-3 text-xl leading-6 font-semibold edit_form_visible hidden">Edit FAQ Category</h2>

            <input type="hidden" name="_method" value="POST" id="category_submit_type">
            <input type="hidden" name="id" value="" id="category_submit_id">
            @csrf
            @livewire("paksuco-settings::textinput", [
            "title" => "Category Name",
            "key" => "title",
            "model" => "",
            "value" => ""
            ], key("title"))
            @livewire("paksuco-settings::textarea", [
            "title" => "Description",
            "key" => "description",
            "model" => "",
            "value" => "",
            "props" => [
            "rows" => 5
            ]
            ], key("description"))
            @livewire("paksuco-settings::selectinput", [
            "title" => "Parent Category",
            "key" => "parent_id",
            "model" => "",
            "value" => "",
            "props" => ["values" => \Paksuco\Support\Models\FAQCategory::select(["id",
            "title"])->get()->pluck("title", "id")]
            ], key("parent"))
            <div class="text-right">
                <button type="button"
                    class="edit_form_visible mr-1 px-3 py-1 rounded shadow border hidden bg-white border-gray-100 text-gray-800"
                    onclick="resetForm(this)">Vazgeç</button>
                <button type="submit"
                    class="px-3 mr-3 py-1 rounded shadow border bg-blue-500 border-blue-600 text-white">Kaydet</button>
            </div>
        </form>
    </div>
    <div class="flex-1 bg-white shadow-lg p-6">
        <div class="w-full items-end">
            @include("support-ui::backend.submitresults")
            <div class="flex">
                <div class="w-2/3">
                    <h2 class="text-3xl font-semibold mb-3" style="line-height: 1em">@lang("FAQ Categories")</h2>
                </div>
                <div class="w-1/3 text-right">
                    &nbsp;
                </div>
            </div>
            <p class="text-gray-600 font-light leading-5 mb-4 text-sm">Lorem ipsum dolor sit amet, consectetur
                adipiscing elit. Proin interdum urna sit amet lorem iaculis, aliquet suscipit sapien venenatis.
                Sed congue vitae velit vitae varius. Mauris egestas consequat mauris sit amet mollis. Proin porta
                tortor in urna tincidunt vehicula. Integer urna nulla, porttitor ac imperdiet eu, mattis vel lacus.
                Sed et porttitor ex. Morbi pellentesque massa a velit gravida, vitae rutrum tortor consequat. Donec
                interdum lacus ut sem consectetur elementum. Proin pellentesque maximus sem sed rhoncus. Cras eget
                neque a nisi posuere mollis vitae vitae magna. Praesent non volutpat sem, a maximus libero.
            </p>
            <div id="category_table">
                @include("support-ui::backend.categories_table")
            </div>
        </div>
    </div>
</div>
<script>
    var editCategory = function(row) {
        fetch("/api/faqcategory/" + row, {
            method: 'GET',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
            'Accept': 'application/json'
            },
        })
        .then( response => response.json())
        .then((data) => {
            var form = document.querySelector("#new_category_form");
            form.action = "{{route('paksuco.faqcategory.index')}}/"+data.id;
            form.querySelector("[name='_method']").value = "PUT";
            form.querySelector("[name='id']").value = data.id;
            form.querySelector("[x-ref='title']").value = data.title;
            form.querySelector("[x-ref='description']").innerText = data.description;
            form.querySelector("[x-ref='parent_id']").value = data.parent_id;
            form.querySelectorAll(".edit_form_visible").forEach( i => i.classList.remove("hidden"));
            form.querySelectorAll(".add_form_visible").forEach( i => i.classList.add("hidden"));
        });
    };
    var resetForm = function(){
        var form = document.querySelector("#new_category_form");
            form.querySelector("[name='_method']").value = "POST";
            form.action = "{{route('paksuco.faqcategory.store')}}";
            form.querySelector("[name='id']").value = "";
            form.querySelector("[x-ref='title']").value = "";
            form.querySelector("[x-ref='description']").innerText = "";
            form.querySelector("[x-ref='parent_id']").value = "";
            form.querySelectorAll(".edit_form_visible").forEach( i => i.classList.add("hidden"));
            form.querySelectorAll(".add_form_visible").forEach( i => i.classList.remove("hidden"));
    };
</script>
@endsection