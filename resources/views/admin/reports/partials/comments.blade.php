<div class="table-responsive">
    <table class="table">
        <thead class="table-light">
        <tr class="text-center">
            <th>مشاور</th>
            <th>تاریخ</th>
            <th>متن</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr class="text-center">
               <td>{{$comment->owner->firstname}} {{$comment->owner->lastname}}</td>
               <td>{{$comment->jalali_created}}</td>
               <td>{{$comment->text}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
