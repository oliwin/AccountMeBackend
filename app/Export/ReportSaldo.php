<?php
namespace App\Export;
use App\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class ReportSaldo implements FromCollection, WithHeadings
{
    private $user_id;
	private $from;
	private $to;
	
	 public function headings(): array
    {
		return ["Код счета", "Название счета", "Остаток на начало", "Дебет", "Кредит", "Остаток на конец"];
		
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
		$this->from = $request->from;
		$this->to = $request->to;
    }
    public function collection()
    {
		
		return DB::table('account_transactions')
			->select('account_transactions.*', 'enterprise_invoces.*', DB::raw("SUM(IF(AT_amount>0, AT_amount, 0)) AS debit, SUM(IF(AT_amount<0, AT_amount, 0)) AS credit"))
			->where('AT_createuser', $this->user_id)
			->whereDate('AT_createdatetime', '>=', $this->from)
			->whereDate('AT_createdatetime', '<=', $this->to)
			->groupBy('AT_transactionficheno')
			->join('enterprise_invoces', 'enterprise_invoces.AC_id', '=', 'account_transactions.AT_code')
			->orderBy('AT_createdatetime', 'desc')
			->get();
     
    }
}