@extends('layouts.main')

@section('content')
    <div class="page-content">

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
                    <table id="example2" class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>@lang('site.Reciever_Name')</th>
                                <th>@lang('site.Total')</th>
                                <th>@lang('site.invoice status')</th>
                                <th>تاريخ تسجيل الفاتورة </th>
                                <th>تاريخ إصدار الفاتورة </th>
                                <th>الرقم الإلكترونى </th>
                                <th>@lang('site.internalid') </th>
                                <th>@lang('site.doc_view')</th>
                                <th>@lang('site.doc_Download')</th>
                                {{-- <th>تحميل الفاتورة </th> --}}
                                <th>@lang('site.doc_cancel')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($allInvoices as $index => $invoice)
                                @if ($invoice['issuerId'] == $taxId)
                                    <tr>
                                        <td>{{ $invoice['receiverName'] }}</td>
                                        <td>{{ $invoice['total'] }} EGP</td>
                                        @if ($invoice['status'] === 'Valid')
                                            <td>@lang('site.valid')</td>
                                        @elseif ($invoice['status'] === 'Invalid')
                                            <td>@lang('site.invalid')</td>
                                        @elseif($invoice['status'] === 'Cancelled')
                                            <td>@lang('site.cancelled')</td>
                                        @elseif($invoice['status'] === 'Submitted')
                                            <td>@lang('site.submited')</td>
                                        @elseif($invoice['status'] === 'Rejected')
                                            <td>@lang('site.rejected')</td>
                                        @else
                                            <td>{{ $invoice['status'] }}</td>
                                        @endif
                                        <td> {{ Carbon\Carbon::parse($invoice['dateTimeIssued'])->format('d-m-Y') }}</td>
                                        <td> {{ Carbon\Carbon::parse($invoice['dateTimeReceived'])->format('d-m-Y') }}
                                        </td>
                                        {{-- <td> {{ Carbon\Carbon::parse($invoice['dateTimeIssued'])->format('d-m-Y') }}</td> --}}
                                        <td> {{ $invoice['uuid'] }}</td>
                                        <td> {{ $invoice['internalId'] }}</td>
                                        {{-- <td> {{ $invoice['dateTimeIssued'] }}</td> --}}
                                        <td><a href="https://invoicing.eta.gov.eg/print/documents/{{ $invoice['uuid'] }}/share/{{ $invoice['longId'] }} "
                                                target="_blank" class="btn btn-success">@lang('site.viewinportal')</a>
                                        </td>
                                        <td><a href="{{ route('pdf', $invoice['uuid']) }}" class="btn btn-info"
                                                target="_blank"> @lang('site.download') </a></td>

                                        <td>
                                            <form action="{{ route('cancelDocument', $invoice['uuid']) }}"
                                                method="post">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-danger" type="submit"
                                                    onclick="return confirm('@lang('site.rusuretocancel')');">@lang('site.cancel invoice')</button>
                                            </form>
                                        </td>

                                    </tr>
                                @endif
                            @endforeach


                        </tbody>

                    </table>
                    <nav aria-label="Page navigation example">

                        {{-- {{ $allMeta['totalPages'] }} --}}

                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" {{ $id == 1 ?'style=display:none' :''}}
                                    href="{{ route('sentInvoices',$id - 1) }}">السابق</a></li>
                            @for ($i = 1; $i <= $allMeta['totalPages']; $i++)
                                <li class="page-item"><a class="page-link"
                                        {{ $i == $id ? 'style=background-color:#CCC' : '' }}
                                        href="{{ route('sentInvoices', $i) }}">{{ $i }}</a></li>
                            @endfor
                            <li class="page-item"><a class="page-link"
                                {{ $id ==  $allMeta['totalPages'] ?'style=display:none' :''}}     href="{{ route('sentInvoices', $id + 1) }}">التالى</a></li>


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
                "pageLength": 100,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
                ],
                buttons: ['copy', 'excel', 'print'],
                sort: false,
                "paging": false,
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
