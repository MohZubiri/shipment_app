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
                <th>رقم العملية</th>
                <th>تاريخ مغادرة المصنع</th>
                <th>رقم المركبة</th>
                <th>رقم القيد</th>
                <th>نوع البضاعة</th>
                <th>اسم السائق</th>
                <th>رقم هاتف السائق</th>
                <th>المنفذ الجمركي</th>
                <th>تاريخ الوصول للجمرك</th>
                <th>وقت الدخول للجمرك</th>
                <th>تاريخ مغادرة الجمرك</th>
                <th>وقت الخروج من الجمرك</th>
                <th>ايام المماسي</th>
                <th>المخزن</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $index => $vehicle)
                @php
                    $firstCustoms = $vehicle->customs->first();
                    $entryDate = $firstCustoms?->entry_date;
                    $exitDate = $firstCustoms?->exit_date;
                    $entryTime = $firstCustoms?->entry_time ? \Carbon\Carbon::parse($firstCustoms->entry_time)->format('H:i') : null;
                    $exitTime = $firstCustoms?->exit_time ? \Carbon\Carbon::parse($firstCustoms->exit_time)->format('H:i') : null;
                    $dockingDays = '-';
                    if ($entryDate && $exitDate) {
                        $diffDays = $entryDate->diffInDays($exitDate, false);
                        $dockingDays = $diffDays >= 2 ? $diffDays - 2 : 0;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vehicle->serial_number ?? '-' }}</td>
                    <td>{{ $vehicle->factory_departure_date ? $vehicle->factory_departure_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $vehicle->vehicle_plate_number ?? '-' }}</td>
                    <td>{{ $vehicle->vehicle_number ?? '-' }}</td>
                    <td>{{ $vehicle->cargo_type ?? '-' }}</td>
                    <td>{{ $vehicle->driver_name ?? '-' }}</td>
                    <td>{{ $vehicle->driver_phone ?? '-' }}</td>
                    <td>
                        @if($firstCustoms)
                            {{ optional($firstCustoms->customsPort)->name ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($firstCustoms)
                            {{ $entryDate ? $entryDate->format('Y-m-d') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($firstCustoms)
                            {{ $entryTime ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($firstCustoms)
                            {{ $exitDate ? $exitDate->format('Y-m-d') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($firstCustoms)
                            {{ $exitTime ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $dockingDays }}</td>
                    <td>{{ optional($vehicle->warehouse)->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
