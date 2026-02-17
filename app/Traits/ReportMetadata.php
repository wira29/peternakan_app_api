<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ReportMetadata
{
    /**
     * Helper untuk membuat metadata standar laporan di berbagai controller.
     */
    public function generateMetadata($collection, Request $request, $sumColumn = null)
    {
        return [
            'generated_at' => now()->toDateTimeString(),
            'filters' => [
                'month' => $request->query('month') ?? 'all',
                'year' => $request->query('year') ?? 'all',
                'location_id' => $request->query('location_id') ?? 'all',
            ],
            'sorting' => [
                'sort_by' => $request->query('sort_by', 'created_at'),
                'sort_order' => $request->query('sort_order', 'desc'),
            ],
            'total_records' => $collection->count(),
            'total_amount' => $sumColumn ? (float) $collection->sum($sumColumn) : 0
        ];
    }

    public function applySorting($query, Request $request, array $allowedColumns = ['created_at'])
    {
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }

        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortOrder);
    }
}
