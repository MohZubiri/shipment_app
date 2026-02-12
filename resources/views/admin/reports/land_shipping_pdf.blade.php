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
                <th>أرقام القواطر</th>
                <th>اسم الشحنة</th>
                <th>عدد القواطر</th>
                <th>رقم البيان</th>
                <th>حالة البيان</th>
                <th>تاريخ الوصول</th>
                <th>تاريخ الخروج</th>
                <th>أيام المماسي</th>
                <th>نوع المستند</th>
                <th>تاريخ استلام المستندات</th>
                <th>المرحلة الحالية</th>
                <th>وجهة الترحيل (المخزن)</th>
                <th>تاريخ الترحيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($landShipments as $index => $landShipping)
                @php
                    // Calculate docking days (أيام المماسي)
                    $dockingDays = '-';
                    if ($landShipping->arrival_date && $landShipping->exit_date) {
                        $diffDays = $landShipping->arrival_date->diffInDays($landShipping->exit_date, false);
                        // بدء العد بعد يوم واحد
                        $dockingDays = $diffDays >= 1 ? $diffDays - 1 : 0;
                    }
                    $customsStateLabel = match ($landShipping->customsData?->state) {
                        1 => 'ضمان',
                        2 => 'سداد',
                        default => '-',
                    };
                    $warehouseTransferDate = $landShipping->warehouseTracking?->event_date
                        ?? $landShipping->warehouseTracking?->created_at
                        ?? $landShipping->warehouse_arrival_date;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $landShipping->operation_number ?? '-' }}</td>
                    <td>{{ $landShipping->locomotives->pluck('locomotive_number')->implode(', ') ?: '-' }}</td>
                    <td>{{ $landShipping->shipment_name ?? '-' }}</td>
                    <td>{{ $landShipping->locomotives->count() ?: '-' }}</td>
                    <td>{{ $landShipping->declaration_number ?? '-' }}</td>
                    <td>{{ $customsStateLabel }}</td>
                    <td>{{ $landShipping->arrival_date ? $landShipping->arrival_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $landShipping->exit_date ? $landShipping->exit_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $dockingDays }}</td>
                    <td>{{ $landShipping->documents_type ?? '-' }}</td>
                    <td>{{ $landShipping->documents_sent_date ? $landShipping->documents_sent_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ optional($landShipping->currentStage)->name ?? '-' }}</td>
                    <td>{{ $landShipping->warehouseTracking?->warehouse?->name ?? '-' }}</td>
                    <td>{{ $warehouseTransferDate?->format('Y-m-d') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
