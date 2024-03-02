<?php

namespace App\Exports;

use App\Models\TimeEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimeEntryExport implements FromView,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TimeEntry::where('date', '<', now()->startOfWeek())->where('date', '>=',  now()->subWeek()->startOfWeek())->get(['date','project_id','type_id', 'comment']);
    }

    public function headings(): array
    {
        return ['Date', 'Project', 'Type', 'Comment'];
    }

    public function view(): View
    {
        return view('exports.time-entries', [
            'timeEntries' => $this->collection()
        ]);
    }
}
