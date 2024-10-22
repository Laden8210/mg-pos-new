<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockCard;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Employee; // Make sure this line is included
use Illuminate\Support\Facades\Hash;
use App\Models\Item;
use App\Http\Controllers\Income;
use App\Models\InventoryItem;
use App\Models\SaleTransactionModel;
use App\Models\Transactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



use \Carbon\Carbon;

class POSController extends Controller
{
    // Redirect to dashboard if user is authenticated, otherwise show login view
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the authenticated user's role
            $role = Auth::guard('employee')->user()->role;

            // Redirect based on the user's role
            if ($role === 'Manager') {
                return redirect()->route('dashboard'); // Manager's dashboard
            } elseif ($role === 'Cashier') {
                return redirect()->route('saletransaction'); // Cashier's dashboard
            }
        }

        // If not authenticated, return the login view
        return view('index'); // Assuming 'index' is your login view
    }

    public function showReorderList()
    {
        // Get inventory items where qtyonhand is less than or equal to reorder point
        $reorderItems = Inventory::with(['item', 'supplier']) // Eager load relationships
            ->get()
            ->filter(function ($item) {
                return $item->qtyonhand <= $item->getReorderPointAttribute(); // Filter based on reorder point
            });

        Log::info('Reorder Items:', $reorderItems->toArray()); // Log fetched items

        return view('reorder-list-report', compact('reorderItems'));
    }

    public function showOrderList(Request $request)
    {
        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;

        $purchasedOrders = PurchaseOrder::with(['supplier', 'items.item', 'items.inventory'])
        ->when($startDate, function ($query) use ($startDate) {
            return $query->where('delivery_date', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            return $query->where('delivery_date', '<=', $endDate);
        })->get();


        return view('report.order_list_report', compact('purchasedOrders'));
    }



    // Handle user login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Retrieve the employee record
        $employee = Employee::where('username', $request->username)->first();

        if ($employee) {
            // Check if the employee status is Inactive
            if ($employee->status === 'Inactive') {
                return redirect()->route('login')->with('error', 'Your account is inactive. Please contact support.');
            }

            // Check if the password matches
            $passwordMatches = Hash::check($request->password, $employee->password);

            if ($passwordMatches) {
                Auth::guard('employee')->login($employee);
                $request->session()->regenerate();

                // Store all relevant employee information in the session
                session([
                    'employee_name' => $employee->firstname . ' ' . $employee->lastname,
                    'employee_role' => $employee->role,
                    'employee_email' => $employee->username,
                    'employee_contact' => $employee->contact_number,
                    'employee_age' => $employee->age,
                    'employee_address' => $employee->address,
                    'employee_gender' => $employee->gender,
                    'employee_status' => $employee->status,
                    'employee_avatar' => $employee->avatar,
                ]);

                // Redirect based on role
                if ($employee->role === 'Manager') {
                    return redirect()->route('dashboard'); // Route for manager dashboard
                } elseif ($employee->role === 'Cashier') {
                    return redirect()->route('cashierdashboard'); // Route for cashier dashboard
                }
            }
        }
        return redirect()->route('login')->with('error', 'Invalid username or password. Please try again.');
    }
    public function showSalesReturnReport(Request $request)
    {

        $start_date = $request->input('start_date', now()->toDateString());
        $end_date   = $request->input('end_date', now()->toDateString());
        $stockCards = StockCard::with(['item'])
            ->where('Type', 'Sales Return')
            ->whereDate('DateReceived', $start_date)
            ->whereDate('DateReceived', $end_date)
            ->get();



        return view('report.sales_return_report', compact('stockCards'));
    }

    public function showInventoryAdjustment()
    {
        // Fetch inventory data
        $inventories = Inventory::with('item', 'supplier')->get(); // Assuming you have an Inventory model with item relationship

        // Pass inventories to the view
        return view('inventory.adjustment', compact('inventories'));
    }

    // public function showInventoryReport(Request $request)
    // {
    //     $filterDate = $request->input('filter_date', now()->toDateString());
    //     dd($filterDate);
    //     $salseStockCard = Inventory::whereDate('date_received', $filterDate);  // Fetch data from the database
    //     return view('report.inventory_report', compact('salseStockCard'));
    // }

    public function showInventoryReport(Request $request)
{
    // Retrieve 'filter_date' and 'start_date' inputs, or default to the current date
    $filterDate = $request->input('filter_date', now()->toDateString());
    $startDate = $request->input('start_date', now()->toDateString()); // Default to current date

    // Log the date filter used
    Log::info('Inventory report generated for dates:', [
        'start_date' => $startDate,
        'filter_date' => $filterDate,
    ]);

    // Fetch inventory data along with related items and suppliers
    $salesStockCard = Inventory::with(['item', 'supplier'])
        ->whereBetween('date_received', [$startDate, $filterDate]) // Filter by date range
        ->get();

    // Calculate reorder points based on a formula (e.g., 20% of the original quantity)
    foreach ($salesStockCard as $inventory) {
        if ($inventory->item && isset($inventory->item->original_quantity)) {
            // Assuming the reorder point is 20% of the original quantity
            $inventory->reorder_point = $inventory->item->original_quantity * 0.2;
        } else {
            // Default reorder point logic or error handling if 'original_quantity' is missing
            $inventory->reorder_point = 0; // Set to 0 or another fallback value
        }
    }

    // Return the view with the fetched data, including startDate and filterDate
    return view('report.inventory_report', compact('salesStockCard', 'startDate', 'filterDate'));
}






    public function showVatableItems()
    {
        // Fetch all vatable items from the database
        $vatableItems = Item::where('is_vatable', true) // Assuming 'is_vatable' is a column in your items table
            ->with('supplier') // Load supplier data if there's a relationship defined
            ->get();

        return view('inventory.vatable_items', compact('vatableItems'));
    }

    public function fetchVatableItems()
    {
        // Fetch your vatable items here
        $this->vatable = Item::where('isVatable', true)->get();

        // Log for debugging
        \Log::info('Vatable Items:', $this->vatable->toArray());
    }

    // Log out the user and invalidate the session
    public function logout(Request $request): RedirectResponse
    {
        // Perform logout
        Auth::guard('employee')->logout();

        // Clear all session data
        $request->session()->flush(); // This clears all session data

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }


    // Return the dashboard view for authenticated users
    public function dashboard(Request $request)
    {
        // Determine the selected year (default to the current year if none selected)
        $selectedYear = $request->input('year', date('Y')); // Default to the current year
        $previousYear = $selectedYear - 1;

        // Fetch the total revenue for the current and previous years using DateReceived
        $currentRevenue = StockCard::whereYear('DateReceived', $selectedYear)
            ->where('type', 'Sales')
            ->sum('Value');

        $previousRevenue = StockCard::whereYear('DateReceived', $previousYear)
            ->where('type', 'Sales')
            ->sum('Value');

        // Calculate growth percentage
        $growthPercentage = $previousRevenue ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;

        // Array of years for the dropdown
        $years = range(date('Y'), date('Y') - 5); // Adjust range as necessary

        // Fetch the total number of items from the database
        $totalItems = Item::count();

        // Fetch the total income from the stock card, filtering by sales type
        $totalIncome = StockCard::where('type', 'Sales')->sum('Value'); // Ensure 'Value' is in uppercase to match your database column

        // Fetch total sales count
        $totalSales = StockCard::where('type', 'Sales')->sum('Quantity');

        // Fetch the most selling items
        $mostSellingItems = StockCard::with(['item'])
            ->select('supplierItemID', DB::raw('SUM(Value) as sales'), DB::raw('MAX(DateReceived) as last_sold_date'))
            ->groupBy('supplierItemID')
            ->orderBy('sales', 'desc')
            ->take(10)
            ->get();

        // Pass all relevant data to the view
        return view('dashboard', [
            'totalItems' => $totalItems,
            'totalIncome' => $totalIncome,
            'totalSales' => $totalSales,
            'mostSellingItems' => $mostSellingItems,
            'currentYear' => $selectedYear,  // Pass the selected year
            'previousYear' => $previousYear,  // Pass the previous year
            'currentRevenue' => $currentRevenue, // Pass current year revenue
            'previousRevenue' => $previousRevenue, // Pass previous year revenue
            'growthPercentage' => $growthPercentage, // Pass growth percentage
            'years' => $years, // Pass years to the view
        ]);
    }



    public function mostSellingItems()
    {
        $mostSellingItems = StockCard::with(['item'])
            ->select('supplierItemID', DB::raw('SUM(Value) as sales'), DB::raw('MAX(DateReceived) as last_sold_date'))
            ->groupBy('supplierItemID')
            ->orderBy('sales', 'desc')
            ->take(10)
            ->get();

        // Debug: Log the most selling items
        \Log::info('Most Selling Items:', $mostSellingItems->toArray());

        return view('report.most_selling_items', compact('mostSellingItems'));
    }

    public function cashierDashboard()
    {
        return view('cashierdashboard');
    }
    // User management view
    public function user()
    {
        $employees = Employee::paginate(10);
        return view('user.user_management', compact('employees'));
    }

    // Supplier information view
    public function supplier()
    {
        return view('supplier.supplier_information');
    }

    // Order supplies view
    public function orderSupplies()
    {
        return view('supplier.order_supplies');
    }

    // Delivery records view
    public function delivery()
    {
        return view('account.delivery_records');
    }

    // Item management view
    public function itemManagement()
    {
        return view('item.item_management');
    }

    // Inventory management view
    public function inventoryManagement()
    {
        return view('inventory.inventory_management');
    }

    // Inventory adjustment view
    public function inventoryAdjustment()
    {
        return view('inventory.inventory_adjustment');
    }

    // Activity log view
    public function activityLog()
    {
        return view('account.activity_log');
    }

    // Inventory report view
    public function inventoryReport(Request $request)
    {
        // Retrieve necessary data and pass it to the view
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $salseStockCard = InventoryItem::with(['item', 'inventory', 'inventory.supplier', 'purchaseItem'])
            ->whereDate('received_date', '>=', $startDate)
            ->whereDate('received_date', '<=', $endDate)
            ->get();

        return view('report.inventory_report', compact('salseStockCard'));
    }


    // Reorder list report view
    public function reorderListReport(Request $request)
    {

        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        // Fetch all inventory items
        $inventoryItems = InventoryItem::with(['item', 'inventory.supplier', 'purchaseItem'])
        ->whereBetween('received_date', [$startDate, $endDate])
        ->get();

        // Filter items that have reached the reorder point
        $reorderItems = $inventoryItems->filter(function ($item) {
            return $item->qtyonhand <= ($item->original_quantity * 0.3);
        });

        return view('report.reorder_list_report', compact('reorderItems'));
    }

    // Fast moving items report view
    public function fastMovingItemReport()
    {
        return view('report.fast_moving_item_report');
    }

    // Slow moving items report view
    public function slowMovingItemReport()
    {
        return view('report.slow_moving_item_report');
    }
    public function transactionsHistoryReport(Request $request)
    {
        $filterDate = $request->input('filter_date', now()->toDateString());
        $startDate = $request->input('start_date', now()->toDateString()); // New start date input

        $stockCards = StockCard::with(['item'])
            ->whereIn('Type', ['Sales', 'SalesReturn'])
            ->whereDate('DateReceived', '>=', $startDate) // Filter by start date
            ->whereDate('DateReceived', '<=', $filterDate) // Filter by end date
            ->get();

        return view('report.transaction_history_report', compact('stockCards'));
    }

    public function salesReport(Request $request)
    {
        $filterDate = $request->input('filter_date', now()->toDateString());
        $startDate = $request->input('start_date', now()->toDateString()); // New start date input

        $saleTransaction = SaleTransactionModel::with('transactions.item')->whereDate('created_at', '>=', $startDate) // Filter by start date
            ->whereDate('created_at', '<=', $filterDate) // Filter by end date
            ->get();

        return view('report.sales_report', compact('saleTransaction'));
    }
    // Stock movement report view
    public function stockMovementReport(Request $request)
    {

        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $stockCards = StockCard::with(['item'])
            ->whereBetween('DateReceived', [$startDate, $endDate])
            ->get();


        return view('report.stock_movement_report', compact('stockCards'));
    }

    // Expiration report view
    public function expirationReport()
    {
        return view('report.expiration_report');
    }

    // Sales return report view
    public function salesReturnReport()
    {
        return view('report.sales_return_report');
    }



    public function salereturn()
    {
        return view('cashier.sale-return'); // Ensure this view includes the Livewire component
    }

    public function saletransaction()
    {
        return view('cashier.sale-transaction');
    }

    // User information view (for user accounts)
    public function userInformation()
    {
        return view('user.user_account');
    }


}
