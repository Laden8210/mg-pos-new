<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector; // Change this based on your OS
use Exception;

class PrinterService
{
    protected $printer;

    public function __construct($printerName)
    {
        try {
            // Change the connector based on your OS and printer connection method
            $this->printer = new Printer(new WindowsPrintConnector($printerName));
        } catch (Exception $e) {
            // Handle any exceptions related to printer connection
            throw new Exception("Could not connect to printer: " . $e->getMessage());
        }
    }

    public function printInvoice(array $invoiceData)
    {
        try {
            // Set justification
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);

            // Print the invoice details
            $this->printer->text("INVOICE\n");
            $this->printer->text("================================\n");

            foreach ($invoiceData['items'] as $item) {
                $this->printer->text("Item: {$item['name']} - Qty: {$item['quantity']} - Price: {$item['price']}\n");
            }

            if (isset($invoiceData['discount'])) {
                $this->printer->text("Discount: {$invoiceData['discount']}\n");
            }

            $this->printer->text("Total: {$invoiceData['total']}\n");
            $this->printer->text("================================\n");
            $this->printer->cut();
            $this->printer->close();
        } catch (Exception $e) {
            // Handle any printing errors
            throw new Exception("Could not print invoice: " . $e->getMessage());
        }
    }
}
