<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تقرير مركبات الجمارك المحلية</title>
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
            تقرير مركبات الجمارك المحلية
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
                <th>الرقم التسلسلي</th>
                <th>رقم اللوحة</th>
                <th>اسم المستخدم</th>
                <th>تاريخ الوصول من الفرع</th>
                <th>الشركة</th>
                <th>القسم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $index => $vehicle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vehicle->serial_number ?? '-' }}</td>
                    <td>{{ $vehicle->vehicle_plate_number ?? '-' }}</td>
                    <td>{{ $vehicle->user_name ?? '-' }}</td>
                    <td>{{ $vehicle->arrival_date_from_branch ? $vehicle->arrival_date_from_branch->format('Y-m-d') : '-' }}</td>
                    <td>{{ optional($vehicle->company)->name ?? '-' }}</td>
                    <td>{{ optional($vehicle->department)->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
