<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $fileName }}</title>
    <style type="text/css" media="all">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }

        html{
            width:100%;
        }
        .document-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .document-header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            text-align: left;
            color: #fe800d;
        }

        .document-header h2 {
            font-size: 15px;
            margin: 0 0 10px 0;
            text-align: left;
        }

        .document-header p {
            font-size: 12px;
            margin: 0;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border-radius: 10px 10px 10px 10px;
        }

        table td,
        th {
            border-color: #ededed;
            border-style: solid;
            border-width: 1px;
            font-size: 12px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        table th {
            font-weight: normal;
        }

    </style>
</head>
<body>
    <div class="document-header">
        <h1>{{ 'PALAWAN INTERNATIONAL TECHNOLOGY BUSINESS INCUBATOR' }}</h1>
        <h2>{{ $fileName }}</h2>
        <p>
            Date of Print: {{ now()->format('F j, Y, g:i A') }}
        </p>
    </div>
    <table>
        <tr>
            <th>#</th> @foreach ($columns as $column)
                <th>
                    {{ $column->getLabel() }}
                </th>
            @endforeach
        </tr>
        @foreach ($rows as $row)
            <tr>
                <td>
                    {{ $loop->iteration }} </td>
                @foreach ($columns as $column)
                    <td>
                        {{ $row[$column->getName()] }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>
