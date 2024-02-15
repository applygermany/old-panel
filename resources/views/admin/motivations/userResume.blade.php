<div class="table-responsive">
    <p>
        @if(is_array($motivation->url_uploaded_from_user) && sizeof($motivation->url_uploaded_from_user) > 0)
            @php
                $item = $motivation->url_uploaded_from_user[0];
            @endphp
                <a target="_blank" class="d-block" href={{$item}}>دانلود رزومه</a>


        @else
            فایلی موجود نیست

        @endif


    </p>
</div>
