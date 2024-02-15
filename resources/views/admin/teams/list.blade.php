@foreach($teams as $team)
    <li class="dd-item" data-id="{{ $team->id }}">
        <div class="dd-handle">
            <div class="d-flex">
                <p class="w-50px">{{ $team->id }}</p>
                <p class="w-50">{{ $team->name }}</p>
                <p class="w-50">{{ $team->field }}</p>
{{--                <p class="w-50">{{ $team->position }}</p>--}}
                <button class="btn btn-warning btn-sm edit dd-nodrag"
                        data-url="{{ route('admin.editTeam',['id'=>$team->id]) }}">ویرایش</button>
                <a href="{{ route('admin.deleteTeam',['id'=>$team->id]) }}" class="btn btn-danger btn-sm delete dd-nodrag">حذف</a>
            </div>
        </div>
    </li>
@endforeach
