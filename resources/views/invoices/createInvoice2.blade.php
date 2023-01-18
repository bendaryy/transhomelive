@extends('layouts.main')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function randomTest() {
        var internalValue = document.getElementById('internalId');
        internalValue.value = Math.floor(Math.random() * 105140);
    }
</script>


<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<title>{{ config('app.name', 'E_TAX') }}</title>



<style>
    th,
    td,
    tr,
    table {
        padding: 5px;
        text-align: center;
    }

    .borderNone {
        border: none
    }

    .borderNone:focus {
        outline: none;
    }

    .online-actions {
        display: none;
    }

    .navbar-expand-sm {
        justify-content: center
    }

        {
            {
            -- input[type="number"] {
                width: 130;
                text-align: center
            }

            --
        }
    }

        {
            {

            -- input[name="totalItemsDiscount[]"],
            input[name="totalAmount2"],
            input[name="totalAmount"] {
                width: 250;
            }

            --
        }
    }

    input[readonly] {
        background-color: #ccc
    }

    hr {
        border: 4px solid orange;
    }
</style>





@section('content')
<div class=" page-content page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    </hr>
    <div class="breadcrumb-title pe-3">@lang('site.documents')</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">@lang("site.add-document")</li>
            </ol>
        </nav>
    </div>

</div>
<div class="wrapper" style="background-color: white">


    @if (request()->routeIs('createInvoice'))
    <form action="{{ route('createInvoice2') }}" method="GET">
        <div class="card text-center" style="margin: auto;margin-bottom: 50px">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">@lang('site.chooseReceiver')</label>
                        <select class="single-select" name="receiverName" class="form-control" id="receiverName">
                            <option selected disabled>@lang('site.chooseReceiver')</option>
                            @foreach ($allCompanies as $companies)
                            <option value="{{ $companies->id }}" class="form-control">
                                {{ $companies->name }}
                            </option>
                            @endforeach

                        </select>

                    </div>
                    <div class="col-2" style="margin-top: 23">

                        <a href="{{ route('customer.create') }}" class="btn btn btn-success " style="text-align: center;min-width: 250px!important; background-color: #1598ca;
                                                    border-color: #1598ca;">
                            @lang('site.addReceiver')
                        </a>
                    </div>
                </div>
            </div>
            {{-- <div class="form-group">
                <button type="submit" class="btn btn-success"
                    style="text-align: center;min-width: 250px!important;background-color: #1598ca;
                                            border-color: #1598ca; margin-bottom: 30px;">@lang('site.fillDetails')</button>
            </div> --}}
    </form>
    @else
    <div style="text-align: center">
        <a href="{{ route('createInvoice') }}" class="btn btn-success" style="text-align: center;min-width: 250px!important;background-color: #1598ca;
                                    border-color: #1598ca; margin-bottom: 30px;">@lang('site.backtochoose')</a>
    </div>
    @endif



    <div style="margin-bottom: 50px">
        <form method="POST">
            @method("POST")
            @csrf

            <div class="row justify-content-center">



                <div class="col-xl-10 invoice-address-client">

                    <div class="row">

                        <div class="col-6">
                            <label for="payment-method-country" class="form-label">@lang('site.Receiver_type')</label>
                            <div>
                                <select name="receiverType" class="form-control text-center form-select">
                                    <option value="B" style="font-size: 20px">@lang('site.Company')</option>
                                    <option value="P" style="font-size: 20px">@lang('site.Person')</option>
                                    <option value="F" style="font-size: 20px">@lang('site.Forign')</option>

                                </select>
                            </div>
                        </div>


                        <div class="col-6">
                            <label class="form-label">@lang('site.Receiver_to')</label>
                            <div class="">
                                <input type="text" class="form-control text-center" id="getReceiverName"
                                    name="receiverName" placeholder="@lang('site.Receiver_to')">
                            </div>
                        </div>
                        <div class="invoice-address-client-fields">

                            <div class="row">

                                <div class="col-4">
                                    <label class="form-label">@lang('site.Reciever_Registration_Number_ID')</label>
                                    <div class="">
                                        <input type="number" id="getReceiverId" style="width:350px"
                                            class="form-control text-center" name="receiverId"
                                            placeholder="@lang('site.Reciever_Registration_Number_ID')">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <label class="form-label">@lang('site.Country')</label>
                                    <div class="">
                                        <input type="text" class="form-control text-center" id="getReceiverCountry"
                                            name="receiverCountry" value="EG" placeholder="@lang('site.Country')">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">@lang('site.Governorate')</label>
                                    <div class="">
                                        <input type="text" id="getReceiverGovernate" class="form-control text-center"
                                            name="receiverGovernate" placeholder="@lang('site.Governorate')">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <label class="form-label">@lang('site.City')</label>
                                    <div class="">
                                        <input type="text" id="getReceiverRegionCity" class="form-control text-center"
                                            name="receiverRegionCity" placeholder="@lang('site.City')">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="form-label">@lang('site.StreetName') </label>
                                    <div class="">
                                        <input type="text" id="getStreet" class="form-control text-center" name="street"
                                            placeholder="@lang('site.StreetName')">
                                    </div>
                                </div>

                            </div>
                            <div class="row">


                                <div class="col-6">
                                    <label class="form-label">@lang('site.Building_Name_No')</label>
                                    <div class="">
                                        <input type="text" id="getBuildingNumber" class="form-control  text-center"
                                            name="receiverBuildingNumber" placeholder="@lang('site.Building_Name_No')">
                                    </div>
                                </div>


                                <div class="col-6">
                                    <label class="form-label"> @lang('site.PostalCode')</label>
                                    <div class="">
                                        <input type="text" id="getPostalCode" class="form-control text-center"
                                            name="receiverPostalCode" placeholder="@lang('site.PostalCode')">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label"> @lang('site.FloorNo')</label>
                                    <div class="">
                                        <input type="text" id="getFloor" class="form-control text-center"
                                            name="receiverFloor" placeholder="  @lang('site.FloorNo')">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="form-label">@lang('site.FlatNo')</label>
                                    <div class="">
                                        <input type="text" id="getRoom" class="form-control text-center"
                                            name="receiverRoom" placeholder="@lang('site.FloorNo')">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label"> @lang('site.landmark')</label>
                                    <div class="">
                                        <input type="text" id="getLandMark" class="form-control  text-center"
                                            name="receiverLandmark" placeholder="@lang('site.landmark')">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="form-label"> @lang('site.AdditionalInformation')</label>
                                    <div class="">
                                        <input type="text" id="getAdditional" class="form-control text-center"
                                            name="receiverAdditionalInformation"
                                            placeholder="@lang('site.AdditionalInformation')">
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-6">
                                    <label for="payment-method-country" class="form-label">
                                        كود النشاط</label>
                                    <div class="">
                                        <select name="taxpayerActivityCode" class="form-select">

                                            @foreach ($ActivityCodes as $code)
                                            <option value="{{ $code->code }}"> {{ $code->Desc_ar }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="payment-method-country" class="form-label">نوع الوثيقة
                                    </label>
                                    @livewire('type')
                                </div>

                            </div>






                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label"> @lang('site.Date Time Issued')</label>
                                    <div class="">
                                        <input type="date" value="{{ date('Y-m-d') }}" class="form-control text-center"
                                            name="date" placeholder="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">@lang('site.internalid')</label>

                                    <input type="text" class="form-control text-center" id="internalId"
                                        name="internalId" placeholder="@lang('site.internalid')">


                                </div>
                                <div class="col-2" style="margin-top: 27px">


                                    <button onClick="randomTest();" class="btn btn-info"
                                        type="button">@lang('site.Generate')</button>

                                </div>


                            </div>





                            <hr>
                            <div class="accordion" id="accordionExample" style="padding-top: 20px;">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="bankDetails">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">@lang("site.bank")
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="bankDetails" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">


                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">@lang("site.Bank Name")</label>
                                                    <input type="text" class="form-control form-control-sm text-center"
                                                        name="bankName" placeholder='@lang(" site.Bank Name")'>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">@lang("site.Bank Address")</label>
                                                    <input type="text" class="form-control form-control-sm text-center"
                                                        name="bankAddress" placeholder="@lang(" site.Bank Address")">
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label"> @lang("site.Bank Account No")</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="bankAccountNo" placeholder="@lang(" site.Bank Account
                                                            No")">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label"> @lang("site.Bank Account
                                                            IBAN")</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="bankAccountIBAN" placeholder="@lang(" site.Bank
                                                            Account IBAN")">

                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label"> @lang("site.Swift Code")</label>
                                                            <input type="text"
                                                                class="form-control form-control-sm text-center"
                                                                name="swiftCode" placeholder="@lang(" site.Swift
                                                                Code")">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label"> @lang("site.Payment
                                                                Terms")</label>
                                                            <input type="text"
                                                                class="form-control form-control-sm text-center"
                                                                name="Bankterms" placeholder="@lang(" site.Payment
                                                                Terms")">

                                                        </div>



                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <hr>









                        </div>

                    </div>


                </div>
                <hr style="border: 1px solid white;margin:50px 20px">
                <div class="form-body mt-4">
                    <div class="row">
                        <div class="col-lg-8" style="margin-top: -120px;">

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="lineDetails">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne"> @lang("site.Line Items")</button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="lineDetails" data-bs-parent="#accordionExample">
                                        <div class="accordion-body" id="newOne">
                                            <div class="row">



                                                <div class="border border-3 p-4 rounded">
                                                    <div class="row g-3">


                                                        <div class="mb-3 col-8">
                                                            <label for="inputProductTitle"
                                                                class="form-label">@lang("site.Line Item")</label>
                                                            <select name="itemCode[]" onchange="bigOne()" id="itemCode"
                                                                class="form-control form-control-sm form-select single-select"
                                                                required>
                                                                @foreach ($products as $product)
                                                                <option value="{{ $product['itemCode'] }}"
                                                                    style="font-size: 20px">
                                                                    {{ $product['codeNameSecondaryLang'] }}
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3 col-4">
                                                            <label for="inputProductTitle" class="form-label">وحـــدة
                                                                القيــاس</label>
                                                            <select name="unitType[]" required
                                                                class="form-control form-control-sm form-select single-select"
                                                                required>
                                                                <option value="EA">each</option>
                                                                @foreach ($unittypes as $unit)
                                                                <option value="{{ $unit->code}}"
                                                                    style="font-size: 20px">
                                                                    {{ $unit->desc_en }}
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="inputProductDescription"
                                                        class="form-label">@lang("site.Line Decription")</label>
                                                    <textarea name="invoiceDescription[]" required class="form-control"
                                                        id="inputProductDescription" rows="2"></textarea>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="linePrice"
                                                            class="form-label">@lang("site.price")</label>
                                                        <input class="form-control" step="any" type="number" step="any"
                                                            name="amountEGP[]" id="amountEGP" onkeyup="bigOne()"
                                                            onmouseover="bigOne()">
                                                    </div>
                                                    <div class=" col-md-6">
                                                        <label class="form-label">@lang("site.quantity")</label>
                                                        <input class="form-control" type="number" step="any"
                                                            name="quantity[]" id="quantity" onkeyup="bigOne()"
                                                            onmouseover="bigOne()">
                                                    </div>
                                                </div>
                                                <div class=" row g-3">
                                                    <div class="col-md-6">
                                                        <label for="inputProductTitle"
                                                            class="form-label">@lang("site.Tax added Type")</label>

                                                        <select name="t1subtype[]" required id="t1subtype"
                                                            class="form-control form-control-sm single-select">

                                                            @foreach ($taxTypes as $type)
                                                            @if ($type->parent === 'T2')
                                                            <option value="{{ $type->code }}"
                                                                style="font-size: 15px;width: 100px;">
                                                                {{ $type->name_ar }}
                                                                @endif
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="lineTaxAdd"
                                                            class="form-label">@lang("site.Tax_added")</label>
                                                        <input type="number" class="form-control" name="rate[]"
                                                            id="rate" class="form-control form-control-sm"
                                                            onkeyup="bigOne()" onmouseover="bigOne()"
                                                            placeholder="نسبة الضريبة">
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="inputProductTitle"
                                                            class="form-label">@lang("site.Tax t4 Type")</label>
                                                        <select name="t4subtype[]" required id="t4subtype"
                                                            class="form-control form-control-sm single-select">
                                                            <option value="W002" style="font-size: 15px;width: 100px;">
                                                                التوريدات</option>
                                                            @foreach ($taxTypes as $type)
                                                            @if ($type->parent === 'T4')
                                                            <option value="{{ $type->code }}"
                                                                style="font-size: 15px;width: 100px;">
                                                                {{ $type->name_ar }}
                                                                @endif
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="lineTaxT4" class="form-label">قيمة ضريبة
                                                            المنبع</label>
                                                        <input type="number" class="form-control" value="0" name="t4rate[]"
                                                            id="t4rate" onkeyup="bigOne()" onmouseover="bigOne()"
                                                            placeholder="قيمة ضريبة المنبع">
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="lineDiscount" class="form-label">الخصم</label>

                                                        <input class="form-control" value="0" placeholder=" @lang("
                                                            site.Discount")" type="number" step="any"
                                                            name="discountAmount[]" id="discountAmount"
                                                            onkeyup="bigOne()" onmouseover="bigOne()">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="lineDiscountAfterTax" class="form-label">خصم
                                                            الأصناف
                                                        </label>
                                                        <input type="number" class="form-control" value="0" step="any"
                                                            name="itemsDiscount[]" id="itemsDiscount" onkeyup="bigOne()"
                                                            onmouseover="bigOne()" placeholder="@lang("
                                                            site.Discount_After_Tax")">
                                                    </div>
                                                </div>
                                            </div></BR>
                                            <div class="border border-3 p-4 rounded">
                                                <div class="mb-3 text-center">
                                                    @lang("site.Line Total")
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="TotalTaxableFees" class="form-label">اجمالى
                                                                ضريبة القيمة المضافة</label>
                                                            <input type="number" readonly class="form-control"
                                                                step="any" name="t2Amount[]" id="t2" onkeyup="bigOne()"
                                                                onmouseover="bigOne()" placeholder="@lang(" site.Total
                                                                Taxable Fees")">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="Totalt4Amount" class="form-label">اجمالى
                                                                ضريبة المنبع</label>
                                                            <input type="number" class="form-control" name="t4Amount[]"
                                                                readonly id="t4Amount" onkeyup="bigOne()bigOne()"
                                                                onmouseover="bigOne()bigOne()" placeholder="@lang("
                                                                site.Total T4 Amount")">
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="Subtotal" class="form-label">إجمالى المبيعات</label>
                                                            <input type="number" class="form-control"
                                                                name="salesTotal[]" readonly id="salesTotal"
                                                                placeholder="إجمالى المبيعات">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="NetTotal" class="form-label">إجمالى مبلغ المبيعات</label>
                                                            <input type="number" class="form-control" readonly
                                                                name="netTotal[]" id="netTotal" onkeyup="bigOne()"
                                                                onmouseover="bigOne()" placeholder="إجمالى مبلغ المبيعات">
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">

                                                        <div class="col-md-12">
                                                            <label for="lineTotal"
                                                                class="form-label">@lang("site.lineTotal")</label>
                                                            <input type="number" class="form-control"
                                                                name="totalItemsDiscount[]" readonly
                                                                id="totalItemsDiscount" onkeyup="bigOne()"
                                                                onmouseover="bigOne()" placeholder="@lang("
                                                                site.lineTotal")">
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div style="z-index:1;text-align: center">
                                            <button id="addNewOne" type="button" class="btn btn-info"
                                                style="width: 200px">@lang("site.add_name")</button>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>







                        <div class="col-lg-4" style="margin-top: -120px">
                            <div class="border border-3 p-4 rounded">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="findTotalt2Amount" class="form-label">إجمالى ضريبة القيمة
                                            المضافة</label>
                                        <input type="number" class="form-control" step="any" name="totalt2Amount"
                                            onmouseover="bigOne()" onkeyup="bigOne()" readonly id="totalt2Amount">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="findTotalt4Amount" class="form-label">إجمالى ضريبة
                                            المنبع</label>
                                        <input class="form-control" type="number" step="any" name="totalt4Amount"
                                            onmouseover="bigOne()" onkeyup="bigOne()" readonly id="totalt4Amount">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="salesTotal" class="form-label">إجمالى الخصم</label>
                                        <input type="number" class="form-control" name="totalDiscountAmount"
                                            onmouseover="bigOne()" onkeyup="bigOne()" readonly id="totalDiscountAmount">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="netTotal" class="form-label">الإجمالى الصافى</label>
                                        <input type="number" class="form-control" step="any" name="TotalSalesAmount"
                                            onmouseover="bigOne()" onkeyup="bigOne()" readonly id="TotalSalesAmount">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="findTotalNetAmount" class="form-label">إجمالى المبلغ
                                            الصافى</label>
                                        <input type="number" step="any" class="form-control" name="TotalNetAmount"
                                            onmouseover="bigOne()" onkeyup="bigOne()" readonly id="TotalNetAmount">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="TotalDiscount" class="form-label">إجمالى خصم الإصناف</label>
                                        <input type="number" step="any" name="totalItemsDiscountAmount"
                                            class="form-control" onmouseover="bigOne()" onkeyup="bigOne()" readonly
                                            id="totalItemsDiscountAmount">
                                    </div>


                                    <div class="col-12">
                                        <label for="ExtraInvoiceDiscount" class="form-label">الخصم الإضافى (مابعد
                                            الضريبة) </label>
                                        <input type="number" class="form-control" step="any" name="ExtraDiscount"
                                            id="ExtraDiscount" value="0" onkeyup="bigOne()" onmouseover="bigOne()"
                                            required>
                                    </div>


                                    <div class="col-12">
                                        <label for="findTotalAmount" class="form-label">الإجمالى قبل الخصم
                                        </label>
                                        <input class="form-control" type="number" step="any" name="totalAmount" readonly
                                            id="totalAmount">
                                    </div>


                                    <div class="col-12">
                                        <label for="findTotalAmount" class="form-label">الإجمالى بعد الخصم
                                        </label>
                                        <input type="number" class="form-control"
                                            style="color: red;font-weight: bold;font-size: 20px" type="number"
                                            step="any" name="totalAmount2" readonly id="totalAmount2">
                                    </div>
                                    <div class="col-12">
                                        <div class="row">

                                            <div class="d-grid col-6">
                                                <button type="submit" class="btn btn-primary" name="action"
                                                    class="col-6" value="test" id="sendNewInv"
                                                    formaction="{{ route('storeInvoice') }}">إرسال الفاتورة</button>
                                                <button disabled style="display: none" class="btn btn-primary"
                                                    id="disabledButton">جارى الإرسال...</button>
                                            </div>
                                            <div class="d-grid col-6">
                                                <button type="submit" class="btn btn-primary" name="action"
                                                    id="saveDraftInvoice" class="col-6" value="draft"
                                                    formaction="{{ route('draft') }}">حفظ كمسودة</button>
                                                <button disabled style="display: none" class="btn btn-primary"
                                                    id="disabledButton2">جارى الحفظ...</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    @endsection







    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            let i = 1
            $("#addNewOne").click(function() {
                i++;
                $('#newOne').append(
                    `<div id="row${i}">
                                <button type="button" style="margin-right:30px" name="remove" id="${i}"  class="btn btn-danger btn_remove">x</button>
                               <div class="border border-3 p-4 rounded">
                                 <div class="row g-3">
                             <div class="mb-3 col-8">
                                <label for="inputProductTitle"
                                    class="form-label">@lang("site.Line Item")</label>
                                <select name="itemCode[]" onchange="bigOne${i}()" id="itemCode${i}"
                                                            class="form-control form-control-sm form-select single-select" required>
                                                            @foreach ($products as $product)
                                                               <option value="{{ $product['itemCode'] }}"
                                                                    style="font-size: 20px">
                                                                    {{ $product['codeNameSecondaryLang'] }}
                                                            @endforeach
                                                        </select>
                             </div>
                              <div class="mb-3 col-4">
                                                            <label for="inputProductTitle" class="form-label">وحـــدة
                                                                القيــاس</label>
                                                            <select name="unitType[]" required
                                                                class="form-control form-control-sm form-select single-select"
                                                                required>
                                                                <option value="EA">each</option>
                                                                @foreach ($unittypes as $unit)
                                                                <option value="{{ $unit->code}}"
                                                                    style="font-size: 20px">
                                                                    {{ $unit->desc_en }}
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                <div class="mb-3">
                                    <label for="inputProductDescription" class="form-label">@lang("site.Line Decription") ${i}</label>
                                    <textarea name="invoiceDescription[]" required class="form-control" id="inputProductDescription${i}" rows="2"></textarea>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="linePrice" class="form-label">@lang("site.price")</label>
                                        <input class="form-control" step="any" type="number" step="any" name="amountEGP[]" id="amountEGP${i}"
                                            onkeyup="bigOne${i}(),bigOne();"
                                            onmouseover="bigOne${i}(),bigOne();">
                                    </div>
                                    <div class=" col-md-6">
                                        <label class="form-label">@lang("site.quantity")</label>
                                        <input class="form-control" type="number" step="any" name="quantity[]" id="quantity${i}"
                                            onkeyup="bigOne${i}(),bigOne();"
                                            onmouseover="bigOne${i}(),bigOne();">
                                    </div>
                                </div>
                                <div class=" row g-3">
                                    <div class="col-md-6">
                                        <label for="inputProductTitle" class="form-label">@lang("site.Tax added Type")</label>
                                        <select name="t1subtype[]" required id="t1subtype" class="form-control form-control-sm single-select">
                                            @foreach ($taxTypes as $type)
                                                @if ($type->parent === 'T2')
                                                    <option value="{{ $type->code }}" style="font-size: 15px;width: 100px;">
                                                        {{ $type->name_ar }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lineTaxAdd" class="form-label">@lang("site.Tax_added")</label>
                                        <input type="number" class="form-control" name="rate[]" id="rate${i}" class="form-control form-control-sm"
                                            onkeyup="bigOne${i}(),bigOne();" onmouseover="bigOne${i}(),bigOne();" placeholder="@lang("site.Tax_added")">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="inputProductTitle" class="form-label">@lang("site.Tax t4 Type")</label>
                                        <select name="t4subtype[]" required id="t4subtype" class="form-control form-control-sm single-select">
                                            <option value="W002" style="font-size: 15px;width: 100px;">التوريدات</option>
                                            @foreach ($taxTypes as $type)
                                                @if ($type->parent === 'T4')
                                                    <option value="{{ $type->code }}" style="font-size: 15px;width: 100px;">
                                                        {{ $type->name_ar }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lineTaxT4" class="form-label">قيمة ضريبة المنبع</label>
                                        <input type="number" class="form-control" value="0" name="t4rate[]" id="t4rate${i}" onkeyup="bigOne${i}(),bigOne();"
                                            onmouseover="bigOne${i}(),bigOne();" placeholder="@lang("site.Tax t4 Value")">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="lineDiscount" class="form-label">الخصم</label>
                                        <input class="form-control" value="0" placeholder="الخصم" type="number" step="any"
                                            name="discountAmount[]" id="discountAmount${i}"
                                            onkeyup="bigOne${i}(),bigOne();"
                                            onmouseover="bigOne${i}(),bigOne();">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lineDiscountAfterTax" class="form-label">خصم الأصناف </label>
                                        <input type="number" class="form-control" step="any" name="itemsDiscount[]" id="itemsDiscount${i}"
                                            onkeyup="bigOne${i}(),bigOne();"
                                            onmouseover="bigOne${i}(),bigOne();" value="0"
                                            placeholder="خصم الأصناف">
                                    </div>
                                </div>
                            </div></BR>
                            <div class="border border-3 p-4 rounded">
                                <div class="mb-3">
                                    @lang("site.Line Total")
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="TotalTaxableFees" class="form-label">@lang("site.Total Taxable Fees")</label>
                                            <input type="number" readonly class="form-control" step="any" name="t2Amount[]" id="t2${i}"
                                                onkeyup="bigOne${i}(),bigOne();" onmouseove="bigOne${i}(),bigOne();" placeholder="@lang(" site.Total
                                                Taxable Fees")">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Totalt4Amount" class="form-label">@lang("site.Totalt4Amount")</label>
                                            <input type="number" class="form-control" name="t4Amount[]" readonly id="t4Amount${i}"
                                                onkeyup="bigOne${i}(),bigOne();" onmouseover="bigOne${i}(),bigOne();" placeholder="@lang("site.Totalt4Amount")">
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="Subtotal" class="form-label">@lang("site.Sub total")</label>
                                            <input type="number" class="form-control" name="salesTotal[]" readonly id="salesTotal${i}"
                                                placeholder="@lang("site.Sub total")">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="NetTotal" class="form-label">@lang("site.Net Total")</label>
                                            <input type="number" class="form-control" readonly name="netTotal[]" id="netTotal${i}"
                                                onkeyup="bigOne${i}(),bigOne();"
                                                onmouseover="bigOne${i}(),bigOne();" placeholder="@lang("site.Net Total")">
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="lineTotal" class="form-label">@lang("site.lineTotal")</label>
                                            <input type="number" class="form-control" name="totalItemsDiscount[]" readonly id="totalItemsDiscount${i}"
                                                onkeyup="bigOne${i}(),bigOne();" onmouseover="bigOne${i}(),bigOne();" placeholder="@lang("site.lineTotal")">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div> `
                );
                $('.single-select').select2();
                $('<script> function bigOne' + i +
                        '(){var quantity = document.getElementById("quantity' + i +
                        '");var amounEGP = document.getElementById("amountEGP' + i +
                        '");var salesTotal = document.getElementById("salesTotal' + i +
                        '");var netTotal = document.getElementById("netTotal' + i +
                        '");var discount = document.getElementById("discountAmount' + i +
                        '");var T2rate = document.getElementById("rate' + i +
                        '");var t2valueEnd = document.getElementById("t2' + i +
                        '");var T4ValueEnd = document.getElementById("t4rate' + i +
                        '");var t4Amount = document.getElementById("t4Amount' + i +
                        '");var totalItemDiscount = document.getElementById("totalItemsDiscount' + i +
                        '");var itemsDiscount = document.getElementById("itemsDiscount' + i +
                        '");var allSalesTotal =  salesTotal.value = (quantity' + i +
                        '.value * amounEGP.value).toFixed(2);var allNetTotal = netTotal.value = (salesTotal.value - discount.value).toFixed(2);t2valueEnd.value = ((allNetTotal* T2rate.value) / 100).toFixed(2);t4Amount.value = ((allNetTotal* T4ValueEnd.value) / 100).toFixed(2);totalItemDiscount.value = (parseFloat(netTotal.value) + parseFloat(t2valueEnd.value) - parseFloat(t4Amount.value) - parseFloat(itemsDiscount.value)).toFixed(2);};  </' + 'script>').appendTo('#test123');
                $(document).on('click', '.btn_remove', function() {
                    var button_id = $(this).attr("id");
                    $("#row" + button_id + "").remove()
                   // findTotalDiscountAmount();
                   // findTotalSalesAmount();
                   // findTotalNetAmount();
                   // findTotalt4Amount();
                   // findTotalt2Amount();
                   // findTotalAmount();
                   // findTotalItemsDiscountAmount();
                   bigOne()
                })
            });
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




    <script id="test123">
        function bigOne() {

            //for itemData

            var quantity = document.getElementById("quantity");
            var amounEGP = document.getElementById("amountEGP");
            var salesTotal = document.getElementById("salesTotal");
            var netTotal = document.getElementById("netTotal");
            var discount = document.getElementById('discountAmount');
            var T2rate = document.getElementById("rate");
            var t2valueEnd = document.getElementById("t2");
            var T4rate = document.getElementById("t4rate");
            var t4Amount = document.getElementById("t4Amount");
            var totalItemDiscount = document.getElementById("totalItemsDiscount");
            var itemsDiscount = document.getElementById("itemsDiscount");


            var allSalesTotal = salesTotal.value = (quantity.value * amounEGP.value).toFixed(2);
            var allNetTotal = netTotal.value = (salesTotal.value - discount.value).toFixed(2);
            t2valueEnd.value = ((allNetTotal * T2rate.value) / 100).toFixed(2);
            t4Amount.value = ((allNetTotal * t4rate.value) / 100).toFixed(2);

            totalItemDiscount.value = (parseFloat(netTotal.value) + parseFloat(t2valueEnd.value) - parseFloat(t4Amount.value) - parseFloat(itemsDiscount.value)).toFixed(2)





            // for total discount amount
            var discountAmount = document.getElementsByName("discountAmount[]");
            var tot1 = 0;
            for (var i = 0; i < discountAmount.length; i++) {
                if (parseFloat(discountAmount[i].value)) {
                    tot1 += parseFloat(discountAmount[i].value);
                }
            }
            document.getElementById("totalDiscountAmount").value = tot1.toFixed(2);



            // for total sales amount

            var TotalSalesAmount = document.getElementsByName("salesTotal[]");
            var tot2 = 0;
            for (var i = 0; i < TotalSalesAmount.length; i++) {
                if (parseFloat(TotalSalesAmount[i].value)) {
                    tot2 += parseFloat(TotalSalesAmount[i].value);
                }
            }
            document.getElementById("TotalSalesAmount").value = tot2.toFixed(2);

            // find net total

            var AllNetTotal = document.getElementsByName("netTotal[]");
            var tot3 = 0;
            for (var i = 0; i < AllNetTotal.length; i++) {
                if (parseFloat(AllNetTotal[i].value)) {
                    tot3 += parseFloat(AllNetTotal[i].value);
                }
            }
            document.getElementById("TotalNetAmount").value = tot3.toFixed(2);

            // all t4 amount

            var Allt4Amount = document.getElementsByName("t4Amount[]");
            var tot4 = 0;
            for (var i = 0; i < Allt4Amount.length; i++) {
                if (parseFloat(Allt4Amount[i].value)) {
                    tot4 += parseFloat(Allt4Amount[i].value);
                }
            }
            document.getElementById("totalt4Amount").value = tot4.toFixed(2);


            // find total t2amount
            var Allt2Amount = document.getElementsByName("t2Amount[]");
            var tot5 = 0;
            for (var i = 0; i < Allt2Amount.length; i++) {
                if (parseFloat(Allt2Amount[i].value)) {
                    tot5 += parseFloat(Allt2Amount[i].value);
                }
            }
            document.getElementById("totalt2Amount").value = tot5.toFixed(2);


            // findt total Amount

            var allTotalItemsDiscount = document.getElementsByName("totalItemsDiscount[]");
            var tot6 = 0;
            for (var i = 0; i < allTotalItemsDiscount.length; i++) {
                if (parseFloat(allTotalItemsDiscount[i].value)) {
                    tot6 += parseFloat(allTotalItemsDiscount[i].value);
                }
            }
            document.getElementById("totalAmount").value = tot6.toFixed(2);



            // find total items discount
            var totalItemsDisc = document.getElementsByName("itemsDiscount[]");
            var tot7 = 0;
            for (var i = 0; i < totalItemsDisc.length; i++) {
                if (parseFloat(totalItemsDisc[i].value)) {
                    tot7 += parseFloat(totalItemsDisc[i].value);
                }
            }
            document.getElementById("totalItemsDiscountAmount").value = tot7.toFixed(2);

            //extra discount

            var ExtraDiscount = document.getElementById('ExtraDiscount');
            var totalAmountOfDiscount = document.getElementById("totalAmount");

            document.getElementById("totalAmount2").value = (totalAmountOfDiscount.value - ExtraDiscount.value).toFixed(2);
            //var DescFill = document.getElementById("itemCode");
            //var FinalText = DescFill.options[DescFill.selectedIndex].text;
            //document.getElementById("inputProductDescription").innerHTML = FinalText;

        }




        // this is invoice 1
     //   function operation(value) {
    //        var x, y, z;
    //        var quantity = document.getElementById("quantity").value;
    //        x = value * quantity;
    //        document.getElementById("salesTotal").value = x.toFixed(5);
   //     };

   //     function proccess(value) {
   //         var x, y, z;
   //         var amounEGP = document.getElementById("amountEGP").value;
   //         y = value * amounEGP;
   //         document.getElementById("salesTotal").value = y.toFixed(5);
   //     };

     //   function discount(value) {
     //       var salesTotal, netTotal, z, t2valueEnd, t1Value, rate, t4rate, t4Amount;
     //       salesTotal = document.getElementById("salesTotal").value;
     //       netTotal = salesTotal - value;
     //       netTotalEnd = document.getElementById("netTotal").value = netTotal.toFixed(5);
    //        rate = document.getElementById("rate").value;
     //       t4rate = document.getElementById("t4rate").value;
       //     t2valueEnd = document.getElementById("t2").value =
    //            ((netTotalEnd * rate) / 100).toFixed(5);
    //        t4Amount = document.getElementById("t4Amount").value =
     //           ((netTotal * t4rate) / 100).toFixed(5);
     //   }

      //  function itemsDiscountValue(value) {
      //      var x, netTotal, t1amount, t2amount, t4Amount;
      //      netTotal = document.getElementById("netTotal").value;
      //      t2amount = document.getElementById("t2").value;
      //      t4Amount = document.getElementById("t4Amount").value;
      //      x =
      //          parseFloat(netTotal) +
      //          parseFloat(t2amount) -
      //          parseFloat(t4Amount) -
      //          parseFloat(value);
      //      document.getElementById("totalItemsDiscount").value = x.toFixed(5);
      //  }

       // function Extradiscount(value) {
       //     var totalDiscount, x;
       //     totalDiscount = document.getElementById("totalAmount").value;
       //     x = totalDiscount - value;
       //     document.getElementById("totalAmount2").value = x.toFixed(5);
       // }

      //  function findTotalDiscountAmount() {
      //      var arr = document.getElementsByName("discountAmount[]");
      //      var tot = 0;
      //      for (var i = 0; i < arr.length; i++) {
      //          if (parseFloat(arr[i].value)) {
      //              tot += parseFloat(arr[i].value);
      //          }
      //      }
      //      document.getElementById("totalDiscountAmount").value = tot.toFixed(5);
      //  }

       // function findTotalSalesAmount() {
       //     var arr = document.getElementsByName("salesTotal[]");
       //     var tot = 0;
       //     for (var i = 0; i < arr.length; i++) {
       //         if (parseFloat(arr[i].value)) {
       //             tot += parseFloat(arr[i].value);
       //         }
       //     }
       //     document.getElementById("TotalSalesAmount").value = tot.toFixed(5);
       // }

       // function findTotalNetAmount() {
       //     var arr = document.getElementsByName("netTotal[]");
       //     var tot = 0;
       //     for (var i = 0; i < arr.length; i++) {
       //         if (parseFloat(arr[i].value)) {
       //             tot += parseFloat(arr[i].value);
       //         }
       //     }
       //     document.getElementById("TotalNetAmount").value = tot.toFixed(5);
       // }

    //    function findTotalt4Amount() {
    //        var arr = document.getElementsByName("t4Amount[]");
    //        var tot = 0;
    //        for (var i = 0; i < arr.length; i++) {
    //            if (parseFloat(arr[i].value)) {
    //                tot += parseFloat(arr[i].value);
    //            }
    //        }
    //        document.getElementById("totalt4Amount").value = tot.toFixed(5);
    //    }

    //    function findTotalt2Amount() {
    //        var arr = document.getElementsByName("t2Amount[]");
    //        var tot = 0;
    //        for (var i = 0; i < arr.length; i++) {
    //            if (parseFloat(arr[i].value)) {
    //                tot += parseFloat(arr[i].value);
    //            }
    //        }
    //        document.getElementById("totalt2Amount").value = tot.toFixed(5);
    //    }

    //    function findTotalAmount() {
    //        var arr = document.getElementsByName("totalItemsDiscount[]");
    //        var tot = 0;
    //        for (var i = 0; i < arr.length; i++) {
    //            if (parseFloat(arr[i].value)) {
    //                tot += parseFloat(arr[i].value);
    //            }
    //        }
    //        document.getElementById("totalAmount").value = tot.toFixed(5);
    //    }

     //   function findTotalItemsDiscountAmount() {
     //       var arr = document.getElementsByName("itemsDiscount[]");
     //       var tot = 0;
     //       for (var i = 0; i < arr.length; i++) {
     //           if (parseFloat(arr[i].value)) {
     //               tot += parseFloat(arr[i].value);
     //           }
     //       }
     //       document.getElementById("totalItemsDiscountAmount").value = tot.toFixed(5);
     //   }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
        $(document).on('change', '#receiverName',function(){
             var select = $(this).val();
                 $.ajax({
      url: `{{URL::to('getcompany/${select}')}}`,
      type: "GET",
      dataType: "json",
      // data: {
      //   select: select,
      // },
      success: function (data) {
        $("#getReceiverName").val(data.name);
        $("#getReceiverId").val(data.tax_id);
        $("#getReceiverCountry").val(data.country);
        $("#getReceiverGovernate").val(data.governate);
         $("#getReceiverRegionCity").val(data.regionCity);
        $("#getStreet").val(data.street);
        $("#getBuildingNumber").val(data.buildingNumber);
        $("#getPostalCode").val(data.postalCode);
        $("#getFloor").val(data.floor);
        $("#getRoom").val(data.room);
        $("#getLandMark").val(data.landmark);
        $("#getAdditional").val(data.additionalInformation);
      },
      error: function () {
        console.log("error in request");
      },
    });
        });

    });
    </script>

    <script>
        $(document).on('click', '#sendNewInv', function() {
    var button = $(this);
   button.hide();
   $("#disabledButton").show()
   $("#saveDraftInvoice").hide()
   setTimeout(function() {
       button.show();
       $("#disabledButton").hide()
       $("#saveDraftInvoice").show()
    },10000);

});
 $(document).on('click', '#saveDraftInvoice', function() {
    var button = $(this);
   button.hide();
   $("#disabledButton2").show()
   $("#sendNewInv").hide()
   setTimeout(function() {
       button.show();
       $("#disabledButton2").hide()
       $("#sendNewInv").show()
    },10000);

});

    </script>
