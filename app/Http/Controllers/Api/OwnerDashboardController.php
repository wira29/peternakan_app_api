<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoatResource;
use App\Http\Resources\MilkSaleResource;
use App\Http\Resources\SaleGoatResource;
use App\Models\Cage;
use App\Models\Goat;
use App\Models\Location;
use App\Models\MilkSale;
use App\Models\SaleGoat;
use App\Traits\ReportMetadata;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use Maatwebsite\Excel\Facades\Excel;

class OwnerDashboardController extends Controller
{
    use ReportMetadata;
    public function index()
    {
        $locationCount = Location::count();
        $cageCount = Cage::count();
        $cowCount = Goat::count();
        $amountCowSale = SaleGoat::whereMonth('date', now()->month)->sum('price');
        $amountMilkSale = MilkSale::whereMonth('sale_date', now()->month)->sum('total');
        $incomeThisMonth = $amountCowSale + $amountMilkSale;
        $expenseThisMonth = Goat::where('origin', 'buy')->whereMonth('date_of_purchase', now()->month)->sum('price');
        $saleHistories = SaleGoat::with('goat')->latest()->take(5)->get();

        return $this->sendResponse([
            'location_count' => $locationCount,
            'cage_count' => $cageCount,
            'cow_count' => $cowCount,
            'income_this_month' => [
                'cow_sales' => $amountCowSale,
                'milk_sales' => $amountMilkSale,
                'total' => $incomeThisMonth,
            ],
            'expense_this_month' => [
                'cow_purchases' => $expenseThisMonth,
                'total' => $expenseThisMonth,
            ],

            'recent_sales' => SaleGoatResource::collection($saleHistories),
        ], 'Successfully get owner dashboard data');
    }

    public function getSales(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $location_id = $request->query('location_id');

        $query = SaleGoat::with('goat')
            ->when($location_id, function ($query) use ($location_id) {
                $query->whereHas('goat', function ($q) use ($location_id) {
                    $q->where('location_id', $location_id);
                });
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            });

        $sales = $this->applySorting($query, $request, ['created_at',  'price'])->get();

        return $sales;
    }


    public function getMilkSales(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $location_id = $request->query('location_id');

        $query = MilkSale::with('location')
            ->when($location_id, function ($query) use ($location_id) {
                $query->where('location_id', $location_id);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('sale_date', $year);
            })
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('sale_date', $month);
            });

        $sales = $this->applySorting($query, $request, ['created_at', 'total'])->get();

        return $sales;
    }

    public function getPurchases(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $location_id = $request->query('location_id');

        $query = Goat::with('location')
            ->where('origin', 'buy')
            ->when($location_id, function ($query) use ($location_id) {
                $query->where('location_id', $location_id);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date_of_purchase', $year);
            })
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date_of_purchase', $month);
            });

        $purchases = $this->applySorting($query, $request, ['created_at', 'price'])->get();

        return $purchases;
    }

    public function reportSales(Request $request)
    {
        $sales = $this->getSales($request);
        $metadata = $this->generateMetadata($sales, $request, 'price');

        return $this->sendResponse([
            'report' => SaleGoatResource::collection($sales),
            'metadata' => $metadata
        ], 'Successfully retrieved sales report.');
    }

    public function reportMilkSales(Request $request)
    {
        $sales = $this->getMilkSales($request);

        return $this->sendResponse([
            'report' => MilkSaleResource::collection($sales),
            'metadata' => $this->generateMetadata($sales, $request, 'total')
        ], 'Successfully retrieved milk sales report.');
    }

    public function reportPurchasesCows(Request $request)
    {
        $purchases = $this->getPurchases($request);

        return $this->sendResponse([
            'report' => GoatResource::collection($purchases),
            'metadata' => $this->generateMetadata($purchases, $request, 'price')
        ], 'Successfully retrieved purchase cows report.');
    }

    public function reportSalesExport(Request $request)
    {
        try {
            $sales = $this->getSales($request);
            $headings = ['Tanggal', 'Kode Sapi', 'Kandang', 'Lokasi', 'Harga', 'Catatan',];
            $mapFields = ['date', 'goat.code', 'goat.cage.name', 'goat.location.location', 'price', 'note'];
            $filename = 'sales_report_' . date('d-m-Y_His') . '.xlsx';

            $totalLabel = 'Total Penjualan';
            $totalColumn = 'E'; // Koordinat kolom harga (E adalah kolom ke-5)
            \Log::info('Exporting sales report' . $filename . ' with ' . count($sales) . ' records');
            return Excel::download(new GeneralExport($sales, $headings, $mapFields, $totalLabel, $totalColumn), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting sales report: ' . $e->getMessage());
            return $this->sendError('Failed to export sales report', 500);
        }
    }

    public function reportPurchasesCowsExport(Request $request)
    {
        try {
            $purchases = $this->getPurchases($request);
            $headings = ['Tanggal Beli', 'Kode Sapi', 'Ras', 'Kandang', 'Lokasi', 'Harga', 'Catatan',];
            $mapFields = ['date_of_purchase', 'code','breed.name', 'cage.name', 'location.location', 'price', 'remarks'];
            $filename = 'purchases_report_' .  date('d-m-Y_His') . '.xlsx';
            $totalLabel = 'Total Pembelian';
            $totalColumn = 'F'; // Koordinat kolom harga (F adalah kolom ke-6)

            \Log::info('Exporting purchases report' . $filename . ' with ' . count($purchases) . ' records');
            return Excel::download(new GeneralExport($purchases, $headings, $mapFields, $totalLabel, $totalColumn), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting purchases report: ' . $e->getMessage());
            return $this->sendError('Failed to export purchases report: '. $e->getMessage(), 500);
        }
    }

    public function reportMilkSalesExport(Request $request)
    {
        try {
            $sales = $this->getMilkSales($request);
            $headings = ['Tanggal',  'Lokasi', 'Harga per Liter', 'Qty','Total', 'Catatan',];
            $mapFields = ['sale_date',  'location.location', 'price_per_liter', 'qty', 'total', 'note'];
            $filename = 'milk_sales_report_' . date('d-m-Y_His') . '.xlsx';

            $totalLabel = 'Total Penjualan';
            $totalColumn = 'E'; // Koordinat kolom harga (E adalah kolom ke-5)

            \Log::info('Exporting milk sales report' . $filename . ' with ' . count($sales) . ' records');
            return Excel::download(new GeneralExport($sales, $headings, $mapFields, $totalLabel, $totalColumn), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting milk sales report: ' . $e->getMessage());
            return $this->sendError('Failed to export milk sales report: '. $e->getMessage(), 500);
        }
    }
}
