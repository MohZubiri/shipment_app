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
            
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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
        </h2>
        @if($selectedCompany || $selectedDepartment || $selectedPort)
            <h3>
                @if($selectedCompany)
                    الشركة: {{ $selectedCompany->name }}
                @endif
                @if($selectedDepartment)
                    {{ $selectedCompany ? ' | ' : '' }}القسم: {{ $selectedDepartment->name }}
                @endif
                @if($selectedPort)
                    {{ ($selectedCompany || $selectedDepartment) ? ' | ' : '' }}المنفذ الجمركي: {{ $selectedPort->name }}
                @endif
            </h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>رقم القيد</th>
                <th>رقم اللوحة</th>
                <th>اسم السائق</th>
                <th>رقم هاتف السائق</th>
                <th>المنفذ الجمركي</th>
                <th>تاريخ الوصول للجمرك</th>
                <th>تاريخ مغادرة الجمرك</th>
                <th>المخزن</th>
                <th>وقت مغادرة المصنع</th>
                <th>تاريخ الوصول للمخزن</th>
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
                    <td>{{ $vehicle->driver_name ?? '-' }}</td>
                    <td>{{ $vehicle->driver_phone ?? '-' }}</td>
                    <td>
                        @if($vehicle->customs && $vehicle->customs->first())
                            {{ optional($vehicle->customs->first()->customsPort)->name ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($vehicle->customs && $vehicle->customs->first())
                            {{ $vehicle->customs->first()->entry_date ? $vehicle->customs->first()->entry_date->format('Y-m-d') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($vehicle->customs && $vehicle->customs->first())
                            {{ $vehicle->customs->first()->exit_date ? $vehicle->customs->first()->exit_date->format('Y-m-d') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ optional($vehicle->warehouse)->name ?? '-' }}</td>
                    <td>{{ $vehicle->factory_departure_date ? $vehicle->factory_departure_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $vehicle->warehouse_arrival_date ? $vehicle->warehouse_arrival_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ optional($vehicle->company)->name ?? '-' }}</td>
                    <td>{{ optional($vehicle->department)->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>