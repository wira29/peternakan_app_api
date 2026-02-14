<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleGoatResource;
use App\Models\Cage;
use App\Models\Goat;
use App\Models\Location;
use App\Models\MilkSale;
use App\Models\SaleGoat;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $locationCount = Location::count();
        $cageCount = Cage::count();
        $cowCount = Goat::count();
        $amountCowSale = SaleGoat::whereMonth('date', now()->month)->sum('price');
        $amountMilkSale= MilkSale::whereMonth('sale_date', now()->month)->sum('total');
        $incomeThisMonth = $amountCowSale + $amountMilkSale;
        $expenseThisMonth = Goat::where('origin', 'buy')->whereMonth('date_of_purchase', now()->month)->sum('price') ;
        $saleHistories = SaleGoat::with('goat')->latest()->take(5)->get();
        
        return $this->sendResponse([
            'location_count' => $locationCount,
            'cage_count' => $cageCount,
            'cow_count' => $cowCount,
            'income_this_month' =>[
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
}
