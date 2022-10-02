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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user_id = Auth::id();

        $prescriptions = DB::table('prescriptions')
            ->join('users', 'users.id', '=', 'prescriptions.user_id')
            ->where('prescriptions.user_id', '=', $current_user_id)
            ->get();

        foreach ($prescriptions as $prescription) {
            $path = $prescription->path;
            // dd($path);
            $contents = Storage::get($path);
            $img = Image::make($contents)->resize(700, 750);
            $img->save('prescription.jpg');
            // $img = Image::make($path)->resize(700, 750);
            // dd($img);
        }
        // $contents = Storage::get($path);

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
        $increment_for_file_name = 0;

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

            DB::table('prescriptions')->insert([
                'prescription_name' => $prescription_name,
                'path' => $path,
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
