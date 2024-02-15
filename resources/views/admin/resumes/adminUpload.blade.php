<form class="row" action="{{route('admin.uploadResumeFromAdmin')}}"  method="post" enctype="multipart/form-data">
    @csrf
    <p>
        پسوند .ZIP,RAR,PDF و حجم زیر 100 مگابایت باشد
    </p>
    <input type="hidden" name="id" value="{{$resume->id}}">
    <div class="col-12 col-lg-6">
        <div class="form-group float-label">
            <label for="resume" class="header-label">آپلود رزومه</label>
            <input type="file" name="resume" class="form-control form-control-sm" id="resume"
            accept=".zip, .rar, .pdf">
        </div>
    </div>
    <div class="col-12 col-lg-6">
        @if($resume->url_uploaded_from_admin != '')
        دانلود آخرین فایل آپلود شده: <a href="{{$resume->url_uploaded_from_admin}}">دانلود</a>
        @endif
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success btn-sm col-12">ثبت</button>
    </div>
</form>