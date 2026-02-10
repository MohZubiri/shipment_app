# Shipment Reports Enhancement Plan

## Objective
Suggest and implement reports for all types of shipments, filtered by company (Departement) separately.

## Current State
- Existing report: ShipmentTransaction report with filters for date, department, customs port, shipment name.
- Shipment types identified:
  - ShipmentTransaction (sea/air shipments, with ShipmentType)
  - LandShipping
  - LocalCustomsVehicle

## Proposed Reports
1. **Enhanced ShipmentTransaction Report**
   - Already exists, but ensure it can be filtered by department (company).
   - Add grouping by ShipmentType within the report.

2. **Land Shipping Report**
   - New report for LandShipping model.
   - Filters: date range, company (Departement), section, search.
   - Display fields: operation_number, shipment_name, declaration_number, arrival_date, company, department, current stage, documents.
   - PDF export.

3. **Local Customs Vehicles Report**
   - New report for LocalCustomsVehicle model.
   - Filters: date range, company (Departement), section, search.
   - Display fields: serial_number, vehicle_plate_number, user_name, arrival_date_from_branch, company, department.
   - PDF export.

4. **Summary Report by Company**
   - Aggregate report showing counts and summaries for all shipment types per company.
   - Columns: Company, ShipmentTransaction count, LandShipping count, LocalCustoms count, Total value, etc.

## Implementation Steps
1. Update ReportController with new methods: landShippingReport, landShippingReportPdf, localCustomsReport, localCustomsReportPdf, summaryReport.
2. Create corresponding Blade views in resources/views/admin/reports/.
3. Update routes in routes/web.php or admin routes.
4. Update the reports index view to include links to new reports.
5. Ensure PDF views are created for exports.

## Filters for All Reports
- Date from/to
- Company (Departement)
- Section (if applicable)
- Search (shipment name, number, etc.)

## Data Relationships
- Company: Departement model (labeled as "Company" in UI, but code uses departmentno or company_id)
- For ShipmentTransaction: departmentno
- For LandShipping: company_id
- For LocalCustomsVehicle: company_id

## Next Steps
- Review and approve this plan.
- Switch to Code mode to implement.