@csrf
<input type="hidden" name="id" value="{{$telSupport->id}}">
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label for="date" class="header-label"> تاریخ</label>
            <input type="text" name="date"
                   class="form-control form-control-sm date" id="date"
                   placeholder=" تاریخ" disabled>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label for="fromTime" class="header-label">ار ساعت</label>
            <input type="text" name="fromTime"
                   class="form-control form-control-sm" id="fromTime"
                   placeholder="ار ساعت" disabled>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label for="toTime" class="header-label">تا ساعت</label>
            <input type="text" name="toTime"
                   class="form-control form-control-sm" id="toTime"
                   placeholder="تا ساعت" disabled>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group float-label">
            <label class="header-label" for="user">کاربر</label>
            <select name="user" id="user"
                    class="form-control form-select-sm"
                    data-control="select2">
                <option value="">انتخاب کنید</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                            data-value="{{$user}}">{{ $user->firstname }} {{ $user->lastname }}
                        - 0{{$user->mobile}} - {{$user->email}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="form-group float-label">
            <label for="title" class="header-label">عنوان</label>
            <input type="text" name="title"
                   class="form-control form-control-sm" id="title"
                   placeholder="عنوان">
        </div>
    </div>
</div>