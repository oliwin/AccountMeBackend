<?php

namespace App\Http\Controllers;

use App\EnterpriseInvoice;
use App\Exceptions\CustomValidationFail;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\TaxBalanceInvoice;
use App\TaxInvoice;
use App\Transactions;
use DB;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class InvoiceController extends Controller
{

    private function buildTree($items)
    {
        $childs = array();

        foreach ($items as $item) {
            $childs[$item->parent_id][] = $item;
        }

        foreach ($items as $item) {
            if (isset($childs[$item->AC_id])) {
                $item->children = $childs[$item->AC_id];
            }
        }

        return isset($childs[0]) ? $childs[0] : [];
    }

    public function balancetax()
    {
        $collection = TaxBalanceInvoice::get();

        return response()->json($collection);
    }

    public function oputax()
    {

        $collection = TaxInvoice::get();
        $_collection = $collection->map(function ($item, $key) {
            $item->name = $item->code . ' - ' . $item->name;
            return $item;
        })->toArray();

        return response()->json($_collection);

    }

    public function index()
    {

        $collection = DB::table('enterprise_invoces')
            ->select('enterprise_invoces.*', DB::raw("AT_code, SUM(IF(AT_amount > 0, AT_amount, 0)) AS Debit, SUM(IF(AT_amount < 0, AT_amount, 0)) AS Credit"))
            ->leftJoin('account_transactions', 'account_transactions.AT_code', '=', 'enterprise_invoces.AC_id')
            ->where('enterprise_invoces.enterprise_id', JWTAuth::user()->id)
            ->orderBy('AC_code')
            ->groupBy('enterprise_invoces.AC_id')
            ->get();

        $tree = $this->buildTree($collection);

        return response()->json($tree);

    }

    public function store(CreateInvoiceRequest $request)
    {

        $invoice = EnterpriseInvoice::create([
            'enterprise_id' => JWTAuth::user()->id,
            'parent_id' => $request->parentId ? $request->parentId : 0,
            'AC_code' => $request->code,
            'AC_name' => $request->name,
            'AC_fname' => $request->remark,
            'AC_type' => $request->type,
            'AC_group' => $request->group,
        ]);

        return response()->json($invoice);
    }

    public function show($id)
    {

        $invoice = EnterpriseInvoice::firstOrFail($id);

        return response()->json($invoice);
    }

    public function edit($id)
    {
        $invoice = EnterpriseInvoice::firstOrFail($id);

        return response()->json($invoice);
    }

    public function update(UpdateInvoiceRequest $request, $id)
    {

        if ($this->existInvoiceTransactions($id)) {
            throw new CustomValidationFail('Invoice can not be updated, because there are transactions!');
        }

        if ($this->existSubInvoice($id)) {
            throw new CustomValidationFail('Invoice can not be deleted, cause there are sub-invoices!');
        }

        $invoice = EnterpriseInvoice::where('AC_id', $id)->update([
            'AC_code' => $request->code,
            'AC_name' => $request->name,
            'AC_fname' => $request->remark,
            'AC_type' => $request->type,
            'AC_group' => $request->group,
        ]);

        return response()->json($invoice);
    }

    private function existSubInvoice($id)
    {

        return EnterpriseInvoice::where('parent_id', $id)->count() > 0;
    }

    private function existInvoiceTransactions($id)
    {
        return Transactions::where('AT_code', $id)->where("AT_createuser", JWTAuth::user()->id)->count() > 0;
    }

    public function destroy($id)
    {

        if ($this->existInvoiceTransactions($id)) {
            throw new CustomValidationFail('Invoice can not be deleted, cause there are transactions!');
        }

        if ($this->existSubInvoice($id)) {
            throw new CustomValidationFail('Invoice can not be deleted, cause there are sub-invoices!');
        }

        $invoice = EnterpriseInvoice::findOrFail($id)->delete();

        return response()->json($invoice);
    }

    private function sumTransactionBalance($invoice_code)
    {

        return Transactions::where('AT_code', $invoice_code)->sum('AT_amount');
    }

}
