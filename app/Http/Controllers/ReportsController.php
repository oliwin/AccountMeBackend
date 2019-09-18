<?php

namespace App\Http\Controllers;

use App\Export\ReportBalance;
use App\Export\ReportReminds;
use App\Export\ReportSaldo;
use App\Export\ReportTransactions;
use App\Http\Requests\Reports\RemainsRequest;
use App\Http\Requests\Reports\SaldoRequest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportsController extends Controller
{

    public function remains(RemainsRequest $request)
    {

        $collection = DB::table('enterprise_invoces')
            ->select('enterprise_invoces.*', DB::raw("AT_code, ABS(SUM(IF(AT_amount > 0, AT_amount, 0))) AS Debit, ABS(SUM(IF(AT_amount < 0, AT_amount, 0))) AS Credit"))
            ->leftJoin('account_transactions', 'account_transactions.AT_code', '=', 'enterprise_invoces.AC_id')
            ->where('AT_transactiondatetime', '<=', $request->date . ' 00:00:00')
            ->where('enterprise_invoces.enterprise_id', JWTAuth::user()->id)
            ->orderBy('AC_code')
            ->groupBy('enterprise_invoces.AC_id')
            ->get();

        return response()->json($collection);

    }

    public function oputax(Request $request)
    {

        $benefit = DB::table('account_transactions')
            ->select('account_transactions.*', 'taxopuinvoices.*', DB::raw("ABS(SUM(AT_amount)) AS amount"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->join('taxopuinvoices', 'taxopuinvoices.id', '=', 'account_transactions.AT_oputax_id')
            ->whereBetween('taxopuinvoices.code', [1200, 1225])
            ->groupBy('AT_oputax_id')
            ->orderBy('code', 'asc');

        if ($request->has('from')) {
            $benefit->whereDate('AT_transactiondatetime', '>=', $request->from);
        }

        if ($request->has('to')) {
            $benefit->whereDate('AT_transactiondatetime', '<=', $request->to);
        }

        $benefit = $benefit->get();

        $expense = DB::table('account_transactions')
            ->select('account_transactions.*', 'taxopuinvoices.*', DB::raw("ABS(SUM(AT_amount)) AS amount"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->join('taxopuinvoices', 'taxopuinvoices.id', '=', 'account_transactions.AT_oputax_id')
            ->whereBetween('taxopuinvoices.code', [1226, 1238])
            ->groupBy('AT_oputax_id')
            ->orderBy('code', 'desc');

        if ($request->has('from')) {
            $expense->whereDate('AT_transactiondatetime', '>=', $request->from);
        }

        if ($request->has('to')) {
            $expense->whereDate('AT_transactiondatetime', '<=', $request->to);
        }

        $expense = $expense->get();

        $benefit_calc = DB::table('account_transactions')
            ->select('account_transactions.*', 'taxopuinvoices.*', DB::raw("ABS(SUM(AT_amount)) AS amount"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->join('taxopuinvoices', 'taxopuinvoices.id', '=', 'account_transactions.AT_oputax_id')
            ->whereBetween('taxopuinvoices.code', [1241, 1246])
            ->groupBy('AT_oputax_id')
            ->orderBy('code', 'asc');

        if ($request->has('from')) {
            $benefit_calc->whereDate('AT_transactiondatetime', '>=', $request->from);
        }

        if ($request->has('to')) {
            $benefit_calc->whereDate('AT_transactiondatetime', '<=', $request->to);
        }

        $benefit_calc = $benefit_calc->get();

        return response()->json(["benefit" => $benefit, "expenses" => $expense, "benefit_calc" => $benefit_calc]);
    }

    // Баланс налог //

    public function balancetax(Request $request)
    {

        $collection = DB::table('account_transactions')
            ->select('account_transactions.*', 'balance_tax_invoices.*', DB::raw("SUM(account_transactions.AT_amount) AS AT_amount"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->whereNotNull("AT_balance_tax_id")
            ->whereNotNull("AT_balance_tax_type_id")
            ->join('balance_tax_invoices', 'balance_tax_invoices.id', 'account_transactions.AT_balance_tax_id')
            ->orderBy('code', 'desc')
            ->groupBy('AT_balance_tax_id')
            ->groupBy('AT_balance_tax_type_id');

        if ($request->has('from')) {
            $collection->whereDate('AT_transactiondatetime', '>=', $request->from);
        }

        if ($request->has('to')) {
            $collection->whereDate('AT_transactiondatetime', '<=', $request->to);
        }

        $collection = $collection->get()->groupBy('code');

        return response()->json($collection);

    }

    public function saldo(SaldoRequest $request)
    {

        /* AT_OODE = env.AC_id */

        $collectiondDebit = DB::table('account_transactions')
            ->select('account_transactions.*', 'enterprise_invoces.*', DB::raw("ABS(SUM(IF(AT_amount>0, AT_amount, 0))) AS credit"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->whereDate('AT_transactiondatetime', '>=', $request->from)
            ->whereDate('AT_transactiondatetime', '<=', $request->to)
            ->where('AC_code', '>=', 601)
            ->where('AC_code', '<=', 641)
            ->join('enterprise_invoces', 'enterprise_invoces.AC_id', '=', 'account_transactions.AT_code')
            ->groupBy('AC_id')
            ->orderBy('AT_transactiondatetime', 'desc')
            ->get();

        $sqlNalog = DB::table('account_transactions')
            ->select('account_transactions.*', 'enterprise_invoces.*', DB::raw("SUM(IF(AT_amount<0, AT_amount, 0)) AS n"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->whereDate('AT_transactiondatetime', '>=', $request->from)
            ->whereDate('AT_transactiondatetime', '<=', $request->to)
            ->where('AC_code', '=', 901)
            ->join('enterprise_invoces', 'enterprise_invoces.AC_id', '=', 'account_transactions.AT_code')
            ->groupBy('AC_id')
            ->orderBy('AT_transactiondatetime', 'desc')
            ->first();

        $collectiondCredit = DB::table('account_transactions')
            ->select('account_transactions.*', 'enterprise_invoces.*', DB::raw("SUM(IF(AT_amount<0, AT_amount, 0)) AS credit"))
            ->where('AT_createuser', JWTAuth::user()->id)
            ->whereDate('AT_transactiondatetime', '>=', $request->from)
            ->whereDate('AT_transactiondatetime', '<=', $request->to)
            ->where('AC_code', '>=', 701)
            ->where('AC_code', '<=', 761)
            ->join('enterprise_invoces', 'enterprise_invoces.AC_id', '=', 'account_transactions.AT_code')
            ->groupBy('AC_id')
            ->orderBy('AT_transactiondatetime', 'desc')
            ->get();

        return response()->json(["debit" => $collectiondDebit, "nalog" => $sqlNalog, "credit" => $collectiondCredit]);
    }

    public function balance(SaldoRequest $request)
    {

        $sqlActiveInvoices = "SELECT AT_type, enterprise_invoces.AC_name, enterprise_invoces.AC_id, enterprise_invoces.AC_code,

        SUM(IF(AT_amount > 0, AT_amount, 0)) AS debit,

        ABS(SUM(IF(AT_amount < 0, AT_amount, 0))) AS credit,

        (SUM(IF(AT_amount > 0, AT_amount, 0))) - (ABS(SUM(IF(AT_amount < 0, AT_amount, 0)))) AS diff_debit,

        0 as diff_credit FROM account_transactions INNER JOIN

         enterprise_invoces ON enterprise_invoces.AC_id = account_transactions.AT_code WHERE

         AT_createuser = " . JWTAuth::user()->id . " AND enterprise_invoces.AC_type = 1

        AND (enterprise_invoces.AC_code BETWEEN 101 AND 245) OR (enterprise_invoces.AC_code BETWEEN 701 AND 761)

        AND date(`AT_transactiondatetime`) >= '" . $request->from . "' AND  date(`AT_transactiondatetime`) <= '" . $request->to . "' group by AC_id order by AT_createdatetime desc";

        /////////////////

        $sqlPassiveInvoices = "SELECT AT_type, enterprise_invoces.AC_name, enterprise_invoces.AC_id,

        enterprise_invoces.AC_code, SUM(IF(AT_amount > 0, AT_amount, 0)) AS debit,

        ABS(SUM(IF(AT_amount < 0, AT_amount, 0))) AS credit,

        (ABS(SUM(IF(AT_amount < 0, AT_amount, 0))) - SUM(IF(AT_amount > 0, AT_amount, 0))) AS diff_credit,

        0 as diff_debit FROM account_transactions INNER JOIN

        enterprise_invoces ON enterprise_invoces.AC_id = account_transactions.AT_code

        WHERE AT_createuser = " . JWTAuth::user()->id . " AND enterprise_invoces.AC_type = 2

         AND date(`AT_transactiondatetime`) >= '" . $request->from . "'

         AND (enterprise_invoces.AC_code BETWEEN 301 AND 445) OR (enterprise_invoces.AC_code BETWEEN 601 AND 641)

          AND date(`AT_transactiondatetime`) <= '" . $request->to . "' group by

          AC_id order by AT_createdatetime desc";

        $collectionActiveInvoices = DB::select($sqlActiveInvoices);

        $collectionPassiveInvoices = DB::select($sqlPassiveInvoices);

        return response()->json(['active' => $collectionActiveInvoices, 'passive' => $collectionPassiveInvoices]);
    }

    public function download(Request $request)
    {

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        $paths = explode('/', $request->file);

        $filename = end($paths);

        return Response::download(storage_path('/app/' . $request->file), $filename, $headers);

    }

    public function export(Request $request)
    {

        if ($request->type == null) {
            return;
        }

        $content;

        $path = 'reports/';

        $filename = 'report_' . date('d-m-y_h') . '.xlsx';

        if ($request->type == 'reminds') {

            $content = Excel::store(new ReportReminds($request), $path . $filename);

        } else if ($request->type == 'balance') {

            $content = Excel::store(new ReportBalance($request), $path . $filename);

        } else if ($request->type == 'saldo') {

            $content = Excel::store(new ReportSaldo($request), $path . $filename);

        } else if ($request->type == 'transactions') {

            $content = Excel::store(new ReportTransactions($request), $path . $filename);
        }

        return response()->json(["file" => $path . $filename]);

    }

}
