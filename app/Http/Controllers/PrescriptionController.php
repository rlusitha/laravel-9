<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
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
        return view('prescription.createPrescription');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation
        $validated = $request->validate([
            'prescriptionImg' => 'required|mimes:jpg,jpeg,png',
            'note' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'deliveryTime' => 'required',

        ]);

        //Storing the user inputs to variables
        $note = $request->input('note');
        $address = $request->input('address');
        $deliveryTime = $request->input('deliveryTime');
        $user_id = $request->user()->id;

        //Inserting the data to database
        foreach ($request->file('prescriptionImg') as $uploadedFiles) {
            $path = $uploadedFiles->store('prescriptions');

            DB::table('prescriptions')->insert([
                'path' => $path,
                'note' => $note,
                'address' => $address,
                'deliveryTime' => $deliveryTime,
                'user_id' => $user_id,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
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
}
