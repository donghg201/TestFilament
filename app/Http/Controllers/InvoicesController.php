<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicesController extends Controller
{
    public function generatePdf(Employees $employee)
    {
        dd($employee);
        $employee = new Buyer([
            'name'          => $employee->name,
            'custom_fields' => [
                'email' => $employee->email,
            ],
        ]);

        $item = InvoiceItem::make('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
            ->buyer($employee)
            ->discountByPercent(10)
            ->taxRate(15)
            ->shipping(1.99)
            ->addItem($item);

        return $invoice->stream();
    }
}
