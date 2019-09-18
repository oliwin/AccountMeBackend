<?php

namespace App\Http\Controllers;

use App\Transactions;
use DB;
use Illuminate\Http\Request;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{

    public function index()
    {

        $collection = DB::table('account_transactions')
            ->select('account_transactions.*', DB::raw("ABS(SUM(IF(AT_amount>0, AT_amount, 0))) AS debit, ABS(SUM(IF(AT_amount<0, AT_amount, 0))) AS credit"), 'transactions_type.name AS typeName', 'enterprise_invoces.AC_name')
            ->where('AT_createuser', JWTAuth::user()->id)
            ->groupBy('AT_transactionficheno')
            ->leftJoin('enterprise_invoces', 'enterprise_invoces.AC_id', '=', 'account_transactions.AT_code')
            ->leftJoin('transactions_type', 'transactions_type.id', '=', 'account_transactions.AT_type')
            ->get();

        return response()->json($collection);

    }

    private function getYearFromDate($date)
    {

        $parsed = explode("-", $date);

        return isset($parsed[0]) ? $parsed[0] : '';
    }

    public function store(Request $request)
    {

        $commonPart = [
            'AT_transactionyear' => $this->getYearFromDate($request->date),
            'AT_transactiondatetime' => $request->date,
            'AT_type' => $request->type,
            'AT_transactionficheno' => $this->getLastIdTransaction(true),
            'AT_projectcode' => 1,
        ];

        $transactionsRows = [];

        foreach ($request['transactions'] as $key => $transaction) {
            $in = [
                'AT_createuser' => JWTAuth::user()->id,
                'AT_transactiondescription' => $request->has('remark') ? $request->remark : (!empty($transaction['remark']) ? $transaction['remark'] : null),
                'AT_amount' => $this->getAmout($transaction),
                'AT_code' => $transaction['invoice']['AC_id'],
                'AT_balance_tax_id' => empty($transaction['balancetaxTypes']) ? null : $transaction['balancetaxTypes'],
                'AT_oputax_id' => empty($transaction['oputax']) ? null : $transaction['oputax'],
                'AT_balance_tax_type_id' => empty($transaction['balancetaxTypesNames']) ? null : $transaction['balancetaxTypesNames'],
            ];
            $transactionsRows[] = array_merge($commonPart, $in);
        }

        $inserted = Transactions::insert($transactionsRows);

        return response()->json($inserted);
    }

    private function getAmout($transaction)
    {

        if ($transaction["credit"] > 0) {
            return $transaction["credit"];
        }

        if ($transaction["debit"] > 0) {
            return -$transaction["debit"];
        }

    }

    public function show($AT_transactionficheno)
    {

        $transaction = Transactions::where('AT_transactionficheno', $AT_transactionficheno)
            ->join('enterprise_invoces', 'enterprise_invoces.AC_id', 'account_transactions.AT_code')
            ->get();

        return response()->json($transaction);
    }

    public function create(Request $request)
    {

        $lastTransaction = Transactions::latest()->first();

        $lastTransactionId = $lastTransaction->AT_id + 1;

        return response()->json([
            'date' => new Date(),
            'fiche' => $lastTransactionId,
            'project_code' => 1,
        ]);
    }

    public function edit($id)
    {
        $transaction = Transactions::firstOrFail($id);

        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $request = $request->toArray();
        $transactions = $request['transactions'];
        $common = $request['common'];

        foreach ($transactions as $k => $value) {
            if (!empty($value['id'])) {
                $updated[] = Transactions::where('AT_id', $value['id'])->update([
                    'AT_amount' => $value['amount'],
                    'AT_transactiondescription' => $value['remark'],
                    'AT_type' => $common['type'],
                    'AT_lastupdateuser' => date('Y'),
                ]);
            }
        }

        return response()->json($updated);

    }

    public function getLastIdTransaction($format = false)
    {

        $id = 1;
        $transaction = Transactions::orderBy('AT_transactionficheno', 'desc')->first();

        if ($transaction) {
            $id = $transaction->AT_transactionficheno + 1;
        }

        if ($format) {
            return $id;
        }

        return response()->json(["id" => $id]);
    }

    public function destroy($id)
    {
        $transaction = Transactions::findOrFail($id)->delete();

        return response()->json($transaction);
    }
}
