<?php

namespace App\Http\Controllers;

use App\Exports\TimeEntryExport;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TimeEntryExportController extends Controller
{

    public function __invoke()
    {

        return Excel::download(new TimeEntryExport(), 'TimeEntry.xlsx');
    }
}
