@extends('layouts.main')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الإرسالات</div>


        </div>


        <p>

            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                aria-expanded="false" aria-controls="collapseExample">
                بحــث متقــدم
            </button>
        </p>

        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <form action="{{ route('searchInSentInv') }}">
                    <div class="row">
                        <div class="mb-3 col-3">
                            <label for="exampleInputEmail1" class="form-label">بحث حر </label>
                            <input type="text"  name="freetext" class="form-control">
                        </div>
                        <div class="mb-3 col-3">
                            <label for="exampleInputPassword1" class="form-label">التاريخ من</label>
                            <input type="date"  name="datefrom" class="form-control">
                        </div>
                        <div class="mb-3 col-3">
                            <label for="exampleInputPassword1" class="form-label">التاريخ الى</label>
                            <input type="date"  name="dateto" class="form-control">
                        </div>
                        <div class="col-12" style="text-align: center;margin:20px">

                            <button type="submit" class="btn btn-primary" style="width: 200px">بحـــث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <div style="text-align: center; margin: 20px">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li style="list-style: none;font-size:25px">{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif


            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <ul>
                        <li style="list-style: none;font-size:25px">{!! \Session::get('error') !!}</li>
                    </ul>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>مسلسل</th>
                                <th>الرقم الداخلى</th>
                                <th>الرقم الإلكترونى</th>
                                <th>نوع المستند</th>
                                {{-- <th>حالة الفاتورة</th> --}}
                                <th>اسم العميل</th>
                                <th>نوع العميل</th>
                                <th>الرقم الضريبى / الرقم القومى</th>
                                <th>عنوان العميل</th>
                                <th>تاريخ الفاتورة</th>
                                <th>إجمالى الفاتورة</th>
                                <th>عرض تفاصيل و حالة الفاتورة</th>
                                <th>عرض الفاتورة pdf</th>
                                <th>عرض الفاتورة على بورتال الضرائب </th>
                                <th>مسح الفاتورة المرسلة</th>
                        </thead>
                        <tbody>


                            @foreach ($allSent as $index => $sent)
                                <tr>
                                    <td>{{ $index + 1 }}</td>


                                    <td>{{ $sent['jsondata']['documents'][0]['internalID'] }}</td>
                                    <td>{{ $sent->uuid }}</td>
                                    @if ($sent['jsondata']['documents'][0]['documentType'] == 'I')
                                        <td>فاتــورة</td>
                                    @elseif ($sent['jsondata']['documents'][0]['documentType'] == 'C')
                                        <td>إشعار دائــن</td>
                                    @else
                                        <td>إشعــار مديــن</td>
                                    @endif

                                    {{-- @php


                                        $showInvoice = Http::withHeaders([
                                            'Authorization' => 'Bearer ' . $token,
                                        ])->get("https://api.preprod.invoicing.eta.gov.eg/api/v1.0/documents/$sent->uuid/details");

                                    @endphp
                                    @isset($showInvoice['status'])

                                    @if ($showInvoice['status'] == 'Valid')
                                    <td>صحيحة</td>
                                    @elseif ($showInvoice['status'] == "Invalid")
                                    <td>غير صحيحة</td>
                                    @elseif ($showInvoice['status'] == "Submitted")
                                    <td>تم إرسالها و جارى المراجعة</td>
                                    @else
                                    <td>{{ $showInvoice['status'] }}</td>
                                    @endif
                                    @else
                                    <td>يوجد مشكلة بسيرفر الضرائب</td>
                                    @endisset --}}
                                    @isset($sent['jsondata']['documents'][0]['receiver']['name'])
                                        <td>{{ $sent['jsondata']['documents'][0]['receiver']['name'] }}</td>
                                    @else
                                        <td></td>
                                    @endisset

                                    @if ($sent['jsondata']['documents'][0]['receiver']['type'] == 'B')
                                        <td>شركــة</td>
                                    @elseif ($sent['jsondata']['documents'][0]['receiver']['type'] == 'P')
                                        <td>شخــص</td>
                                    @else
                                        <td>عميل أجنبى</td>
                                    @endif
                                    @isset($sent['jsondata']['documents'][0]['receiver']['id'])
                                        <td>{{ $sent['jsondata']['documents'][0]['receiver']['id'] }}</td>
                                    @else
                                        <td></td>
                                    @endisset
                                    @isset($sent['jsondata']['documents'][0]['receiver']['address']['street'])
                                        <td>{{ $sent['jsondata']['documents'][0]['receiver']['address']['street'] }}</td>
                                    @else
                                        <td></td>
                                    @endisset
                                    <td>{{ Carbon\Carbon::parse($sent['jsondata']['documents'][0]['dateTimeIssued'])->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $sent['jsondata']['documents'][0]['totalAmount'] }}</td>
                                    <td><a href="{{ route('showsentdetails', $sent->uuid) }}" class="btn btn-info">
                                            عرض التفاصيل
                                        </a></td>
                                    <td> <a class="btn btn-success" href="{{ route('pdf', $sent->uuid) }}" target="_blank">
                                            عرض الفاتورة pdf
                                        </a>
                                    </td>
                                    <td><a href="https://invoicing.eta.gov.eg/print/documents/{{ $sent['uuid'] }}/share/{{ $sent['longId'] }} "
                                            target="_blank" class="btn btn-success">@lang('site.viewinportal')</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('deleteSentInv', $sent->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('هل أنت متأكد من مسح الفاتورة؟');">مسح</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>

                    </table>
                    @if ($allSent instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $allSent->links() }}
                    @else
                    @endif



                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('main/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('main/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'print'],
                sort: false,
                "paging": false,
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

{{--
{
    "issuer": {
        "id": "562272003",
        "name": "شركة الكواترو",
        "type": "B",
        "address": {
            "street": "ش جمال الدين زكى التجاريين السلام",
            "country": "EG",
            "branchID": "0",
            "governate": "القاهرة",
            "regionCity": "قسم اول السلام",
            "buildingNumber": "106"
        }
    },
    "receiver": {
        "name": null,
        "type": "P",
        "address": {
            "country": "EG"
        }
    },
    "netAmount": 200,
    "taxTotals": [
        {
            "amount": 2,
            "taxType": "T4"
        },
        {
            "amount": 28,
            "taxType": "T1"
        }
    ],
    "internalID": "48216",
    "totalAmount": 220,
    "documentType": "I",
    "invoiceLines": [
        {
            "total": 226,
            "discount": {
                "rate": 0,
                "amount": 0
            },
            "itemCode": "10005726",
            "itemType": "GS1",
            "netTotal": 200,
            "quantity": 20,
            "unitType": "EA",
            "unitValue": {
                "amountEGP": 10,
                "amountSold": 0,
                "currencySold": "EGP",
                "currencyExchangeRate": 0
            },
            "salesTotal": 200,
            "description": "وصف",
            "internalCode": "100",
            "taxableItems": [
                {
                    "rate": 1,
                    "amount": 2,
                    "subType": "W001",
                    "taxType": "T4"
                },
                {
                    "rate": 14,
                    "amount": 28,
                    "subType": "V001",
                    "taxType": "T1"
                }
            ],
            "itemsDiscount": 0,
            "valueDifference": 0,
            "totalTaxableFees": 0
        }
    ],
    "dateTimeIssued": "2022-12-24T08:25:51Z",
    "totalSalesAmount": 200,
    "documentTypeVersion": "0.9",
    "extraDiscountAmount": 6,
    "totalDiscountAmount": 0,
    "taxpayerActivityCode": "4610",
    "totalItemsDiscountAmount": 0
} --}}
