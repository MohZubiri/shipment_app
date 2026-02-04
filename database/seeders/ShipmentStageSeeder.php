<?php

namespace Database\Seeders;

use App\Models\ShipmentStage;
use Illuminate\Database\Seeder;

class ShipmentStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            [
                'name' => 'تصنيع',
                'name_en' => 'Manufacturing',
                'code' => 'manufacturing',
                'order' => 1,
                'icon' => 'factory',
                'color' => '#6366f1',
                'description' => 'مرحلة تصنيع البضائع في المصدر',
            ],
            [
                'name' => 'مغادرة المصنع',
                'name_en' => 'Factory Departure',
                'code' => 'factory_departure',
                'order' => 2,
                'icon' => 'truck',
                'color' => '#8b5cf6',
                'description' => 'خروج البضائع من المصنع',
            ],
            [
                'name' => 'في الميناء المصدر',
                'name_en' => 'Source Port',
                'code' => 'source_port',
                'order' => 3,
                'icon' => 'anchor',
                'color' => '#3b82f6',
                'description' => 'البضائع في ميناء المصدر',
            ],
            [
                'name' => 'في الطريق',
                'name_en' => 'In Transit',
                'code' => 'in_transit',
                'order' => 4,
                'icon' => 'ship',
                'color' => '#0ea5e9',
                'description' => 'البضائع في الطريق إلى الوجهة',
            ],
            [
                'name' => 'وصول الميناء',
                'name_en' => 'Port Arrival',
                'code' => 'port_arrival',
                'order' => 5,
                'icon' => 'anchor',
                'color' => '#10b981',
                'description' => 'وصول البضائع للميناء',
            ],
            [
                'name' => 'الجمارك',
                'name_en' => 'Customs Clearance',
                'code' => 'customs',
                'order' => 6,
                'icon' => 'file-text',
                'color' => '#f59e0b',
                'description' => 'إجراءات الجمارك',
            ],
            [
                'name' => 'إفراج',
                'name_en' => 'Released',
                'code' => 'released',
                'order' => 7,
                'icon' => 'check-circle',
                'color' => '#22c55e',
                'description' => 'إفراج البضائع من الجمارك',
            ],
            [
                'name' => 'نقل للمخزن',
                'name_en' => 'Transport to Warehouse',
                'code' => 'transport',
                'order' => 8,
                'icon' => 'truck',
                'color' => '#f97316',
                'description' => 'نقل البضائع إلى المخزن',
            ],
            [
                'name' => 'وصول المخزن',
                'name_en' => 'Warehouse Arrival',
                'code' => 'warehouse',
                'order' => 9,
                'icon' => 'warehouse',
                'color' => '#84cc16',
                'description' => 'وصول البضائع للمخزن',
            ],
        ];

        foreach ($stages as $stage) {
            ShipmentStage::updateOrCreate(
                ['code' => $stage['code']],
                $stage
            );
        }
    }
}
