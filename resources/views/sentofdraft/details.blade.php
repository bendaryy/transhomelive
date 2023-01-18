@extends('layouts.main')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">
                <h3> حالة الفاتورة</h3>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        {{-- <li class="breadcrumb-item"><a href="javascript:;"></a></li> --}}
                        @isset($showInvoice['status'])


                        @if ($showInvoice['status'] == 'Invalid')
                            <li class="breadcrumb-item active" aria-current="page">
                                <h3 class="btn btn-danger">غير صحيح</h3>

                                <table class=" table-striped table-bordered text-center">
                                    <tr>
                                        @foreach ($showInvoice['validationResults']['validationSteps'] as $error)
                                        @if($error['status'] == "Invalid")
                                        {{-- <h4 style="background-color: red;padding:10px;color:white;text-align: center">{{ $error['error']['errorAr'] }}</h4> --}}
                                        <th style="color: red">{{ $error['error']['errorAr'] }}</th>
                                        @foreach ($error['error']['innerError'] as $errorDetails)
                                        <tr>
                                            <td>  {{ $errorDetails['errorAr'] }}</td>
                                        </tr>

                                        @endforeach
                                        @endif
                                        @endforeach
                                    </tr>
                                </table>
                                {{-- <h3>{{ $showInvoice['validationResults']['validationSteps'][0]['error']['errorAr'] }}</h3> --}}
                            </li>
                        @elseif ($showInvoice['status'] == 'Valid')
                            <li class="breadcrumb-item active" aria-current="page">
                                <h3 class="btn btn-success">صحيحة</h3>
                            </li>
                        @elseif ($showInvoice['status'] == 'Submitted')
                            <li class="breadcrumb-item active" aria-current="page">
                                <h3 class="btn btn-info">تم إرسالها و جارى المراجعة</h3>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                <h3 class="btn btn-secondary">{{ $showInvoice['status'] }}</h3>
                            </li>
                            @endif

                            @else
                            <li class="breadcrumb-item active" aria-current="page">
                                <h3 class="btn btn-secondary">غير معروف</h3>
                            </li>
                    @endisset
                        {{-- <li class="breadcrumb-item active" aria-current="page"><h3>{{ $status }}</h3></li> --}}
                    </ol>
                </nav>
            </div>

        </div>
        {{-- {{ $allSent[0]['jsondata']['documents'][0]['issuer']['address']['street'] }} --}}


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
                            <td style="padding: 20px">تاريخ الفاتورة</td>
                            <td style="padding: 20px">
                                {{ Carbon\Carbon::parse($allSent[0]['jsondata']['documents'][0]['dateTimeIssued'])->format('d-m-Y') }}
                            </td>
                        </tr>
                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['name'])
                            <tr>
                                <td style="padding: 20px">إسم العميل</td>
                                <td style="padding: 20px">{{ $allSent[0]['jsondata']['documents'][0]['receiver']['name'] }}</td>
                            </tr>
                        @endisset
                        <tr>
                            <td style="padding: 20px">نوع الوثيقة</td>
                            @if ($allSent[0]['jsondata']['documents'][0]['documentType'] == 'I')
                                <td style="padding: 20px">فاتورة</td>
                            @elseif ($allSent[0]['jsondata']['documents'][0]['documentType'] == 'C')
                                <td style="padding: 20px">إشعار دائن</td>
                            @else
                                <td style="padding: 20px">إشعار مدين</td>
                            @endif
                        </tr>

                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['id'])
                            <tr>
                                <td style="padding: 20px">الرقم الضريبى</td>
                                <td style="padding: 20px">{{ $allSent[0]['jsondata']['documents'][0]['receiver']['id'] }}</td>
                            </tr>
                        @endisset

                        @if ($allSent[0]['jsondata']['documents'][0]['receiver']['type'] == 'B')
                            <tr>
                                <td style="padding: 20px">نوع العميل</td>
                                <td style="padding: 20px">شركة</td>
                            </tr>
                        @elseif($allSent[0]['jsondata']['documents'][0]['receiver']['type'] == 'P')
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

                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['street'])
                            <td style="padding: 20px">عنوان العميل</td>
                            <td style="padding: 20px">
                                {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['street'] }}</td>
                        @endisset
                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['country'])
                            @if ($allSent[0]['jsondata']['documents'][0]['receiver']['address']['country'] == 'EG')
                                <tr>
                                    <td style="padding: 20px">الدولة</td>
                                    <td style="padding: 20px">مـــصــر</td>
                                </tr>
                            @else
                                <tr>
                                    <td style="padding: 20px">الدولة</td>
                                    <td style="padding: 20px">
                                        {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['country'] }}</td>
                                </tr>
                            @endif
                        @endisset

                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['branchID'])
                            <tr>
                                <td style="padding: 20px">كود الفرع</td>
                                <td style="padding: 20px">
                                    {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['branchID'] }}</td>
                            </tr>
                        @endisset
                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['governate'])
                            <tr>
                                <td style="padding: 20px">المحافظة</td>
                                <td style="padding: 20px">
                                    {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['governate'] }}</td>
                            </tr>
                        @endisset
                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['regionCity'])
                            <tr>
                                <td style="padding: 20px">المدينة (القسم)</td>
                                <td style="padding: 20px">
                                    {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['regionCity'] }}</td>
                            </tr>
                        @endisset
                        @isset($allSent[0]['jsondata']['documents'][0]['receiver']['address']['buildingNumber'])
                            <tr>
                                <td style="padding: 20px">رقم المبنى</td>
                                <td style="padding: 20px">
                                    {{ $allSent[0]['jsondata']['documents'][0]['receiver']['address']['buildingNumber'] }}</td>
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
                        @foreach ($allSent[0]['jsondata']['documents'][0]['invoiceLines'] as $invoice)
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
                            <td style="padding: 20px">{{ $allSent[0]['jsondata']['documents'][0]['totalSalesAmount'] }}
                            </td>
                            <td style="padding: 20px">{{ $allSent[0]['jsondata']['documents'][0]['totalDiscountAmount'] }}
                            </td>
                            <td style="padding: 20px">{{ $allSent[0]['jsondata']['documents'][0]['netAmount'] }}</td>
                            @isset($allSent[0]['jsondata']['documents'][0]['taxTotals'])
                                <td>
                                    @foreach ($allSent[0]['jsondata']['documents'][0]['taxTotals'] as $taxes)
                                        @if ($taxes['taxType'] == 'T4')
                                            الخصم تحت حساب الضريبة
                                        @elseif($taxes['taxType'] == 'T1')
                                            ضريبة القيمة المضافة
                                        @elseif ($taxes['taxType'] == 'T2')
                                            ضريبة الجدول (النسبية)
                                        @endif
                                          {{ $taxes['amount'] }}
                                        <hr>
                                    @endforeach
                                </td>
                            @else
                                <td>لا يوجد ضريبة</td>
                            @endisset
                            <td>{{ $allSent[0]['jsondata']['documents'][0]['totalItemsDiscountAmount'] }}</td>
                            <td>{{ $allSent[0]['jsondata']['documents'][0]['extraDiscountAmount'] }}</td>
                            <td style="color:red;font-weight: bold">
                                {{ $allSent[0]['jsondata']['documents'][0]['totalAmount'] }}</td>

                        </tr>

                        </tbody>

                    </table>
                    <div class="text-center">

                        <a class="btn btn-success" href="{{ route('pdf', $uuid) }}" target="_blank">
                            عرض و طباعة الفاتورة pdf
                        </a>
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
@endpush
