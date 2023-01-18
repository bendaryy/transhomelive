@extends('layouts.main')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الفواتير (المسودة)</div>


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
                                <th>اسم العميل</th>
                                <th>تاريخ كتــابة الفاتــورة</th>
                                <th>إجمالى الفاتورة</th>
                                <th>التحكم</th>
                                <th>موقــف الفــاتورة</th>
                                <th>مسح</th>
                        </thead>
                        <tbody>


                            @foreach ($allDraft as $index=>$draft)
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{ $draft['jsondata']['internalID'] }}</td>
                                    @isset($draft['jsondata']['receiver']['name'])
                                        <td>{{ $draft['jsondata']['receiver']['name'] }}</td>
                                    @else
                                        <td></td>
                                    @endisset
                                    <td>{{ Carbon\Carbon::parse($draft['jsondata']['dateTimeIssued'])->format('d-m-Y') }}</td>
                                    <td>{{ $draft['jsondata']['totalAmount'] }}</td>
                                    <td>
                                        <a href="{{ route('showDraftDetails',$draft->id) }}" class="btn btn-secondary">عرض التفاصيل</a>
                                    </td>

                                        {{-- <form action="{{ route('sendDraftData',$draft->id) }}" method="post">
                                            @csrf
                                            @method('post')
                                            <button class="btn btn-success">ارسال</button>
                                        </form> --}}
                                    @if($draft->inv_uuid != null)
                                        <td style="background-color: #28A745;color:white">تم إرسالها</td>
                                        @else
                                        <td style="background-color: #FFC107;">غير مرسلة</td>
                                    @endif
                                    <td>
                                        <form action="{{ route('deleteDraft',$draft->id) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-danger" onclick="return confirm('هل أنت متأكد من مسح الفاتورة؟');">مسح الفاتورة</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>

                    </table>
                    {{ $allDraft->links() }}

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
