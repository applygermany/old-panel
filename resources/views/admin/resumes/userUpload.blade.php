<div class="table-responsive">
    <p>
        @if($resume->url_uploaded_from_user != '')
        دانلود فایل: <a href="{{$resume->url_uploaded_from_user}}" target="_blank">دانلود</a>
        @else
        فایلی موجود نیست

        @endif
    </p>
</div>