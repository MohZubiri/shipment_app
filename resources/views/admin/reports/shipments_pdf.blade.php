<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تقرير الشحنات</title>
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
            التقرير اليومي لشركة التكامل الدولية شركة
            {{ $selectedDepartment ? $selectedDepartment->name : 'العصائر' }}
            لدى منفذ
        </h2>
        <h3>
            {{ $selectedPort ? $selectedPort->name : 'منفذ شحن' }}
        </h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>رقم العملية</th>
                <th>إسم الشحنة</th>
                <th>رقم البوليصة</th>
                <th>رقم الببان</th>
                <th>الخط الملاحي</th>
                <th>حاويات 20</th>
                <th>حاويات 40</th>
                <th>تاريخ وصول الباخرة المتوقعة</th>
                <th>فترة السماح</th>
                <th>تاريخ انتهاء فترة السماح</th>
                <th>نوع المستندات</th>
                <th>تاريخ استلام المستندات</th>
                <th>تاريخ الترحيل</th>
                <th>وجهة الترحيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $index => $shipment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $shipment->operationno }}</td>
                    <td>{{ $shipment->shippmintno ?? '-' }}</td>
                    <td>{{ $shipment->pilno }}</td>
                    <td>{{ $shipment->datano }}</td>
                    <td>{{ optional($shipment->shippingLine)->name ?? '-' }}</td>
                    <td>{{ $shipment->park20 }}</td>
                    <td>{{ $shipment->park40 }}</td>
                    <td>{{ $shipment->dategase ? $shipment->dategase->format('Y-m-d') : '-' }}</td>
                    <td>{{ $shipment->others ?? '-' }}</td>
                    <td>{{ $shipment->endallowdate ? $shipment->endallowdate->format('Y-m-d') : '-' }}</td>
                    <td>{{ $shipment->paperno }}</td>
                    <td>{{ $shipment->officedate ? $shipment->officedate->format('Y-m-d') : '-' }}</td>
                    <td>{{ $shipment->relaydate ? $shipment->relaydate->format('Y-m-d') : '-' }}</td>
                    <td>{{ $shipment->relayname }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
