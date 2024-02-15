<div class="table-responsive">
    <h5>ادیت از طرف ادمین</h5>
    <div class="px-5">
        {!!$motivation->admin_comment ? $motivation->admin_comment:"__"!!}
  </div>
   <h5>فایل های پیوست ادمین</h5>
    <div class="px-5">
      @foreach ( $motivation->admin_attachment as $key => $item)
      <a class="d-block" href="{{$item}}">دانلود فایل {{$key+1}}</a>
      @endforeach
     
    </div>
    <h5 class="mt-3">ادیت از طرف کاربر</h5>
  <div class="px-5">
        {!!$motivation->user_comment ? $motivation->user_comment:"__"!!}
  </div>
  <h5 class="mt-3">فایل های پیوست کاربر</h5>
  <div class="px-5">
      @foreach($motivation->url_uploaded_from_user as $key => $item)
      <a class="d-block" href={{$item}}>دانلود فایل {{$key+1}}</a>
      @endforeach
  </div>
</div>

