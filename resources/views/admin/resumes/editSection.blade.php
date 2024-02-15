<div class="table-responsive">
    <h5>ادیت از طرف ادمین</h5>
    <div class="px-5">
        {!! $resume->admin_comment ? $resume->admin_comment: "__" !!}
    </div>
    <h5>فایل پیوست ادمین</h5>
    <div class="px-5">
      @foreach ((is_array($resume->admin_attachment) ? $resume->admin_attachment : []) as $key => $item)
      <a class="d-block" href="{{$item}}">دانلود فایل {{$key+1}}</a>
      @endforeach
     
    </div>
    <h5 class="mt-3">ادیت از طرف کاربر</h5>
    <div class="px-5">
        {!! $resume->user_comment ? $resume->user_comment: "__" !!}
    </div>
    <h5 class="mt-3">فایل پیوست کاربر</h5>
    <div class="px-5">
        {!! $resume->url_uploaded_from_user ? '<a href="'.$resume->url_uploaded_from_user.'">دانلود</a>': "__" !!}
    </div>
</div>

