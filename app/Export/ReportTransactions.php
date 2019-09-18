<?php
namespace App\Export;
use App\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class ReportTransactions implements FromCollection, WithHeadings
{
    private $user_id;
	
	 public function headings(): array
    {
		return ["Номер транзакции", "Дата создания", "Тип транзакции", "Дебет", "Кредит"];
		
        /*return [
             Lang::get('excel.idEvent'),
             Lang::get('excel.event'),
			 Lang::get('excel.event_date'),
			 Lang::get('excel.created_at'),
			 Lang::get('excel.status')
        ];*/
    }
    public function __construct(Request $request)
    {
        $this->user_id = JWTAuth::user()->id;

    }
    public function collection()
    {
		
		return DB::table('account_transactions')
		->select('account_transactions.*', DB::raw("SUM(IF(AT_amount>0, AT_amount, 0)) AS debit, SUM(IF(AT_amount<0, AT_amount, 0)) AS credit"), 'transactions_type.name AS typeName')
		->where('AT_createuser', $this->user_id)
		->groupBy('AT_transactionficheno')
		->leftJoin('transactions_type', 'transactions_type.id', '=', 'account_transactions.AT_type')
		->get();
     
    }
}