@extends('layouts.main')

@section('content')

    <div class="page-content">
        @include('search.index')
        @if (request()->routeIs('allinvoices'))
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('site.documents')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('site.sent_documents')</li>
                        </ol>
                    </nav>
                </div>
                {{-- <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('createInvoice') }}" class="btn btn-outline-success px-5 radius-30">
                        <i class="bx bx-message-square-edit mr-1"></i>@lang('site.add-document')</a>

                </div>
            </div> --}}
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
                                    <th>الرقم الداخلى للفاتورة</th>
                                    <th>اسم مُستقبل الفاتورة</th>
                                    <th>اسم مُرسل الفاتورة</th>
                                    <th>نوع الفاتورة</th>
                                    <th>حالة الفاتورة</th>
                                    <th>إجمالى الفاتورة</th>
                                    <th>تاريخ إصدار الفاتورة </th>
                                    <th>الرقم الإلكترونى </th>
                                    <th>@lang('site.doc_view')</th>
                                    <th>@lang('site.doc_Download')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($allInvoices as $index => $invoice)

                                    <tr>
                                        <td>{{ $invoice['internalId'] }}</td>
                                        <td>{{ $invoice['receiverName'] }}</td>
                                        <td>{{ $invoice['issuerName'] }}</td>
                                        @if ($invoice['issuerId'] == auth()->user()->details->company_id)
                                            <th style="background-color: green;color:white;opacity:0.8">فاتورة تم ارسالها
                                            </th>
                                        @else
                                            <th style="background-color: red;color:white;opacity:0.8">فاتورة تم استقبالها
                                            </th>
                                        @endif
                                        @if ($invoice['status'] === 'Valid')
                                            <td>صحيحة</td>
                                        @elseif ($invoice['status'] == "Rejected")
                                        <td>تم رفــضها</td>
                                        @elseif ($invoice['status'] == "Invalid")
                                        <td>غير صحيحة</td>
                                        @elseif ($invoice['status'] == "Cancelled")
                                        <td>تم الغائها</td>
                                        @else
                                            <td>{{ $invoice['status'] }}</td>
                                        @endif
                                        <td>{{ $invoice['total'] }}</td>
                                        <td>{{ Carbon\Carbon::parse($invoice['dateTimeIssued'])->format('d-m-Y') }}
                                        </td>
                                        <td>{{ $invoice['uuid'] }}</td>
                                        <td><a href="{{ $invoice['publicUrl'] }}" target="_blank"
                                                class="btn btn-success">@lang('site.viewinportal')</a>
                                        </td>
                                        <td><a href="{{ route('pdf', $invoice['uuid']) }}" class="btn btn-info"
                                                target="_blank"> @lang('site.download') </a></td>

                                @endforeach


                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        @endif
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
