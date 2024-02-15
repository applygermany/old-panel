<div class="table-responsive">
    <p>
        @if($motivation->url_uploaded_from_user)
            @foreach($motivation->url_uploaded_from_user as $key => $item)
                <a class="d-block" href={{$item}}>دانلود فایل {{$key+1}}</a>
            @endforeach
        @else
            فایلی موجود نیست
        @endif
    </p>
</div>
