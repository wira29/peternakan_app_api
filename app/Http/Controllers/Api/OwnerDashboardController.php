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

    public function reportSales(Request $request)
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

        $metadata = $this->generateMetadata($sales, $request, 'price');

        return $this->sendResponse([
            'report' => SaleGoatResource::collection($sales),
            'metadata' => $metadata
        ], 'Successfully retrieved sales report.');
    }

    public function reportMilkSales(Request $request)
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

        return $this->sendResponse([
            'report' => MilkSaleResource::collection($sales),
            'metadata' => $this->generateMetadata($sales, $request, 'total')
        ], 'Successfully retrieved milk sales report.');
    }

    public function reportPurchasesCows(Request $request)
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

        return $this->sendResponse([
            'report' => GoatResource::collection($purchases),
            'metadata' => $this->generateMetadata($purchases, $request, 'price')
        ], 'Successfully retrieved purchase cows report.');
    }
}
