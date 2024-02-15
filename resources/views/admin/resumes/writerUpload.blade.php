<div class="table-responsive">
    <p>
    @if($resume->url_uploaded_from_writer)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>فایل</th>
                    <th>وضعیت</th>
                    <th>عملبات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resume->url_uploaded_from_writer as $key => $item)
                    <tr>
                        <td>
                            <a class="d-block" target="_blank" href={{$item}}> فایل {{$key+1}}</a>
                        </td>
                        <td>
                            @if($resume->admin_accepted_filename === $item)
                                <span class="badge badge-success">تایید ادمین</span>
                            @else
                                <span class="badge badge-warning">----</span>
                            @endif
                        </td>
                        <td>
                            @if($resume->admin_accepted_filename !== $item)
                                <a href="javascript:{}"
                                   data-url="{{ route('admin.acceptResumeFile',['id'=>$resume->id, 'file'=>$key]) }}"
                                   class="acceptFileModal btn btn-info btn-sm">تایید</a> |
                                <a href="javascript:{}"
                                   data-url="{{ route('admin.deleteResumeFile',['id'=>$resume->id, 'file'=>$key]) }}"
                                   class="deleteFileModal btn btn-danger btn-sm">حذف</a> |
                            @endif
                            <a class="btn btn-primary btn-sm" target="_blank" href={{$item}}> دانلود</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        فایلی موجود نیست
        @endif
        </p>
</div>
