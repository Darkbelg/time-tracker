<?php

namespace App\Exports;

use App\Models\TimeEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimeEntryExport implements FromView,WithHeadings
{
    public function __construct(private readonly Carbon $date)
    {
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Eager load the 'project', 'project.customers', and 'type' relationships
        $timeEntries = TimeEntry::with(['project', 'project.customers', 'type'])
            ->where('date', '>=', $this->date->clone()->startOfMonth())
            ->where('date', '<=', $this->date->clone()->endOfMonth())
            ->orderBy('date')
            ->get();

        // Transform the collection to the desired structure
        return $timeEntries->map(function ($entry) {
            return [
                'date' => $entry->date->format('d/m/Y'),
                'time' => $entry->time,
                'comment' => $entry->comment,
                'project_name' => $entry->project ? $entry->project->name : null,
                'customer_names' => $entry->project && $entry->project->customers ? $entry->project->customers->pluck('name')->join(', ') : null,
                'type_name' => $entry->type ? $entry->type->name : null,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Project', 'Type', 'Comment'];
    }

    public function view(): View
    {
        $user = auth()->user();
        $currentYear = date('Y');
        $lastMonthNumeric = date('m', strtotime('-1 month')); // Numeric representation with leading zero
        $lastMonthText = date('F', strtotime('-1 month')); // Full textual representation

        $lastMonth = $lastMonthNumeric . ' - ' . $lastMonthText;

        return view('exports.time-entries', [
            'user' => $user,
            'year' => $currentYear,
            'month' => $lastMonth,
            'timeEntries' => $this->collection()
        ]);
    }
}
