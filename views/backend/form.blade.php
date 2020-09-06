@extends($extends)
@section("content")
<div class="p-8 border-t">
    @include("support-ui::backend.submitresults")
    <form method="POST" action="{{$edit ? route('paksuco.faq.update', $faq) : route('paksuco.faq.store')}}">
        @if($edit)
        @method("PUT")
        @endif
        @csrf
        <div class="w-full items-end">
            <div class="flex mb-4">
                <div class="w-2/3">
                    <h2 class="text-3xl font-semibold mb-3" style="line-height: 1em">
                        {{$edit ? __("Edit FAQ Item") : __("Create a new FAQ Item")}}
                    </h2>
                </div>
                <div class="w-1/3 text-right">
                    <button type="submit" name="publish" value="0"
                        class="border px-4 py-2 rounded shadow bg-blue-400
                        text-white border-blue-500">Save</button>

                    @if($edit == false || $faq->published == false)
                        <button type="submit" name="publish" value="1"
                            class="border px-4 py-2 rounded shadow bg-green-400
                            text-white border-green-500">Save & Publish</button>
                    @else
                        <button type="submit" name="publish" value="-1"
                            class="border px-4 py-2 rounded shadow bg-red-700
                            text-white border-red-800">Un-publish</button>
                    @endif
                    <button type="button" class="border px-4 py-2 rounded shadow"
                        onclick="window.location = '{{route("paksuco.faq.index")}}';">Go Back</button>
                </div>
            </div>
            <input type="text" name="title" placeholder="@lang('Enter Title')"
                class="border rounded-sm shadow-inner w-full text-2xl p-2 px-4 mb-3" value="{{$edit ? $faq->question : ''}}">
            <select name="category_id" class="border rounded-sm shadow-inner w-full text-xl p-2 px-4 mb-3">
                <option value="">@lang("- Select a Category -")</option>
                @foreach ($categories as $category)
                    <option value="{{$category->id}}" @if($edit && $faq->category_id == $category->id) selected @endif >{{$category->title}}</option>
                @endforeach
            </select>
            <textarea id="mytextarea" name="content" class="shadow">{{$edit ? $faq->answer : ''}}</textarea>
        </div>
    </form>
</div>
@endsection

@push("head-scripts")
<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea',
        height: 500,
        language: "{{config('app.locale')}}",
        plugins: 'preview powerpaste searchreplace autolink \
            directionality advcode visualblocks visualchars fullscreen image \
            link media template codesample table charmap hr faqbreak nonbreaking \
            anchor toc insertdatetime advlist lists textcolor wordcount \
            tinymcespellchecker a11ychecker imagetools mediaembed linkchecker \
            contextmenu colorpicker textpattern code',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | \
            link | alignleft aligncenter alignright alignjustify  | numlist bullist \
            outdent indent  | removeformat | code',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        images_upload_handler: function (blobInfo, success, failure) {
           var xhr, formData;
           xhr = new XMLHttpRequest();
           xhr.withCredentials = false;
           xhr.open('POST', '{{route("paksuco.faq.upload")}}');
           var token = '{{ csrf_token() }}';
           xhr.setRequestHeader("X-CSRF-Token", token);
           xhr.onload = function() {
               var json;
               if (xhr.status != 200) {
                   failure('HTTP Error: ' + xhr.status);
                   return;
               }
               json = JSON.parse(xhr.responseText);

               if (!json || typeof json.location != 'string') {
                   failure('Invalid JSON: ' + xhr.responseText);
                   return;
               }
               success(json.location);
           };
           formData = new FormData();
           formData.append('file', blobInfo.blob(), blobInfo.filename());
           xhr.send(formData);
       }
    });
</script>
@endpush