<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
// use Image;

class PrescriptionController extends Controller
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
        $current_user_id = Auth::id();

        $prescriptions = DB::table('prescriptions') 
            ->select('id', 'path', 'file_name', 'prescription_name', 'date', 'user_id')
            ->where('prescriptions.user_id', '=', $current_user_id)
            ->get();

        foreach ($prescriptions as $prescription) {
            $file_name = $prescription->file_name;
            $path = $prescription->path;
            $contents = Storage::get($path);
            $img = Image::make($contents)->resize(700, 750);
            $img->save($file_name);
        }

        return view('prescription.viewPrescriptions', ['prescriptions' => $prescriptions]);
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
            'prescription_name' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'deliveryTime' => 'required',

        ]);

        //Storing the user inputs to variables
        $prescription_name = $request->input('prescription_name');
        $note = $request->input('note');
        $address = $request->input('address');
        $deliveryTime = $request->input('deliveryTime');
        $user_id = $request->user()->id;

        //Inserting the data to database
        foreach ($request->file('prescriptionImg') as $uploadedFiles) {
            $path = $uploadedFiles->store('public');
            $file_name = basename($path);

            DB::table('prescriptions')->insert([
                'prescription_name' => $prescription_name,
                'path' => $path,
                'file_name' => $file_name,
                'note' => $note,
                'address' => $address,
                'deliveryTime' => $deliveryTime,
                'date' => date('Y-m-d'),
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
        $current_user_id = Auth::id();

        $prescription_img = DB::table('prescriptions') 
        ->select('file_name')
        ->where('prescriptions.id', '=', $id)
        ->where('prescriptions.user_id', '=', $current_user_id)
        ->get();

        $prescription_name = DB::table('prescriptions') 
        ->select('prescription_name')
        ->where('prescriptions.id', '=', $id)
        ->where('prescriptions.user_id', '=', $current_user_id)
        ->get();

        // return view('prescription.viewPrescriptions', ['prescription_img' => $prescription_img, 'prescription_name' => $prescription_name]);
        return response()->json([
            'prescription_img' => $prescription_img,
            'prescription_name' => $prescription_name,
        ]);
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
