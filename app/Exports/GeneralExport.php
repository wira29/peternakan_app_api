<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GeneralExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $collection;
    protected $headings;
    protected $mapFields;
    protected $totalLabel;
    protected $totalColumn;

    public function __construct($collection, array $headings, array $mapFields, $totalLabel = null, $totalColumn = null)
    {
        $this->collection = $collection;
        $this->headings = $headings;
        $this->mapFields = $mapFields;
        $this->totalLabel = $totalLabel;   
        $this->totalColumn = $totalColumn;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $result = [];
        foreach ($this->mapFields as $field) {
            $result[] = data_get($row, $field, '-');
        }
        return $result;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                if ($this->totalColumn) {
                    $lastRow = $this->collection->count() + 1;
                    $totalRow = $lastRow + 1;
                    
                    $labelColumn = chr(ord($this->totalColumn) - 1);

                    $event->sheet->setCellValue("{$labelColumn}{$totalRow}", $this->totalLabel ?? 'TOTAL:');
                    
                    $event->sheet->setCellValue(
                        "{$this->totalColumn}{$totalRow}", 
                        "=SUM({$this->totalColumn}2:{$this->totalColumn}{$lastRow})"
                    );

                    $event->sheet->getStyle("{$labelColumn}{$totalRow}:{$this->totalColumn}{$totalRow}")
                                 ->getFont()->setBold(true);
                }
            },
        ];
    }
}