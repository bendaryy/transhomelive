<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Models\DraftInvoice;
use App\Models\SentInvoices;

class manageDoucumentController extends Controller
{
    public $url1 = "https://id.eta.gov.eg";
    public $url2 = "https://api.invoicing.eta.gov.eg";
    // this is for show sent inovices
    public function sentInvoices($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        $taxId = auth()->user()->details->company_id;

        return view('invoices.sentInvoices', compact('allInvoices', 'allMeta', 'taxId', 'id'));
    }

    // this is for show recieved inovices

    public function receivedInvoices($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        $taxId = auth()->user()->details->company_id;

        return view('invoices.receivedInvoices', compact('allInvoices', 'allMeta', 'taxId', 'id'));
    }

    public function invoiceDollar(Request $request)
    {

        $validated = $request->validate([
            // 'receiverCountry' => 'required',
            // 'receiverCountry' => 'required',
            // 'receiverGovernate' => 'required',
            // 'receiverRegionCity' => 'required',
            'receiverType' => 'required',
            // 'receiverId' => 'required',
            // 'receiverName' => 'required',
            'DocumentType' => 'required',
            'date' => 'required',
            'taxpayerActivityCode' => 'required',
            'internalId' => 'required',
            'ExtraDiscount' => 'required',
            'rate' => 'required',
            'invoiceDescription' => 'required',
            'itemCode' => 'required',
            't4subtype' => 'required',
            't1subtype' => 'required',

        ]);

        $invoice =
            [
            "issuer" => array(
                "address" => array(
                    "branchID" => "0",
                    "country" => "EG",
                    "governate" => auth()->user()->details->governate,
                    "regionCity" => auth()->user()->details->regionCity,
                    "street" => auth()->user()->details->street,
                    "buildingNumber" => auth()->user()->details->buildingNumber,
                ),
                "type" => auth()->user()->details->issuerType,
                "id" => auth()->user()->details->company_id,
                "name" => auth()->user()->details->company_name,
            ),

            "receiver" => array(
                "address" => array(

                ),
                "type" => $request->receiverType,

            ),
            "documentType" => $request->DocumentType,
            "documentTypeVersion" => "1.0",
            "dateTimeIssued" => $request->date . "T" . date("h:i:s") . "Z",
            "taxpayerActivityCode" => $request->taxpayerActivityCode,
            "internalID" => $request->internalId,
            "invoiceLines" => [

            ],
            "totalDiscountAmount" => floatval($request->totalDiscountAmount),
            "totalSalesAmount" => floatval($request->TotalSalesAmount),
            "netAmount" => floatval($request->TotalNetAmount),
            "taxTotals" => array(
                array(
                    "taxType" => "T4",
                    "amount" => floatval($request->totalt4Amount),
                ),
                array(
                    "taxType" => "T2",
                    "amount" => floatval($request->totalt2Amount),
                ),
            ),
            "totalAmount" => floatval($request->totalAmount2),
            "extraDiscountAmount" => floatval($request->ExtraDiscount),
            "totalItemsDiscountAmount" => floatval($request->totalItemsDiscountAmount),
        ];

        for ($i = 0; $i < count($request->quantity); $i++) {
            $Data = [
                "description" => $request->invoiceDescription[$i],
                "itemType" => "EGS",
                "itemCode" => $request->itemCode[$i],
                // "itemCode" => "10003834",
                "unitType" => "EA",
                "quantity" => floatval($request->quantity[$i]),
                "internalCode" => "100",
                "salesTotal" => floatval($request->salesTotal[$i]),
                "total" => floatval($request->totalItemsDiscount[$i]),
                "valueDifference" => 0.00,
                "totalTaxableFees" => 0.00,
                "netTotal" => floatval($request->netTotal[$i]),
                "itemsDiscount" => floatval($request->itemsDiscount[$i]),

                "unitValue" => [
                    "currencySold" => "USD",
                    "amountSold" => floatval($request->amountSold[$i]),
                    "currencyExchangeRate" => floatval($request->currencyExchangeRate[$i]),
                    "amountEGP" => floatval($request->amountEGP[$i]),
                ],
                "discount" => [
                    "rate" => 0.00,
                    "amount" => floatval($request->discountAmount[$i]),
                ],
                "taxableItems" => [
                    [

                        "taxType" => "T4",
                        "amount" => floatval($request->t4Amount[$i]),
                        "subType" => ($request->t4subtype[$i]),
                        "rate" => floatval($request->t4rate[$i]),
                    ],
                    [
                        "taxType" => "T2",
                        "amount" => floatval($request->t2Amount[$i]),
                        "subType" => ($request->t1subtype[$i]),
                        "rate" => floatval($request->rate[$i]),
                    ],
                ],

            ];
            $invoice['invoiceLines'][$i] = $Data;
        }

// this is for receiver address
        ($request->receiverName ? $invoice['receiver']['name'] = $request->receiverName : "");
        ($request->receiverCountry ? $invoice['receiver']["address"]['country'] = $request->receiverCountry : "");
        ($request->receiverBuildingNumber ? $invoice['receiver']["address"]['buildingNumber'] = $request->receiverBuildingNumber : "");
        ($request->street ? $invoice['receiver']["address"]['street'] = $request->street : "");
        ($request->receiverRegionCity ? $invoice['receiver']["address"]['regionCity'] = $request->receiverRegionCity : "");
        ($request->receiverGovernate ? $invoice['receiver']["address"]['governate'] = $request->receiverGovernate : "");
        ($request->receiverPostalCode ? $invoice['receiver']["address"]['postalcode'] = $request->receiverPostalCode : "");
        ($request->receiverFloor ? $invoice['receiver']["address"]['floor'] = $request->receiverFloor : "");
        ($request->receiverRoom ? $invoice['receiver']["address"]['room'] = $request->receiverRoom : "");
        ($request->receiverLandmark ? $invoice['receiver']["address"]['landmark'] = $request->receiverLandmark : "");
        ($request->receiverAdditionalInformation ? $invoice['receiver']["address"]['additionalInformation'] = $request->receiverAdditionalInformation : "");
        ($request->receiverId ? $invoice['receiver']['id'] = $request->receiverId : "");

        // this is for reference debit or credit note
        ($request->referencesInvoice ? $invoice['references'] = [$request->referencesInvoice] : "");
        // End reference debit or credit note

        // this is for Bank payment

        ($request->bankName ? $invoice['payment']["bankName"] = $request->bankName : "");
        ($request->bankAddress ? $invoice['payment']["bankAddress"] = $request->bankAddress : "");
        ($request->bankAccountNo ? $invoice['payment']["bankAccountNo"] = $request->bankAccountNo : "");
        ($request->bankAccountIBAN ? $invoice['payment']["bankAccountIBAN"] = $request->bankAccountIBAN : "");
        ($request->swiftCode ? $invoice['payment']["swiftCode"] = $request->swiftCode : "");
        ($request->Bankterms ? $invoice['payment']["terms"] = $request->Bankterms : "");
        // End Bank payment

        $trnsformed = json_encode($invoice, JSON_UNESCAPED_UNICODE);
        $myFileToJson = fopen('C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json', "w") or die("unable to open file");
        fwrite($myFileToJson, $trnsformed);
        return redirect()->route('cer');

    }
    public function invoice(Request $request)
    {

        $validated = $request->validate([
            // 'receiverCountry' => 'required',
            // 'receiverCountry' => 'required',
            // 'receiverGovernate' => 'required',
            // 'receiverRegionCity' => 'required',
            'receiverType' => 'required',
            // 'receiverId' => 'required',
            // 'receiverName' => 'required',
            'DocumentType' => 'required',
            'date' => 'required',
            'taxpayerActivityCode' => 'required',
            'internalId' => 'required',
            'ExtraDiscount' => 'required',
            'rate' => 'required',
            'invoiceDescription' => 'required',
            'itemCode' => 'required',
            't4subtype' => 'required',
            't1subtype' => 'required',

        ]);

        $invoice =
            [
            "issuer" => array(
                "address" => array(
                    "branchID" => "0",
                    "country" => "EG",
                    "governate" => auth()->user()->details->governate,
                    "regionCity" => auth()->user()->details->regionCity,
                    "street" => auth()->user()->details->street,
                    "buildingNumber" => auth()->user()->details->buildingNumber,
                ),
                "type" => auth()->user()->details->issuerType,
                "id" => auth()->user()->details->company_id,
                "name" => auth()->user()->details->company_name,
            ),

            "receiver" => array(
                "address" => array(

                ),
                "type" => $request->receiverType,

            ),
            "documentType" => $request->DocumentType,
            "documentTypeVersion" => "1.0",
            "dateTimeIssued" => $request->date . "T" . date("h:i:s") . "Z",
            "taxpayerActivityCode" => $request->taxpayerActivityCode,
            "internalID" => $request->internalId,
            "invoiceLines" => [

            ],
            "totalDiscountAmount" => floatval($request->totalDiscountAmount),
            "totalSalesAmount" => floatval($request->TotalSalesAmount),
            "netAmount" => floatval($request->TotalNetAmount),
            "taxTotals" => array(
                // array(
                //     "taxType" => "T4",
                //     "amount" => floatval($request->totalt4Amount),
                // ),
                // array(
                //     "taxType" => "T1",
                //     "amount" => floatval($request->totalt2Amount),
                // ),
            ),
            "totalAmount" => floatval($request->totalAmount2),
            "extraDiscountAmount" => floatval($request->ExtraDiscount),
            "totalItemsDiscountAmount" => floatval($request->totalItemsDiscountAmount),
        ];

        for ($i = 0; $i < count($request->quantity); $i++) {
            $Data = [
                "description" => $request->invoiceDescription[$i],
                "itemType" => "EGS",
                "itemCode" => $request->itemCode[$i],
                // "itemCode" => "10003834",
                "unitType" => $request->unitType[$i],
                "quantity" => floatval($request->quantity[$i]),
                "internalCode" => "100",
                "salesTotal" => floatval($request->salesTotal[$i]),
                "total" => floatval($request->totalItemsDiscount[$i]),
                "valueDifference" => 0.00,
                "totalTaxableFees" => 0.00,
                "netTotal" => floatval($request->netTotal[$i]),
                "itemsDiscount" => floatval($request->itemsDiscount[$i]),

                "unitValue" => [
                    "currencySold" => "EGP",
                    "amountSold" => 0.00,
                    "currencyExchangeRate" => 0.00,
                    "amountEGP" => floatval($request->amountEGP[$i]),
                ],
                "discount" => [
                    "rate" => 0.00,
                    "amount" => floatval($request->discountAmount[$i]),
                ],
                "taxableItems" => [
                    // [

                    //     "taxType" => "T4",
                    //     "amount" => floatval($request->t4Amount[$i]),
                    //     "subType" => ($request->t4subtype[$i]),
                    //     "rate" => floatval($request->t4rate[$i]),
                    // ],
                    // [
                    //     "taxType" => "T1",
                    //     "amount" => floatval($request->t2Amount[$i]),
                    //     "subType" => ($request->t1subtype[$i]),
                    //     "rate" => floatval($request->rate[$i]),
                    // ],
                ],

            ];
             if (floatval($request->t4rate[$i]) > 0) {
                $newArray = [

                    "taxType" => "T4",
                    "amount" => floatval($request->t4Amount[$i]),
                    "subType" => ($request->t4subtype[$i]),
                    "rate" => floatval($request->t4rate[$i]),
                ];

                array_push($Data['taxableItems'], $newArray);

            }

            if (floatval($request->rate[$i]) > 0) {
                $newArray2 = [
                    "taxType" => "T2",
                    "amount" => floatval($request->t2Amount[$i]),
                    "subType" => ($request->t1subtype[$i]),
                    "rate" => floatval($request->rate[$i]),
                ];
                array_push($Data['taxableItems'], $newArray2);

            }

            // send data to invoiceLines
            $invoice['invoiceLines'][$i] = $Data;
        }

        // this is for receiver address
        ($request->receiverName ? $invoice['receiver']['name'] = $request->receiverName : "");
        ($request->receiverCountry ? $invoice['receiver']["address"]['country'] = $request->receiverCountry : "");
        ($request->receiverBuildingNumber ? $invoice['receiver']["address"]['buildingNumber'] = $request->receiverBuildingNumber : "");
        ($request->street ? $invoice['receiver']["address"]['street'] = $request->street : "");
        ($request->receiverRegionCity ? $invoice['receiver']["address"]['regionCity'] = $request->receiverRegionCity : "");
        ($request->receiverGovernate ? $invoice['receiver']["address"]['governate'] = $request->receiverGovernate : "");
        ($request->receiverPostalCode ? $invoice['receiver']["address"]['postalcode'] = $request->receiverPostalCode : "");
        ($request->receiverFloor ? $invoice['receiver']["address"]['floor'] = $request->receiverFloor : "");
        ($request->receiverRoom ? $invoice['receiver']["address"]['room'] = $request->receiverRoom : "");
        ($request->receiverLandmark ? $invoice['receiver']["address"]['landmark'] = $request->receiverLandmark : "");
        ($request->receiverAdditionalInformation ? $invoice['receiver']["address"]['additionalInformation'] = $request->receiverAdditionalInformation : "");
        ($request->receiverId ? $invoice['receiver']['id'] = $request->receiverId : "");

        // this is for reference debit or credit note
        ($request->referencesInvoice ? $invoice['references'] = [$request->referencesInvoice] : "");
        // End reference debit or credit note



         if (floatval($request->totalt4Amount) > 0) {
            $newArray = [
                "taxType" => "T4",
                "amount" => floatval($request->totalt4Amount),
            ];
            array_push($invoice['taxTotals'], $newArray);
        }
        if (floatval($request->totalt2Amount) > 0) {
            $newArray = [
                "taxType" => "T2",
                "amount" => floatval($request->totalt2Amount),
            ];
            array_push($invoice['taxTotals'], $newArray);
        }

        // this is for Bank payment

        ($request->bankName ? $invoice['payment']["bankName"] = $request->bankName : "");
        ($request->bankAddress ? $invoice['payment']["bankAddress"] = $request->bankAddress : "");
        ($request->bankAccountNo ? $invoice['payment']["bankAccountNo"] = $request->bankAccountNo : "");
        ($request->bankAccountIBAN ? $invoice['payment']["bankAccountIBAN"] = $request->bankAccountIBAN : "");
        ($request->swiftCode ? $invoice['payment']["swiftCode"] = $request->swiftCode : "");
        ($request->Bankterms ? $invoice['payment']["terms"] = $request->Bankterms : "");
        // End Bank payment

        $trnsformed = json_encode($invoice, JSON_UNESCAPED_UNICODE);
        $myFileToJson = fopen('C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json', "w") or die("unable to open file");
        fwrite($myFileToJson, $trnsformed);
        return redirect()->route('cer');

    }


    // save draft invoice

   public function draft(Request $request)
    {

       $validated = $request->validate([
            // 'receiverCountry' => 'required',
            // 'receiverCountry' => 'required',
            // 'receiverGovernate' => 'required',
            // 'receiverRegionCity' => 'required',
            'receiverType' => 'required',
            // 'receiverId' => 'required',
            // 'receiverName' => 'required',
            'DocumentType' => 'required',
            'date' => 'required',
            'taxpayerActivityCode' => 'required',
            'internalId' => 'required',
            'ExtraDiscount' => 'required',
            'rate' => 'required',
            'invoiceDescription' => 'required',
            'itemCode' => 'required',
            't4subtype' => 'required',
            't1subtype' => 'required',

        ]);

        $invoice =
            [
            "issuer" => array(
                "address" => array(
                    "branchID" => "0",
                    "country" => "EG",
                    "governate" => auth()->user()->details->governate,
                    "regionCity" => auth()->user()->details->regionCity,
                    "street" => auth()->user()->details->street,
                    "buildingNumber" => auth()->user()->details->buildingNumber,
                ),
                "type" => auth()->user()->details->issuerType,
                "id" => auth()->user()->details->company_id,
                "name" => auth()->user()->details->company_name,
            ),

            "receiver" => array(
                "address" => array(

                ),
                "type" => $request->receiverType,

            ),
            "documentType" => $request->DocumentType,
            "documentTypeVersion" => "1.0",
            "dateTimeIssued" => $request->date . "T" . date("h:i:s") . "Z",
            "taxpayerActivityCode" => $request->taxpayerActivityCode,
            "internalID" => $request->internalId,
            "invoiceLines" => [

            ],
            "totalDiscountAmount" => floatval($request->totalDiscountAmount),
            "totalSalesAmount" => floatval($request->TotalSalesAmount),
            "netAmount" => floatval($request->TotalNetAmount),
            "taxTotals" => array(
                // array(
                //     "taxType" => "T4",
                //     "amount" => floatval($request->totalt4Amount),
                // ),
                // array(
                //     "taxType" => "T1",
                //     "amount" => floatval($request->totalt2Amount),
                // ),
            ),
            "totalAmount" => floatval($request->totalAmount2),
            "extraDiscountAmount" => floatval($request->ExtraDiscount),
            "totalItemsDiscountAmount" => floatval($request->totalItemsDiscountAmount),
        ];

        for ($i = 0; $i < count($request->quantity); $i++) {
            $Data = [
                "description" => $request->invoiceDescription[$i],
                "itemType" => "EGS",
                "itemCode" => $request->itemCode[$i],
                // "itemCode" => "10003834",
                "unitType" => $request->unitType[$i],
                "quantity" => floatval($request->quantity[$i]),
                "internalCode" => "100",
                "salesTotal" => floatval($request->salesTotal[$i]),
                "total" => floatval($request->totalItemsDiscount[$i]),
                "valueDifference" => 0.00,
                "totalTaxableFees" => 0.00,
                "netTotal" => floatval($request->netTotal[$i]),
                "itemsDiscount" => floatval($request->itemsDiscount[$i]),

                "unitValue" => [
                    "currencySold" => "EGP",
                    "amountSold" => 0.00,
                    "currencyExchangeRate" => 0.00,
                    "amountEGP" => floatval($request->amountEGP[$i]),
                ],
                "discount" => [
                    "rate" => 0.00,
                    "amount" => floatval($request->discountAmount[$i]),
                ],
                "taxableItems" => [
                    // [

                    //     "taxType" => "T4",
                    //     "amount" => floatval($request->t4Amount[$i]),
                    //     "subType" => ($request->t4subtype[$i]),
                    //     "rate" => floatval($request->t4rate[$i]),
                    // ],
                    // [
                    //     "taxType" => "T1",
                    //     "amount" => floatval($request->t2Amount[$i]),
                    //     "subType" => ($request->t1subtype[$i]),
                    //     "rate" => floatval($request->rate[$i]),
                    // ],
                ],

            ];
             if (floatval($request->t4rate[$i]) > 0) {
                $newArray = [

                    "taxType" => "T4",
                    "amount" => floatval($request->t4Amount[$i]),
                    "subType" => ($request->t4subtype[$i]),
                    "rate" => floatval($request->t4rate[$i]),
                ];

                array_push($Data['taxableItems'], $newArray);

            }

            if (floatval($request->rate[$i]) > 0) {
                $newArray2 = [
                    "taxType" => "T2",
                    "amount" => floatval($request->t2Amount[$i]),
                    "subType" => ($request->t1subtype[$i]),
                    "rate" => floatval($request->rate[$i]),
                ];
                array_push($Data['taxableItems'], $newArray2);

            }

            // send data to invoiceLines
            $invoice['invoiceLines'][$i] = $Data;
        }

        // this is for receiver address
        ($request->receiverName ? $invoice['receiver']['name'] = $request->receiverName : "");
        ($request->receiverCountry ? $invoice['receiver']["address"]['country'] = $request->receiverCountry : "");
        ($request->receiverBuildingNumber ? $invoice['receiver']["address"]['buildingNumber'] = $request->receiverBuildingNumber : "");
        ($request->street ? $invoice['receiver']["address"]['street'] = $request->street : "");
        ($request->receiverRegionCity ? $invoice['receiver']["address"]['regionCity'] = $request->receiverRegionCity : "");
        ($request->receiverGovernate ? $invoice['receiver']["address"]['governate'] = $request->receiverGovernate : "");
        ($request->receiverPostalCode ? $invoice['receiver']["address"]['postalcode'] = $request->receiverPostalCode : "");
        ($request->receiverFloor ? $invoice['receiver']["address"]['floor'] = $request->receiverFloor : "");
        ($request->receiverRoom ? $invoice['receiver']["address"]['room'] = $request->receiverRoom : "");
        ($request->receiverLandmark ? $invoice['receiver']["address"]['landmark'] = $request->receiverLandmark : "");
        ($request->receiverAdditionalInformation ? $invoice['receiver']["address"]['additionalInformation'] = $request->receiverAdditionalInformation : "");
        ($request->receiverId ? $invoice['receiver']['id'] = $request->receiverId : "");

        // this is for reference debit or credit note
        ($request->referencesInvoice ? $invoice['references'] = [$request->referencesInvoice] : "");
        // End reference debit or credit note



         if (floatval($request->totalt4Amount) > 0) {
            $newArray = [
                "taxType" => "T4",
                "amount" => floatval($request->totalt4Amount),
            ];
            array_push($invoice['taxTotals'], $newArray);
        }
        if (floatval($request->totalt2Amount) > 0) {
            $newArray = [
                "taxType" => "T2",
                "amount" => floatval($request->totalt2Amount),
            ];
            array_push($invoice['taxTotals'], $newArray);
        }

        // this is for Bank payment

        ($request->bankName ? $invoice['payment']["bankName"] = $request->bankName : "");
        ($request->bankAddress ? $invoice['payment']["bankAddress"] = $request->bankAddress : "");
        ($request->bankAccountNo ? $invoice['payment']["bankAccountNo"] = $request->bankAccountNo : "");
        ($request->bankAccountIBAN ? $invoice['payment']["bankAccountIBAN"] = $request->bankAccountIBAN : "");
        ($request->swiftCode ? $invoice['payment']["swiftCode"] = $request->swiftCode : "");
        ($request->Bankterms ? $invoice['payment']["terms"] = $request->Bankterms : "");
        // End Bank payment

        $trnsformed = json_encode($invoice, JSON_UNESCAPED_UNICODE);
        $myFileToJson = fopen('C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json', "w") or die("unable to open file");
        fwrite($myFileToJson, $trnsformed);
         $path = 'C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json';
        $fullDraftFile = file_get_contents($path);

        $draftInvoice = new DraftInvoice();
        $draftInvoice->tax_id = auth()->user()->details->company_id;
        $draftInvoice->jsondata = json_decode($fullDraftFile);
        $draftInvoice->save();
        // echo $fullDraftFile;
        unlink($path);
        return redirect()->route('showDraft')->with('success', 'تم حفظ المسودة بنجاح ');

    }


    // show all drafts of invoices

    public function showDraft()
    {
        $allDraft = DraftInvoice::where('tax_id',auth()->user()->details->company_id)->orderBy('id', 'desc')->paginate(100);
        return view('draft.index', compact('allDraft'));
    }

    // send to draft

    public function sendDraftData($id)
    {
        $data = DraftInvoice::find($id)['jsondata'];
        $trnsformed = json_encode($data, JSON_UNESCAPED_UNICODE);
        $myFileToJson = fopen('C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json', "w") or die("unable to open file");
        fwrite($myFileToJson, $trnsformed);
        $path = 'C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json';
        $fullDraftFile = file_get_contents($path);
        $obj = json_decode($fullDraftFile, true);
        $datetime = $obj['dateTimeIssued'] = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $trnsformed = json_encode($obj, JSON_UNESCAPED_UNICODE);
        $myFileToJson = fopen('C:\laragon\www\transhome\EInvoicing\SourceDocumentJson.json', "w") or die("unable to open file");
        $file = fwrite($myFileToJson, $trnsformed);
        // return $obj;

        return redirect('cer')->with('id', $id);
    }

    // show specific invoice draft
    public function showDraftDetails($id)
    {
        $draft = DraftInvoice::where('id', $id)->get()[0]['jsondata'];
        $invUuid = DraftInvoice::where('id', $id)->get()[0]['inv_uuid'];
        // return $draft;
        return view('draft.details', compact('draft', "id", 'invUuid'));
    }

    // delete invoice from drafts that is sent or no need to it

     public function deleteDraft($id)
    {
        $draft = DraftInvoice::find($id);
        $draft->delete();
        return redirect()->route('showDraft')->with('error', 'تم مسح الفاتورة بنجاح ');
    }


    // this is sent invoices that show our data to user
     public function SentInvoicesFromDraft()
    {
        $allSent = SentInvoices::where('tax_id',auth()->user()->details->company_id)->orderBy('id', 'desc')->paginate(100);
        // return $allSent;
        // foreach($allSent as $all){
        //     echo $all;
        // }
        // $response = Http::asForm()->post('https://id.preprod.eta.gov.eg/connect/token', [
        //     'grant_type' => 'client_credentials',
        //     'client_id' => auth()->user()->details->client_id,
        //     'client_secret' => auth()->user()->details->client_secret,
        //     'scope' => 'InvoicingAPI',
        // ]);
        // $token = $response['access_token'];
        // return view('sentofdraft.index', compact('allSent','token'));
        return view('sentofdraft.index', compact('allSent'));

    }

    // search for any invoices that are sent from me

    public function searchInSentInv(Request $request)
    {
        $allSent = SentInvoices::where(function ($query) use ($request) {
            if ($request->freetext && !null) {
                $freetext = $request->freetext;
            }
            if ($request->datefrom && !null) {
                $datefrom = $request->datefrom;
            }
            if ($request->dateto && !null) {
                $dateto = $request->dateto;
            }
            // $datefrom = $request->datefrom;
            // $dateto = $request->dateto;
            if ($request->datefrom && $request->dateto && $request->freetext) {
                $query->where('tax_id',auth()->user()->details->company_id)->where('jsondata', "like", "%$freetext%")->whereBetween('created_at', [$datefrom, $dateto])->orWhere('uuid','like',"%$freetext%");
            }elseif($request->datefrom && $request->dateto){
                $query->where('tax_id',auth()->user()->details->company_id)->whereBetween('created_at', [$datefrom, $dateto]);
            }elseif($request->freetext && !null  ){
                $query->where('tax_id',auth()->user()->details->company_id)->where('jsondata', "like", "%$freetext%")->orWhere('uuid','like',"%$freetext%");
            }
            // $query->orWhereBetween('created_at', [$datefrom, $dateto])->where('jsondata', "like", "%" . $freetext . "%");
        })->orderBy('created_at', 'desc')->get();
        // return $allSent;
        // foreach($allSent as $all){
        //     echo $all;
        // }
        // $response = Http::asForm()->post('https://id.preprod.eta.gov.eg/connect/token', [
        //     'grant_type' => 'client_credentials',
        //     'client_id' => auth()->user()->details->client_id,
        //     'client_secret' => auth()->user()->details->client_secret,
        //     'scope' => 'InvoicingAPI',
        // ]);
        // $token = $response['access_token'];
        return view('sentofdraft.index', compact('allSent', ));

    }

    // this for show details of invoice that i sent to ETA

    public function showSentInvDetails($id)
    {
        $allSent = SentInvoices::where('uuid', $id)->get();
        // return $allSent;
        $uuid = $allSent[0]['uuid'];

        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoice = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/$uuid/details");

        return view('sentofdraft.details', compact('showInvoice', 'allSent', 'uuid'));

//        return $showInvoice['status'] . '<br/>' . $allSent;

    }


    // this is for delete invoice that i was sent before

     public function deleteSentInv($id)
    {
        $deletesent = SentInvoices::find($id);
        $deletesent->delete();
        return redirect()->route('sentofdraft')->with('error', 'تم مسح الفاتورة المرسلة بنجاح ');
    }


// this function for signature

    public function openBat()
    {

        shell_exec('C:\laragon\www\transhome\EInvoicing/SubmitInvoices2.bat');
        $path = 'C:\laragon\www\transhome\EInvoicing/FullSignedDocument.json';
        $path2 = 'C:\laragon\www\transhome\EInvoicing/Cades.txt';
        $path3 = 'C:\laragon\www\transhome\EInvoicing/CanonicalString.txt';
        $path4 = 'C:\laragon\www\transhome\EInvoicing/SourceDocumentJson.json';

        $fullSignedFile = file_get_contents($path);

        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $invoice = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->withBody($fullSignedFile, "application/json")->post("$this->url2/api/v1/documentsubmissions");

      if ($invoice['submissionId'] == !null) {
            // if ($invoice) {
            $sentInvoices = new SentInvoices();
            $sentInvoices->uuid = $invoice['acceptedDocuments'][0]['uuid'];
            $sentInvoices->longid = $invoice['acceptedDocuments'][0]['longId'];
            $sentInvoices->tax_id = auth()->user()->details->company_id;
            $sentInvoices->jsondata = json_decode($fullSignedFile);
            $sentInvoices->save();

            if (\Session::has('id')) {
                $sentInvoices->draft_id = \Session::get('id');

                DB::transaction(function () use ($sentInvoices) {
                    if ($sentInvoices->save()) {
                        $draftInv = DraftInvoice::where('id', $sentInvoices->draft_id)->first();
                        $draftInv->inv_id = $sentInvoices->id;
                        $draftInv->inv_uuid = $sentInvoices->uuid;
                    }
                    $draftInv->update();
                });
            }

            // return $sentInvoices->id;
            unlink($path);
            unlink($path2);
            unlink($path3);
            unlink($path4);
            return redirect()->route('sentofdraft')->with('success', 'تم تسجيل الفاتورة بنجاح ');
            // return $invoice->body();

        } else {
            unlink($path);
            unlink($path2);
            unlink($path3);
            // unlink($path4);
            //return $invoice->body();
            foreach ($invoice['rejectedDocuments'][0]['error']['details'] as $Rejectedinvoice) {
                return redirect()->route('sentofdraft')->with('error', $Rejectedinvoice['message'] . '<br>' . $Rejectedinvoice['target']);
            }

        }
    }

// this is for create page of invoice
    public function createInvoice()
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $unittypes = DB::table('unittypes')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        return view('invoices.createInvoice2', compact('allCompanies', 'codes', 'ActivityCodes', 'taxTypes', 'products','unittypes'));
    }

    // this function for Fill  the customer information

    public function createInvoice2(Request $request)
    {

        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        $companiess = DB::table('companies2')->where('id', $request->receiverName)->get();
        return view('invoices.createInvoice2', compact('companiess', 'allCompanies', "codes", 'ActivityCodes', 'taxTypes', "products"));
    }

    public function createInvoiceDollar()
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        return view('invoices.createInvoice3', compact('allCompanies', 'codes', 'ActivityCodes', 'taxTypes', 'products'));
    }

    // this function for Fill  the customer information

    public function createInvoiceDollar2(Request $request)
    {

        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        $companiess = DB::table('companies2')->where('id', $request->receiverName)->get();
        return view('invoices.createInvoice3', compact('companiess', 'allCompanies', "codes", 'ActivityCodes', 'taxTypes', "products"));
    }

    public function createInvoice3()
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        return view('invoices.createInvoice3', compact('allCompanies', 'codes', 'ActivityCodes', 'taxTypes', 'products'));
    }

    // this function for Fill  the customer information

    public function createInvoice4(Request $request)
    {

        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $product = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Content-Type" => "application/json",
        ])->get("$this->url2/api/v1.0/codetypes/requests/my?Active=true&Status=Approved&PS=1000");

        $products = $product['result'];
        $codes = DB::table('products')->where('status', 'Approved')->get();
        $ActivityCodes = DB::table('activity_code')->get();
        $allCompanies = DB::table('companies2')->get();
        $taxTypes = DB::table('taxtypes')->get();
        $companiess = DB::table('companies2')->where('id', $request->receiverName)->get();
        return view('invoices.createInvoice3', compact('companiess', 'allCompanies', "codes", 'ActivityCodes', 'taxTypes', "products"));
    }

// show pdf printout
    public function showPdfInvoice($uuid)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showPdf = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
            "Accept-Language" => 'ar',
        ])->get("$this->url2/api/v1/documents/" . $uuid . "/pdf");

        return response($showPdf)->header('Content-Type', 'application/pdf');
    }

    public function cancelDocument($uuid)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $cancel = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->put(
            "$this->url2/api/v1.0/documents/state/" . $uuid . '/state',
            array(
                "status" => "cancelled",
                "reason" => "يوجد خطأ بالفاتورة",
            )
        );
        // return ($cancel);
        if ($cancel->ok()) {
            return redirect()->route('sentInvoices',"1")->with('success', 'تم تقديم طلب الغاء الفاتورة بنجاح سيتم الموافقة او الرفض فى خلال 7 ايام');
        } else {
            return redirect()->route('sentInvoices',"1")->with('error', $cancel['error']['details'][0]['message']);
        }
    }

    public function RejectDocument($uuid)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $cancel = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->put(
            "$this->url2/api/v1.0/documents/state/" . $uuid . '/state',
            array(
                "status" => "rejected",
                "reason" => "يوجد خطأ بالفاتورة",
            )
        );
        // return ($cancel);
        if ($cancel->ok()) {
            return redirect()->route('receivedInvoices','1')->with('success', 'تم تقديم طلب رفض الفاتورة بنجاح سيتم الموافقة او الرفض فى خلال 7 ايام');
        } else {
            return redirect()->route('receivedInvoices','1')->with('error', $cancel['error']['details'][0]['message']);
        }

    }

    public function DeclineRejectDocument($uuid)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $cancel = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->put(
            "$this->url2/api/v1.0/documents/state/" . $uuid . '/decline/rejection');
        // return ($cancel);
        if ($cancel->ok()) {
            return redirect()->back()->with('success', 'تم الغاء الرفض بنجاح');
        } else {
            return redirect()->back()->with('error', $cancel['error']['details'][0]['message']);
        }
    }

    public function DeclineCancelDocument($uuid)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $cancel = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->put(
            "$this->url2/api/v1.0/documents/state/" . $uuid . '/decline/cancelation');
        // return ($cancel);
        if ($cancel->ok()) {
            return redirect()->back()->with('success', 'تم الغاء الإلغاء بنجاح');
        } else {
            return redirect()->back()->with('error', $cancel['error']['details'][0]['message']);
        }
    }

    public function RequestcancelledDoc($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.requestCancelled', compact('allInvoices', 'allMeta', 'id'));
    }

    public function companiesRequestcancelledDoc($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.companiesRequestCancelled', compact('allInvoices', 'allMeta', 'id'));
    }

    public function cancelledDoc($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.showCancelled', compact('allInvoices', 'allMeta', 'id'));
    }

    public function companyCancelledDoc($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.showCompanyCancelled', compact('allInvoices', 'allMeta', 'id'));
    }

    public function rejected($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.showRejected', compact('allInvoices', 'allMeta', 'id'));
    }

    public function companyRejected($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.companyRejected', compact('allInvoices', 'allMeta', 'id'));
    }

    public function requestCompanyRejected($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.RequestCompanyRejected', compact('allInvoices', 'allMeta', 'id'));
    }

    public function requestRejected($id)
    {
        $response = Http::asForm()->post("$this->url1/connect/token", [
            'grant_type' => 'client_credentials',
            'client_id' => auth()->user()->details->client_id,
            'client_secret' => auth()->user()->details->client_secret,
            'scope' => "InvoicingAPI",
        ]);

        $showInvoices = Http::withHeaders([
            "Authorization" => 'Bearer ' . $response['access_token'],
        ])->get("$this->url2/api/v1.0/documents/recent?pageNo=$id&pageSize=100");

        $allInvoices = $showInvoices['result'];

        $allMeta = $showInvoices['metadata'];
        return view('invoices.RequestRejected', compact('allInvoices', 'allMeta', 'id'));
    }

}
