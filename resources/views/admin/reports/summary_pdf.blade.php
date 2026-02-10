<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>التقرير المجمّع حسب الشركة</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tfoot {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2,
        .header h3 {
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>
            التقرير المجمّع حسب الشركة
            {{ $selectedCompany ? $selectedCompany->name : '' }}
        </h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>الشركة</th>
                <th>عدد الشحنات (بحر/جو)</th>
                <th>عدد الشحنات البرية</th>
                <th>عدد مركبات الجمارك المحلية</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $index => $company)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->shipment_transactions_count ?? 0 }}</td>
                    <td>{{ $company->land_shippings_count ?? 0 }}</td>
                    <td>{{ $company->local_customs_vehicles_count ?? 0 }}</td>
                    <td>{{ ($company->shipment_transactions_count ?? 0) + ($company->land_shippings_count ?? 0) + ($company->local_customs_vehicles_count ?? 0) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: left;">الإجمالي الكلي</td>
                <td>{{ $companies->sum('shipment_transactions_count') }}</td>
                <td>{{ $companies->sum('land_shippings_count') }}</td>
                <td>{{ $companies->sum('local_customs_vehicles_count') }}</td>
                <td>{{ $companies->sum('shipment_transactions_count') + $companies->sum('land_shippings_count') + $companies->sum('local_customs_vehicles_count') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>