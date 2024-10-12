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
use App\Models\SaleTransactionModel;
use Illuminate\Support\Facades\Auth;
class InvoiceController extends Controller
{
    // protected $printerService;

    // public function __construct(PrinterService $printerService)
    // {
    //     $this->printerService = $printerService;
    // }
    public function printInvoice($invoice)

    {
        // Fetch the invoice data
        $invoiceData = SaleTransactionModel::with('transactions', 'transactions.item')
            ->where('transaction_number', $invoice)
            ->first();

        $pdf = Pdf::loadView('report.sales-invoice', compact('invoiceData'));

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

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;



        $saleTransaction = SaleTransactionModel::with('transactions.item')->whereDate('created_at', '>=', $start_date) // Filter by start date
            ->whereDate('created_at', '<=', $end_date) // Filter by end date
            ->get();



        $pdf = Pdf::loadView('report.salesReport',compact('saleTransaction', 'start_date', 'end_date'));

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
            'salseStockCard' => $inventoryData, // Corrected variable name
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        // Download the generated PDF
        return $pdf->stream('inventory_report.pdf');
    }


    public function generateStockMovementReport(Request $request){


        $start_date = $request->input('start_date', now()->toDateString());
        $end_date = $request->input('end_date', now()->toDateString());

        $stockCards = StockCard::with(['item'])
            ->whereBetween('DateReceived', [$start_date, $end_date])
            ->where('Type', '!=', 'Sales Return')
            ->get();


            $pdf = Pdf::loadView('report.StockMovementReport', [
    // Corrected variable name
                'stockCards' => $stockCards,  // Correct variable name
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            // Download the generated PDF
            return $pdf->stream('stock-movement.pdf');

    }


    public function generateReorderListReport(Request $request)
    {


        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        // Fetch all inventory items
        $inventoryItems = Inventory::with(['item', 'supplier'])->get();

        // Filter items that have reached the reorder point
        $reorderItems = $inventoryItems->filter(function ($item) {
            return $item->qtyonhand <= ($item->original_quantity * 0.3);
        });
        $pdf = Pdf::loadView('report.reorder-list-report',  compact('reorderItems', 'start_date', 'end_date'));

        // Download the generated PDF
        return $pdf->stream('reorder_list_report.pdf');
    }

    public function generateOrderListReport(Request $request)
    {

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        $purchasedOrders = PurchaseOrder::with(['supplier', 'items.item', 'items.inventory'])
        ->when($start_date, function ($query) use ($start_date) {
            return $query->where('delivery_date', '>=', $start_date);
        })
        ->when($end_date, function ($query) use ($end_date) {
            return $query->where('delivery_date', '<=', $end_date);
        })->get();


        $pdf = Pdf::loadView('report.order-list-report',  compact('purchasedOrders', 'start_date', 'end_date'));

        // Download the generated PDF
        return $pdf->stream('order_list_report.pdf');
    }

    public function generateTransactionHistoryReport()
    {

        $stockCards = StockCard::with(['item'])
            ->whereIn('Type', ['Sales', 'SalesReturn'])
            ->get();

        $pdf = Pdf::loadView('report.transactionHistoryReport',  compact('stockCards', 'start_date', 'end_date'));

        // Download the generated PDF
        return $pdf->stream('transaction_history_report.pdf');
    }

    public function generateSalesReturnReport(Request $request)
    {

        $start_date = $request->input('start_date', now()->toDateString());
        $end_date   = $request->input('end_date', now()->toDateString());
        $stockCards = StockCard::with(['item'])
            ->where('Type', 'Sales Return')
            ->whereDate('DateReceived', $start_date)
            ->whereDate('DateReceived', $end_date)
            ->get();

        $pdf = Pdf::loadView('report.salesReturnReport', compact('stockCards', 'start_date', 'end_date'));

        // Download the generated PDF
        return $pdf->stream('transaction_history_report.pdf');
    }
}
