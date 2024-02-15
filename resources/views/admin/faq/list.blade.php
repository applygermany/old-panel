@foreach($faqs as $faq)
    <li class="dd-item" data-id="{{ $faq->id }}">
        <div class="dd-handle">
            <div class="d-flex">
                <p class="w-50px">{{ $faq->id }}</p>
                <p class="w-75">{{ $faq->question }}</p>
                <a href="{{ route('admin.editFaq',['id'=>$faq->id]) }}"
                   class="btn btn-warning btn-sm dd-nodrag">ویرایش</a>
                <a href="{{ route('admin.deleteFaq',['id'=>$faq->id]) }}"
                   class="btn btn-danger btn-sm delete dd-nodrag">حذف</a>
            </div>
        </div>
    </li>
@endforeach