<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StockCard;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\Transactions;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
class InvoiceController extends Controller
{
    // protected $printerService;

    // public function __construct(PrinterService $printerService)
    // {
    //     $this->printerService = $printerService;
    // }
    public function printInvoice($items_list, $subtotal, $total, $discount, $amountTendered, $change)
    {
        // Decode the JSON string into a PHP array
        $itemsList = json_decode($items_list, true);

        // Pass the decoded array, total, amount tendered, and change to the view
        $pdf = Pdf::loadView('report.sales-invoice', [
            'items' => $itemsList,
            'subtotal' => $subtotal,
            'total' => $total,
            'discount' => $discount,
            'amountTendered' => $amountTendered,
            'change' => $change
        ]);

        // Download the generated PDF
        return $pdf->stream('invoice.pdf');
    }

    public function printOrderReceipt($purchase_number)
    {

        $purchaseOrder = PurchaseOrder::with(['supplier', 'items.item'])
            ->where('purchase_number', $purchase_number)
            ->first();

        $employee = Auth::user()->firstname . ' '. Auth::user()->lastname;

        $pdf = Pdf::loadView('supplier.purchaseOrderReceipt', compact('purchaseOrder', 'employee'));

        // Stream the generated PDF

        return $pdf->stream('purchase_order.pdf');
    }


    public function generateSalesReport(Request $request)
    {

        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;


        $transactions = Transactions::with('item')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('date_created', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('date_created', '<=', $endDate);
            })
            ->get();



        $pdf = Pdf::loadView('report.salesReport', ['transactions' => $transactions]);

        // Stream the generated PDF
        return $pdf->stream('sales_report.pdf');
    }

    public function generateInventoryReport(Request $request)
    {
        // Get startDate and endDate from the request, or set defaults
        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;


        // Fetch inventory data
        $inventoryQuery = Inventory::with(['item', 'supplier', 'stockCards'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('date_received', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('date_received', '<=', $endDate);
            });

        $inventoryData = $inventoryQuery->get();


        // Load the view with the correct variable name
        $pdf = Pdf::loadView('report.InventoryReport', [
            'salesStockCard' => $inventoryData, // Corrected variable name
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        // Download the generated PDF
        return $pdf->stream('inventory_report.pdf');
    }


    public function generateStockMovementReport(Request $request){


        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $stockCards = StockCard::with(['item'])
            ->whereBetween('DateReceived', [$startDate, $endDate])
            ->get();

            $pdf = Pdf::loadView('report.StockMovementReport', [
                'stockCards' => $stockCards, // Corrected variable name

            ]);

            // Download the generated PDF
            return $pdf->stream('stock-movement.pdf');

    }


    public function generateReorderListReport(Request $request)
    {


        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;

        // Fetch all inventory items
        $inventoryItems = Inventory::with(['item', 'supplier'])->get();

        // Filter items that have reached the reorder point
        $reorderItems = $inventoryItems->filter(function ($item) {
            return $item->qtyonhand <= ($item->original_quantity * 0.3);
        });
        $pdf = Pdf::loadView('report.reorder-list-report', ['reorderItems' => $reorderItems]);

        // Download the generated PDF
        return $pdf->stream('reorder_list_report.pdf');
    }

    public function generateOrderListReport(Request $request)
    {

        $startDate = $request->input('start_date') ?? null;
        $endDate = $request->input('end_date') ?? null;

        $purchaseOrders = PurchaseOrder::with(['supplier', 'items.item', 'items.inventory'])
        ->when($startDate, function ($query) use ($startDate) {
            return $query->where('delivery_date', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            return $query->where('delivery_date', '<=', $endDate);
        })->get();


        $pdf = Pdf::loadView('report.order-list-report', ['purchasedOrders' => $purchaseOrders]);

        // Download the generated PDF
        return $pdf->stream('order_list_report.pdf');
    }

    public function generateTransactionHistoryReport()
    {

        $stockCards = StockCard::with(['item'])
            ->whereIn('Type', ['Sales', 'SalesReturn'])
            ->get();

        $pdf = Pdf::loadView('report.transactionHistoryReport', ['stockCards' => $stockCards]);

        // Download the generated PDF
        return $pdf->stream('transaction_history_report.pdf');
    }

    public function generateSalesReturnReport(Request $request)
    {

        $start_date = $request->input('start_date', now()->toDateString());
        $end_date   = $request->input('end_date', now()->toDateString());
        $stockCards = StockCard::with(['item'])
            ->where('Type', 'SalesReturn')
            ->whereDate('DateReceived', $start_date)
            ->whereDate('DateReceived', $end_date)
            ->get();

        $pdf = Pdf::loadView('report.salesReturnReport', ['stockCards' => $stockCards]);

        // Download the generated PDF
        return $pdf->stream('transaction_history_report.pdf');
    }
}
