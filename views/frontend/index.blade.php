@extends($extends)
@section('content')
<!-- component -->
<div class="bg-gray-100 py-10">
    <div class="mx-auto container">
        <div class="p-2 bg-gray-200 rounded min-h-screen">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 p-4 text-sm">
                    <div class="text-3xl">Frequently asked <span class="font-medium">Questions</span></div>
                    <div class="my-2">Wondering how our service works ?</div>
                    <div class="mb-2">Confused about how we can improve your business ?</div>
                    <div class="text-xs text-gray-600">Dive into our FAQ for more details</div>
                </div>
                <div class="md:w-2/3">
                    <div class="p-4">
                        @forelse(\Paksuco\Support\Models\FAQItem::all() as $faqitem)
                        <div class="mb-2">
                            <div class="font-medium rounded-sm text-lg px-2 py-3 flex text-gray-800 flex-row-reverse mt-2 cursor-pointer text-black bg-white hover:bg-white">
                                <div class="flex-auto">{{$faqitem->question}}</div>
                                <div class="pl-3 pr-4">
                                    <i class="fa fa-chevron-up text-sm"></i>
                                </div>
                            </div>
                            <div class="px-8 pb-8 pt-4 text-justify text-left text-gray-800 bg-white" style="">
                                {!! $faqitem->answer !!}

                                Did it help you? Like dislike
                            </div>
                        </div>
                        @empty
                        <div class="mb-2">
                            <div class="px-8 pb-8 pt-4 text-justify text-left text-gray-800" style="">
                                There is nothing to show here. Perhaps the site admin forgot to add some questions?
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection