@extends('layouts.main')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">
                <h3>وثيقة رقم </h3>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">



                        <li class="breadcrumb-item active" aria-current="page">
                            <h3>{{ $draft['internalID'] }}</h3>
                        </li>
                    </ol>
                </nav>
            </div>

        </div>
        @if ($invUuid != null)
            @php

                $response = Http::asForm()->post('https://id.preprod.eta.gov.eg/connect/token', [
                    'grant_type' => 'client_credentials',
                    'client_id' => auth()->user()->details->client_id,
                    'client_secret' => auth()->user()->details->client_secret,
                    'scope' => 'InvoicingAPI',
                ]);
                $token = $response['access_token'];

                $showInvoice = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get("https://api.preprod.invoicing.eta.gov.eg/api/v1.0/documents/$invUuid/details");

            @endphp
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">
                    <h3>حالة الفاتورة </h3>
                </div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">


                            @isset($showInvoice['status'])
                                <li class="breadcrumb-item active" aria-current="page">
                                    @if ($showInvoice['status'] == 'Valid')
                                        <h3 class="btn btn-success">صحيحة</h3>
                                    @elseif ($showInvoice['status'] == 'Invalid')
                                        <h3 class="btn btn-danger">غير صحيحة</h3>
                                    @elseif ($showInvoice['status'] == 'Submitted')
                                        <h3 class="btn btn-secondary">تم إرسالها و جارى المراجعة</h3>
                                    @else
                                        <h3 class="btn btn-secondary">{{ $showInvoice['status'] }}</h3>
                                    @endif
                                @else
                                <li class="breadcrumb-item active" aria-current="page">
                                    <h3 class="btn btn-secondary">غير معروف</h3>
                                </li>
                            @endisset
                        </ol>
                    </nav>
                </div>

            </div>

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">
                    <h3>طباعة الفاتورة </h3>
                </div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ route('pdf', $invUuid) }}" target="_blank" class="btn btn-success">طباعة</a>

                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        @endif


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
        <h3 style="text-align: center;margin:20px">بيانات العميل</h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered text-center">
                        <tr>
                            @if ($invUuid != null)
                                <td style="padding: 20px">تاريخ إرسال الفاتورة</td>
                                <td style="padding: 20px">
                                    @isset($showInvoice['dateTimeIssued'])
                                    {{ Carbon\Carbon::parse($showInvoice['dateTimeIssued'])->format('d-m-Y') }}
                                        @else
                                        {{ Carbon\Carbon::parse($draft['dateTimeIssued'])->format('d-m-Y') }}
                                    @endisset
                                </td>
                            @else
                                <td style="padding: 20px">تاريخ كتابة الفاتورة</td>
                                <td style="padding: 20px">
                                    {{ Carbon\Carbon::parse($draft['dateTimeIssued'])->format('d-m-Y') }}
                                </td>
                            @endif
                        </tr>
                        @isset($draft['receiver']['name'])
                            <tr>
                                <td style="padding: 20px">إسم العميل</td>
                                <td style="padding: 20px">{{ $draft['receiver']['name'] }}</td>
                            </tr>
                        @endisset
                        <tr>
                            <td style="padding: 20px">نوع الوثيقة</td>
                            @if ($draft['documentType'] == 'I')
                                <td style="padding: 20px">فاتورة</td>
                            @elseif ($draft['documentType'] == 'C')
                                <td style="padding: 20px">إشعار دائن</td>
                            @else
                                <td style="padding: 20px">إشعار مدين</td>
                            @endif
                        </tr>

                        @isset($draft['receiver']['id'])
                            <tr>
                                <td style="padding: 20px">الرقم الضريبى</td>
                                <td style="padding: 20px">{{ $draft['receiver']['id'] }}</td>
                            </tr>
                        @endisset

                        @if ($draft['receiver']['type'] == 'B')
                            <tr>
                                <td style="padding: 20px">نوع العميل</td>
                                <td style="padding: 20px">شركة</td>
                            </tr>
                        @elseif($draft['receiver']['type'] == 'P')
                            <tr>
                                <td style="padding: 20px">نوع العميل</td>
                                <td style="padding: 20px">شخص</td>
                            </tr>
                        @else
                            <tr>
                                <td style="padding: 20px">نوع العميل</td>
                                <td style="padding: 20px">عميل أجنبى</td>
                            </tr>
                        @endif

                        @isset($draft['receiver']['address']['street'])
                            <td style="padding: 20px">عنوان العميل</td>
                            <td style="padding: 20px">{{ $draft['receiver']['address']['street'] }}</td>
                        @endisset
                        @isset($draft['receiver']['address']['country'])
                            @if ($draft['receiver']['address']['country'] == 'EG')
                                <tr>
                                    <td style="padding: 20px">الدولة</td>
                                    <td style="padding: 20px">مـــصــر</td>
                                </tr>
                            @else
                                <tr>
                                    <td style="padding: 20px">الدولة</td>
                                    <td style="padding: 20px">{{ $draft['receiver']['address']['country'] }}</td>
                                </tr>
                            @endif
                        @endisset

                        @isset($draft['receiver']['address']['branchID'])
                            <tr>
                                <td style="padding: 20px">كود الفرع</td>
                                <td style="padding: 20px">{{ $draft['receiver']['address']['branchID'] }}</td>
                            </tr>
                        @endisset
                        @isset($draft['receiver']['address']['governate'])
                            <tr>
                                <td style="padding: 20px">المحافظة</td>
                                <td style="padding: 20px">{{ $draft['receiver']['address']['governate'] }}</td>
                            </tr>
                        @endisset
                        @isset($draft['receiver']['address']['regionCity'])
                            <tr>
                                <td style="padding: 20px">المدينة (القسم)</td>
                                <td style="padding: 20px">{{ $draft['receiver']['address']['regionCity'] }}</td>
                            </tr>
                        @endisset
                        @isset($draft['receiver']['address']['buildingNumber'])
                            <tr>
                                <td style="padding: 20px">رقم المبنى</td>
                                <td style="padding: 20px">{{ $draft['receiver']['address']['buildingNumber'] }}</td>
                            </tr>
                        @endisset


                        </tbody>

                    </table>

                </div>
            </div>
        </div>
        <h3 style="text-align: center;margin:20px">البنود</h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered text-center">

                        <tr>
                            <th style="padding: 20px">مخطط نظام التكويد</th>
                            <th style="padding: 20px">رقم الكود</th>
                            <th style="padding: 20px">الوصف</th>
                            <th style="padding: 20px">نوع الوحدة</th>
                            <th style="padding: 20px">الكمية</th>
                            <th style="padding: 20px">سعر الوحدة</th>
                            <th style="padding: 20px">إجمالى مبلغ المبيعات</th>
                            <th style="padding: 20px">قيمة الخصم</th>
                            <th style="padding: 20px">الإجمالى الصافى</th>
                            <th style="padding: 20px">الضريبة</th>
                            <th style="padding: 20px">قيمة خصم الإصناف</th>
                            <th style="padding: 20px">الإجمالى الكلى للبند</th>
                        </tr>
                        @foreach ($draft['invoiceLines'] as $invoice)
                            <tr>
                                <td style="padding: 20px">{{ $invoice['itemType'] }}</td>
                                <td style="padding: 20px">{{ $invoice['itemCode'] }}</td>
                                <td style="padding: 20px">{{ $invoice['description'] }}</td>
                                <td style="padding: 20px">{{ $invoice['unitType'] }}</td>
                                <td style="padding: 20px">{{ $invoice['quantity'] }}</td>
                                <td style="padding: 20px">{{ $invoice['unitValue']['amountEGP'] }}</td>
                                <td style="padding: 20px">{{ $invoice['salesTotal'] }}</td>
                                <td style="padding: 20px">{{ $invoice['discount']['amount'] }}</td>
                                <td style="padding: 20px">{{ $invoice['netTotal'] }}</td>
                                @isset($invoice['taxableItems'])
                                    <td>
                                        @foreach ($invoice['taxableItems'] as $taxes)
                                            @if ($taxes['taxType'] == 'T4')
                                                الخصم تحت حساب الضريبة ( {{ $taxes['rate'] }}% )
                                            @elseif($taxes['taxType'] == 'T1')
                                                ضريبة القيمة المضافة ( {{ $taxes['rate'] }}% )
                                            @elseif ($taxes['taxType'] == 'T2')
                                                ضريبة الجدول (النسبية) ( {{ $taxes['rate'] }}% )
                                            @endif = {{ $taxes['amount'] }}
                                            <hr>
                                        @endforeach
                                    </td>
                                @else
                                    <td>لا يوجد ضريبة</td>
                                @endisset


                                <td style="padding: 20px">{{ $invoice['itemsDiscount'] }}</td>
                                <td style="padding: 20px">{{ $invoice['total'] }}
                                    {{ $invoice['unitValue']['currencySold'] }}</td>
                            </tr>
                        @endforeach



                        </tbody>

                    </table>

                </div>
            </div>
        </div>
        <h3 style="text-align: center;margin:20px">الإجماليات</h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered text-center">

                        <tr>
                            <th style="padding: 20px">إجمالى المبيعات</th>
                            <th style="padding: 20px">إجمالى الخصومات</th>
                            <th style="padding: 20px">الإجمالى الصافى</th>
                            <th style="padding: 20px">إجمالى الضريبة</th>
                            <th style="padding: 20px">إجمالى خصم الأصناف</th>
                            <th style="padding: 20px">الخصم الإضافى</th>
                            <th style="padding: 20px">إجمالى المدفوع</th>
                        </tr>
                        <tr>
                            <td style="padding: 20px">{{ $draft['totalSalesAmount'] }}</td>
                            <td style="padding: 20px">{{ $draft['totalDiscountAmount'] }}</td>
                            <td style="padding: 20px">{{ $draft['netAmount'] }}</td>
                            @isset($draft['taxTotals'])
                                <td>
                                    @foreach ($draft['taxTotals'] as $taxes)
                                        @if ($taxes['taxType'] == 'T4')
                                            الخصم تحت حساب الضريبة
                                        @elseif($taxes['taxType'] == 'T1')
                                            ضريبة القيمة المضافة
                                        @elseif ($taxes['taxType'] == 'T2')
                                            ضريبة الجدول (النسبية)
                                        @endif = {{ $taxes['amount'] }}
                                        <hr>
                                    @endforeach
                                </td>
                            @else
                                <td>لا يوجد ضريبة</td>
                            @endisset
                            <td>{{ $draft['totalItemsDiscountAmount'] }}</td>
                            <td>{{ $draft['extraDiscountAmount'] }}</td>
                            <td style="color:red;font-weight: bold">{{ $draft['totalAmount'] }}</td>

                        </tr>

                        </tbody>

                    </table>
                    <div class="text-center">
                        <form action="{{ route('sendDraftData', $id) }}" method="post">
                            @csrf
                            @method('post')
                            @if ($invUuid != null)
                                <button type="submit" class="btn btn-success" id="sendNewInv"
                                    style="width: 200px;margin:30px">إرسال الفاتورة مرة أخرى</button>
                            @else
                                <button type="submit" class="btn btn-success" id="sendNewInv"
                                    style="width: 200px;margin:30px">ارسال</button>
                            @endif
                        </form>
                        <button disabled style="display: none" class="btn btn-primary" id="disabledButton">جارى
                            الإرسال...</button>
                    </div>

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
                "paging": true,

            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>


    <script>
        $(document).on('click', '#sendNewInv', function() {
            var button = $(this);
            button.hide();
            $("#disabledButton").show()
            setTimeout(function() {
                button.show();
                $("#disabledButton").hide()
            }, 10000);

        });
    </script>
@endpush
