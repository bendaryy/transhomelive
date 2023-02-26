@php
   $allCompanies = DB::table('companies2') ->select('name', 'tax_id')
        ->get();
@endphp

<h5 style="margin-bottom: 50px;color:red;border-bottom: 1px solid red;width:50%">ملحوظة : يجب ان يكون الفارق بين
    التاريخين فى البحث 31 يوم على الأكثر</h5>
<div style="margin: 10px">
    <form action="{{ route('allinvoices') }}" method="get">
        <div class="form-row row text-center">
            <div class="form-group col-md-2">
                <label for="inputEmail4">التاريخ من</label>
                <input required type="date" value="{{ request('datefrom') ? request('datefrom') : '' }}" name="datefrom"
                    class="form-control" id="inputEmail4">
            </div>
            <div class="form-group col-md-2">
                <label for="inputPassword4">التاريخ الى</label>
                <input required type="date" value="{{ request('dateto') ? request('dateto') : '' }}" name="dateto"
                    class="form-control" id="inputPassword4">
            </div>
            <div class="form-group col-md-4">
                <label for="inputPassword4">إسم العميل</label>
                <select class="form-control single-select" name="receiverId">
                    {{-- @if (request('receiverId'))
                        <option value="">
                            {{ request('receiverId') == $allCompanies['BetakaDriba'] ? $allCompanies['name'] : '' }}
                        </option>
                    @endif --}}
                    <option value="">جميع العملاء</option>
                    @foreach ($allCompanies as $company)
                        @if (request('receiverId'))
                            <option value=""
                                {{ request('receiverId') == $company->tax_id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @else
                            <option value="{{ $company->tax_id }}">{{ $company->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="inputPassword4">إتجاه الفاتورة</label>
                <select class="form-control single-select" name="direction">
                    {{-- <option value="">إختر إتجاه الفواتير...</option> --}}
                    <option value="Sent" {{ request('direction') == 'Sent' ? 'selected' : '' }}>مرسلة</option>
                    <option value="Received" {{ request('direction') == 'Received' ? 'selected' : '' }}>مُستقبلة
                    </option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="inputPassword4">حالة الفاتورة</label>
                <select class="form-control single-select" name="status">
                    <option value="Valid" {{ request('status') == 'Valid' ? 'selected' : '' }}>صحيحة</option>
                    <option value="Invalid" {{ request('status') == 'Invalid' ? 'selected' : '' }}>غير صحيحة</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>تم الغائها
                    </option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>تم رفضها</option>
                    </option>
                </select>
            </div>
        </div>

</div>

<div style="text-align: center">

    <button type="submit" class="btn btn-primary" style="margin-bottom: 50px;width: 100px">بحــث</button>
</div>
</form>
