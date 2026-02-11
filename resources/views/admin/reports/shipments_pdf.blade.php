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
        <h2>التقرير اليومي لشركة التكامل الدولية</h2>
        @if(isset($selectedCompany) || isset($selectedDepartment) || isset($selectedPort))
            <h3>
                @if(isset($selectedCompany))
                    الشركة: {{ $selectedCompany->name }}
                @endif
                @if(isset($selectedDepartment))
                    {{ isset($selectedCompany) ? ' | ' : '' }}القسم: {{ $selectedDepartment->name }}
                @endif
                @if(isset($selectedPort))
                    {{ (isset($selectedCompany) || isset($selectedDepartment)) ? ' | ' : '' }}المنفذ الجمركي: {{ $selectedPort->name }}
                @endif
            </h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>رقم العملية</th>
                <th>إسم الشحنة</th>
                <th>رقم البوليصة</th>
                
                <th>رقم الببان</th>
                  <th>حالة الببان</th>
                <th>الخط الملاحي</th>
                <th>عدد الحاويات</th>
                <th>تاريخ وصول الباخرة المتوقعة</th>
                <th>فترة السماح</th>
                <th>تاريخ انتهاء فترة السماح</th>
                <th>نوع المستندات</th>
                <th>تاريخ استلام المستندات</th>
                   <th>مرحلة الشحنة الحالية </th>
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
                    <td>{{ $shipment->pillno ?: '-' }}</td>
                 
                    <td>{{ $shipment->datano }}</td>
                         <td>{{ (($shipment->customsData->state==1)?'ضمان ':'سداد') ?: '-' }}</td>
                    <td>{{ optional($shipment->shippingLine)->name ?? '-' }}</td>
                    @php
                        $containerCounts = $shipment->containers
                            ->filter(fn($container) => $container->container_size && $container->container_count)
                            ->groupBy('container_size')
                            ->map(fn($group) => $group->sum('container_count'));

                        if ($containerCounts->isEmpty()) {
                            $containerCounts = collect();
                            if (($shipment->park40 ?? 0) > 0) {
                                $containerCounts->put('40', $shipment->park40);
                            }
                            if (($shipment->park20 ?? 0) > 0) {
                                $containerCounts->put('20', $shipment->park20);
                            }
                        }

                        $preferredOrder = ['40', '40HC', '20'];
                        $orderedCounts = collect();
                        foreach ($preferredOrder as $size) {
                            if ($containerCounts->has($size)) {
                                $orderedCounts->put($size, $containerCounts->get($size));
                            }
                        }
                        $orderedCounts = $orderedCounts->union($containerCounts->diffKeys($orderedCounts));
                        $containerSummary = $orderedCounts->map(fn($count, $size) => "حاويات {$size} قدم عدد {$count}")->implode('<br>');
                    @endphp
                    <td>{!! $containerSummary !== '' ? $containerSummary : '-' !!}</td>
                    <td>{{ $shipment->dategase ? $shipment->dategase->format('Y-m-d') : '-' }}</td>
                    <td> {{ $shipment->shippingLine->time ?? '-' }}
                    </td>
                    <td> {{ $shipment->endallowdate ? $shipment->endallowdate->subDay()->format('Y-m-d') : '-' }}</td>
                    <td>{{ $shipment->paperno ?: '-' }}</td>
                    <td>{{ $shipment->officedate ? $shipment->officedate->format('Y-m-d') : '-' }}</td>
                    @php
                        $relayDate = $shipment->relaydate ?? $shipment->warehouseTracking?->event_date;
                        $relayDestination = $shipment->relayname ?: $shipment->warehouseTracking?->warehouse?->name;
                    @endphp
                     <td class="px-2 py-2 border border-gray-300">{{ $shipment->currentStage->name ?? '-' }}</td>
                    <td>{{ $relayDestination ?? '-' }}</td>
                     <td>{{ $relayDate ? $relayDate->format('Y-m-d') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
