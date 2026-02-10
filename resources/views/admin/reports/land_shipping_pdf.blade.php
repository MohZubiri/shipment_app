<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تقرير الشحنات البرية</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-family: 'almarai', sans-serif;
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
            تقرير الشحنات البرية
            {{ $selectedCompany ? $selectedCompany->name : '' }}
        </h2>
        @if($selectedSection)
        <h3>
            القسم: {{ $selectedSection->name }}
        </h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>رقم العملية</th>
                <th>اسم الشحنة</th>
                <th>رقم البيان</th>
                <th>تاريخ الوصول</th>
                <th>الشركة</th>
                <th>القسم</th>
                <th>المرحلة الحالية</th>
                <th>المستندات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($landShipments as $index => $landShipping)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $landShipping->operation_number ?? '-' }}</td>
                    <td>{{ $landShipping->shipment_name ?? '-' }}</td>
                    <td>{{ $landShipping->declaration_number ?? '-' }}</td>
                    <td>{{ $landShipping->arrival_date ? $landShipping->arrival_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ optional($landShipping->company)->name ?? '-' }}</td>
                    <td>{{ optional($landShipping->department)->name ?? '-' }}</td>
                    <td>{{ optional($landShipping->currentStage)->name ?? '-' }}</td>
                    <td>{{ $landShipping->documents_type ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
