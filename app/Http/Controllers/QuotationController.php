<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        return response()->json([
            'Sucess' => true,
            'data' => $i
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
            ->select('id', 'prescription_name', 'file_name', 'date', 'note', 'address', 'deliveryTime')
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
}
