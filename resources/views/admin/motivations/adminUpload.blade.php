<form class="row" action="{{route('admin.uploadMotivationFromAdmin')}}" method="post" enctype="multipart/form-data">
    @csrf
    <p>
        پسوند .ZIP,PDF,ZIP و حجم زیر 100 مگابایت باشد
    </p>
    <input type="hidden" name="id" value="{{$motivation->id}}">
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label for="motivation" class="header-label">آپلود انگیزه نامه</label>
            <input type="file" name="motivation" class="form-control form-control-sm" id="motivation"
                   accept=".zip, .rar, .pdf">
        </div>
    </div>
    <div class="col-12 col-lg-6">
        @if($motivation->url_uploaded_from_admin)
            @foreach($motivation->url_uploaded_from_admin as $key => $item)
                <p class="d-block">
                    دانلود فایل آپلود شده شماره {{$key+1}}: <a  href="{{$item}}">دانلود</a>
                </p>
            @endforeach
        @endif
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
    </div>
</form>
