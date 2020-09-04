@extends($extends)
@section("content")
<div class="flex min-h-screen border-t">
    <div class="w-1/4 p-6">
        <h2 class="text-xl leading-6 font-semibold">Add New FAQ Category</h2>
        <div class="-mx-3 mt-3">
            <form action="{{route('paksuco.faqcategory.store')}}" method="POST" id="new_category_form">
                @csrf
                @livewire("paksuco-settings::textinput", [
                "title" => "Category Name",
                "key" => "title",
                "model" => "title",
                "value" => ""
                ], key("title"))
                @livewire("paksuco-settings::textarea", [
                "title" => "Description",
                "key" => "description",
                "model" => "description",
                "value" => "",
                "props" => [
                "rows" => 5
                ]
                ], key("description"))
                @livewire("paksuco-settings::selectinput", [
                "title" => "Parent Category",
                "key" => "parent_id",
                "model" => "parent_id",
                "value" => "",
                "props" => ["values" => \Paksuco\Support\Models\FAQCategory::select(["id",
                "title"])->get()->pluck("title", "id")]
                ], key("parent"))
                <div class="text-right">
                    <button type="submit"
                            class="mx-3 px-3 py-1 rounded shadow border bg-blue-500 border-blue-600 text-white">
                        Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    <div class="w-3/4 bg-white shadow-lg p-6">
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
            console.log(data);
        });
    };
</script>
@endsection