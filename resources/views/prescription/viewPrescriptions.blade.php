@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">My Prescriptions</h1>
    <p class="mb-4">List of all your prescriptions and quotations. You may accept the quotation or reject</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My Prescriptions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered prescriptionTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>View Prescription</th>
                            <th>View Quotation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>View Prescription</th>
                            <th>View Quotation</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->id }}</td>
                            <td>{{ $prescription->prescription_name }}</td>
                            <td>{{ $prescription->date }}</td>
                            <td><a href="" class="viewImg" target="_blank" data-toggle="modal" data-target="#prescriptionModal" data-id="{{ $prescription->id }}">View Prescription</a></td>
                            <td><a href="" target="_blank" data-toggle="modal" data-target="#quotationModal">View Quotation</a></td>
                            <td><button class="btn btn-success mr-3">Accept</button><button class="btn btn-danger">Reject</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- The Modal for prescription -->
        <div class="modal fade" id="prescriptionModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 id="modal-title" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <img id="imgPres" style="display: block; margin-left: auto; margin-right: auto;" src="" alt="prescription">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- End of modal for prescription -->

        <!-- The Modal for quotation -->
        <div class="modal fade" id="quotationModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Modal Heading</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        Modal body..
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- End of modal for quotation -->
    </div>

</div>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

<script type='text/javascript'>
    $(document).ready(function() {

        $('.prescriptionTable').on('click', '.viewImg', function(e) {
            e.preventDefault();

            var prescriptionID = $(this).attr('data-id');

            if (prescriptionID > 0) {

                // AJAX request
                var url = "{{ route('prescription.show',[':prescriptionID']) }}";
                url = url.replace(':prescriptionID', prescriptionID);

                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(response) {
                        //Change the title of the modal header
                        $('#modal-title').text(response.prescription_name[0].prescription_name);

                        //Change the prescription image
                        $('#imgPres').attr("src", response.prescription_img[0].file_name);
                    }
                });
            }
        });

    });
</script>
@endsection