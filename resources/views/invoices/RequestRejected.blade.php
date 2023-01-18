@extends('layouts.main')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('site.documents')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page"> تم تقديم طلب لرفضها من خلالنا</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('createInvoice') }}" class="btn btn-outline-success px-5 radius-30">
                        <i class="bx bx-message-square-edit mr-1"></i>@lang('site.add-document')</a>

                </div>
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
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>اسم الشركة</th>
                                <th>الإجمالى</th>
                                <th>حالة الفاتورة</th>
                                <th>تاريخ قبول الرفض</th>
                                <th>توقيت قبول الرفض</th>
                                {{-- <th>تاريخ الفاتورة</th> --}}
                                <th>الرقم الداخلى </th>
                                <th>عرض الفاتورة</th>
                                <th>تحميل الفاتورة </th>
                                {{-- <th>تحميل الفاتورة </th> --}}
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($allInvoices as $invoice)
                                @if ($invoice['receiverId'] == auth()->user()->details->company_id &&
                                    $invoice['rejectRequestDate'] !== null &&
                                    $invoice['status'] === 'Valid' &&
                                    $invoice['declineRejectRequestDate'] === null)
                                    <tr>
                                        <td>{{ $invoice['issuerName'] }}</td>
                                        <td>{{ $invoice['total'] }} EGP</td>
                                        @if ($invoice['status'] === 'Valid')
                                            <td>صحيحة</td>
                                        @endif
                                        <td>{{ Carbon\Carbon::parse($invoice['rejectRequestDelayedDate'])->format('d-m-Y') }}
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($invoice['rejectRequestDelayedDate'])->format('m-i-s') }}
                                        </td>
                                        {{-- <td> {{ Carbon\Carbon::parse($invoice['dateTimeIssued'])->format('d-m-Y') }}</td> --}}
                                        <td> {{ $invoice['internalId'] }}</td>
                                        {{-- <td> {{ $invoice['dateTimeIssued'] }}</td> --}}
                                        <td><a href="{{ $invoice['publicUrl'] }}" class="btn btn-success">عرض الفاتورة على
                                                موقع الضرائب</a></td>
                                        <td><a href="{{ route('pdf', $invoice['uuid']) }}" class="btn btn-info"> pdf
                                                تحميل الفاتورة </a></td>

                                    </tr>
                                @endif
                            @endforeach


                        </tbody>

                    </table>
                    <nav aria-label="Page navigation example">

                        {{-- {{ $allMeta['totalPages'] }} --}}

                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" {{ $id == 1 ? 'style=display:none' : '' }}
                                    href="{{ route('requestRejected', $id - 1) }}">السابق</a></li>
                            @for ($i = 1; $i <= $allMeta['totalPages']; $i++)
                                <li class="page-item"><a class="page-link"
                                        {{ $i == $id ? 'style=background-color:#CCC' : '' }}
                                        href="{{ route('requestRejected', $i) }}">{{ $i }}</a></li>
                            @endfor
                            <li class="page-item"><a class="page-link"
                                    {{ $id == $allMeta['totalPages'] ? 'style=display:none' : '' }}
                                    href="{{ route('requestRejected', $id + 1) }}">التالى</a></li>


                        </ul>
                    </nav>
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
                buttons: ['copy', 'excel', 'pdf', 'print'],
                sort: false "paging": false
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
