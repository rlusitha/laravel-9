<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }
    </style>
</head>

<body>

    <table width="100%">
        <tr>
            <td valign="top"><img src="{{public_path('img/xiteb_logo.png')}}" /></td>
            <td align="right">
                <h3>Xiteb Pharmacy</h3>
                <pre>
                Lusitha Ranathunga
                123 2/1, 2nd Floor, McLarens Building
                Bauddhaloka Mawatha
                Colombo 04
                TP : 0114 347 575 
            </pre>
            </td>
        </tr>

    </table>

    <table width="100%">
        <tr>
            <td><strong>From :</strong> Lusitha Ranathunga</td>
            <td><strong>To:</strong> Linblum - Barrio Comercial</td>
        </tr>

    </table>

    <br />

    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>#</th>
                <th>Drug Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @foreach($quotation_data as $data )
            <tr>
                <th scope="row">{{$count++}}</th>
                <td>{{ $data->drug_name }}</td>
                <td align="right">{{ $data->quantity }}</td>
                <td align="right">{{ $data->unit_price }}</td>
                <td align="right">{{ $data->amount }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td align="right">Total </td>
                <td align="right" class="gray">{{$total_price}}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>