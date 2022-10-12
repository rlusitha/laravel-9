@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Create Quotation</h1>
    <div class="row">
        <div class="col-6">
            <!-- Left side -->
            @foreach($file_names as $file_name)
            <img style="width: 600px;" src="{{$file_name->file_name}}" alt="prescription_img">
            @endforeach
        </div>
        <div class="col">
            <!-- Right side -->
            <div id="table-scroll" class="table-scroll">
                <!-- <form> -->
                <table id="main-table" class="main-table">
                    <thead>
                        <tr>
                            <th scope="col" class="drug">Drug</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="drug"></th>
                            <td></td>
                            <td>Total :</td>
                            <td id="amount">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br>
            <br>
            <div>
                <div class="form-group row">
                    <label for="drug" class="col-sm-2 col-form-label">Drug :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="drug" name="drug" placeholder="Enter the drug">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="unit_price" class="col-sm-2 col-form-label">Unit Price :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="unit_price" name="unit_price" placeholder="Enter unit price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="quantity" class="col-sm-2 col-form-label">Quantity :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <button id="add" type="button" class="btn btn-primary float-left">Add</button>
                    </div>
                    <div class="col-sm-6">
                        <button id="create_quotation" value="{{ $prescription_id }}" class="btn btn-success float-right" disabled>Create Quotation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var rowId = 0;
    var total = 0;
    var table_row_count = 0;

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Disabling the Create Quotation button, when the page loads for the first time
        if (table_row_count == 0) {
            $('#create_quotation').attr('disabled', true);
        }

        // Adding rows to the table dynamically
        $("#add").click(function() {
            var drug = $('#drug').val();
            var unit_price = $('#unit_price').val();
            var quantity = $('#quantity').val();

            var amount = 0;

            if (drug == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Please enter a drug name'
                })
            } else if (unit_price == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Please enter the unit price'
                })
            } else if (quantity == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Please enter a quantity'
                })
            } else {
                amount = unit_price * quantity;
                $('#amount').val(amount);
                total = total + amount;

                $('#tbody').append(`<tr id="R${++rowId}">
                    <td>${drug}</td>
                    <td>${unit_price}</td>
                    <td>${quantity}</td>
                    <td>${amount}</td>
                    </tr>`);

                $('#amount').html(`${total}`);

                table_row_count++;

                // Enabling the Create Quotation button
                if (table_row_count > 0) {
                    $('#create_quotation').attr('disabled', false);
                }

                $('#drug').val('');
                $('#unit_price').val('');
                $('#quantity').val('');
            }
        });

        // Getting the text of tds
        $('#create_quotation').click(function(e) {
            e.preventDefault();

            var prescription_id = $('#create_quotation').val();
            var url = "{{ route('quotation.store') }}";
            var TableData = new Array();

            $('#main-table').each(function(row, tr) {
                TableData = TableData +
                    $(tr).find('td:eq(0)').text() +
                    $(tr).find('td:eq(1)').text() +
                    $(tr).find('td:eq(2)').text() +
                    $(tr).find('td:eq(3)').text();
            });

            function storeTblValues() {
                var TableData = new Array();

                $('#main-table tr').each(function(row, tr) {
                    TableData[row] = {
                        "drug": $(tr).find('td:eq(0)').text(),
                        "unit_price": $(tr).find('td:eq(1)').text(),
                        "quantity": $(tr).find('td:eq(2)').text(),
                        "amount": $(tr).find('td:eq(3)').text()
                    }
                });
                TableData.shift(); // Removing the header row
                TableData.pop(); // Removing the footer row

                return TableData;
            }

            TableData = storeTblValues();

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: {
                    TableData: TableData,
                    prescription_id: prescription_id,
                },
                success: function(response) {
                    if (response) {
                        Swal.fire(
                            'Success!',
                            'Quotation created successfully!',
                            'success'
                        )

                        setTimeout(function() {
                            window.location.href = "{{route('quotation.index')}}";
                        }, 1000);
                    }
                }
            });
        });
    });
</script>
@endsection