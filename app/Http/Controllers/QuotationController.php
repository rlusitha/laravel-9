<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationEmail;

class QuotationController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prescriptions = DB::table('prescriptions')
            ->select('id', 'file_name', 'prescription_name', 'date', 'quotation_status')
            ->get();

        return view('quotation.viewQuotations', ['prescriptions' => $prescriptions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contents = asset('storage/prescription_gamage.jpg');

        return view('quotation.createQuotation', ['contents' => $contents]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $i = 0;
        $user_id = $request->user()->id;
        $prescription_id = $request->prescription_id;

        foreach ($request['TableData'] as $result) {
            $drug[] = $result['drug'];
            $unit_price[] = $result['unit_price'];
            $quantity[] = $result['quantity'];
            $amount[] = $result['amount'];

            $i++;
        }

        for ($j = 0; $j < $i; $j++) {
            DB::table('quotations')->insert([
                'drug_name' => $drug[$j],
                'unit_price' => $unit_price[$j],
                'quantity' => $quantity[$j],
                'amount' => $amount[$j],
                'prescription_id' => $prescription_id,
                'user_id' => $user_id,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }

        DB::table('prescriptions')
            ->where('id', '=', $prescription_id)
            ->update(['quotation_status' => 'created']);

        return response()->json([
            'Sucess' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        DB::table('prescriptions')
            ->where('id', '=', $id)
            ->update(['quotation_status' => 'sent']);

        return response()->json([
            'Sucess' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * View list of prescriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_prescriptions()
    {
        $prescriptions = DB::table('prescriptions')
            ->select('id', 'prescription_name', 'file_name', 'date', 'note', 'address', 'deliveryTime', 'quotation_status')
            ->get();

        return view('quotation.viewPrescriptions', ['prescriptions' => $prescriptions]);
    }

    public function create_quotation_view(Request $request)
    {
        $prescription_id = $request->prescription_id;

        $file_name = DB::table('prescriptions')
            ->select('file_name')
            ->where('id', '=', $prescription_id)
            ->get();

        return view('quotation/createQuotation', ['file_names' => $file_name, 'prescription_id' => $prescription_id]);
    }

    public function quotation_pdf_generator($id)
    {
        $prescription_id = $id;
        $total = 0;

        $quotation_data = DB::table('quotations')
            ->select('drug_name', 'unit_price', 'quantity', 'amount', 'prescription_id')
            ->where('prescription_id', '=', $prescription_id)
            ->get();

        foreach ($quotation_data as $data) {
            $amount = $data->amount;
            $total = $total + $amount;
        }

        $total = number_format($total, 2);

        $file_names = DB::table('prescriptions')
            ->select('file_name')
            ->join('quotations', 'prescriptions.id', '=', 'quotations.prescription_id')
            ->where('prescriptions.id', '=', $prescription_id)
            ->get();

        foreach ($file_names as $file_name) {
            $prescription_file_name = $file_name->file_name;
        }

        //Removing the extension of the file name
        $prescription_file_name = substr($prescription_file_name, 0, -4);

        $pdf = Pdf::loadView('quotation.pdfQuotation', ['quotation_data' => $quotation_data, 'total_price' => $total]);
        return $pdf->stream($prescription_file_name . '.pdf');
    }

    public function send_mail(Request $request)
    {
        $prescription_id = $request->prescription_id;

        $names = DB::table('users')
        ->join('prescriptions', 'users.id', '=', 'prescriptions.user_id')
        ->where('prescriptions.id', '=', $prescription_id)
        ->select('name')
        ->get();

        foreach($names as $name) {
            $name = $name->name;
        }

        $mailData = [
            'name' => $name,
        ];

        Mail::to("rlusitha@gmail.com")->send(new QuotationEmail($mailData));
    }
}
