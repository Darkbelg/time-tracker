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
        // Eager load the 'project', 'project.customers', and 'type' relationships
        $timeEntries = TimeEntry::with(['project', 'project.customers', 'type'])
            ->where('date', '<', now()->startOfWeek())
            ->where('date', '>=', now()->subWeek()->startOfWeek())
            ->orderBy('date')
            ->get();

        // Transform the collection to the desired structure
        return $timeEntries->map(function ($entry) {
            return [
                // Directly access the 'date', 'time', and 'comment' from the TimeEntry model
                'date' => $entry->date->format('d/m/Y'), // Formatting the date for better readability
                'time' => $entry->time,
                'comment' => $entry->comment,

                // Accessing nested relationships can be a bit tricky.
                // You might want to check if the relationship exists to avoid null errors.
                'project_name' => $entry->project ? $entry->project->name : null,

                // For 'project.customers', since there could be multiple, you'll need to decide how you want to handle them.
                // Here's how you could concatenate customer names if there are multiple:
                'customer_names' => $entry->project && $entry->project->customers ? $entry->project->customers->pluck('name')->join(', ') : null,

                // Finally, accessing the 'type.name'.
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
