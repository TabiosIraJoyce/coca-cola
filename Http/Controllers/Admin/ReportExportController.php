<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use PDF;
use League\Csv\Writer;
use SplTempFileObject;

class ReportExportController extends Controller
{
    public function exportPDF(Request $request)
    {
        $type = $request->report_type;
        $division = $request->division_id;

        $reports = Report::where('division_id', $division)
                         ->where('report_type', $type)
                         ->get();

        $view = match($type) {
            'receipts'     => 'exports.pdf.receipts',
            'remittance'   => 'exports.pdf.remittance',
            'receivables'  => 'exports.pdf.receivables',
            'borrowers'    => 'exports.pdf.borrowers',
        };

        $pdf = PDF::loadView($view, compact('reports'));
        return $pdf->setPaper('A4', 'landscape')->download($type.'-report.pdf');
    }


    public function exportCSV(Request $request)
    {
        $type = $request->report_type;
        $division = $request->division_id;

        $reports = Report::where('division_id', $division)
                         ->where('report_type', $type)
                         ->get();

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        // HEADERS DEPENDING ON TYPE
        $headers = match($type) {
            'receipts'     => ['Route','Leadman','Gross Sales','Net Sales','Total Remittance'],
            'remittance'   => ['Bank','Account #','Check Date','Amount','Total'],
            'receivables'  => ['Customer','Amount','CRI','Check No.','Remarks'],
            'borrowers'    => ['Item','Borrowed','Returned','Location'],
        };

        $csv->insertOne($headers);

        foreach ($reports as $r) {
            $csv->insertOne($r->toArray());
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$type.'.csv"',
        ]);
    }
}
