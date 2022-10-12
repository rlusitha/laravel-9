@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Add Prescription</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Prescription Form</h6>
        </div>
        <div class="card-body">
            <form class="user" method="POST" action="{{ route('prescription.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <b><label for="prescription_name">Prescription Name</label></b>
                    <input class="form-control" type="text" name="prescription_name" placeholder="Ex: Mom's prescription">
                </div>
                <div class="custom-file form-group mb-3 mt-3">
                    <input type="file" class="custom-file-input" id="prescriptionImg" name="prescriptionImg[]" multiple="true">
                    <label class="custom-file-label" for="prescriptionImg">Upload prescription</label>
                </div>
                <div class="form-group">
                    <b><label for="note">Note</label></b>
                    <textarea class="form-control" name="note" id="note" rows="3" placeholder="Add a note"></textarea>
                </div>
                <div class="form-group">
                    <b><label for="address">Delivery Address</label></b>
                    <input class="form-control" type="text" name="address" placeholder="Enter delivery address">
                </div>
                <div class="form-group">
                    <b><label for="exampleFormControlInput1">Delivery time slot</label></b>
                    <select class="custom-select" id="deliveryTime" name="deliveryTime">
                        <option value="" selected>Choose your convenient delivery time slot</option>
                        <option value="9AM">9AM</option>
                        <option value="11AM">11AM</option>
                        <option value="1PM">1PM</option>
                        <option value="3PM">3PM</option>
                        <option value="5PM">5PM</option>
                        <option value="7PM">7PM</option>
                        <option value="9PM">9PM</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript'>
    var limit = 5;
    $(document).ready(function() {
        $("input#prescriptionImg").change(function() {
            var files = $(this)[0].files;
            if (files.length > limit) {
                alert("You can select max " + limit + " images.");
                $("#prescriptionImg").val("");
                return false;
            } else {
                return true;
            }
        });
    });
</script>
@endsection