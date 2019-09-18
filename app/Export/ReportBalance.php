<?php
namespace App\Export;
use App\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class ReportBalance implements FromCollection, WithHeadings
{
    
    private $user_id;
	private $from;
	private $to;
	
	 public function headings(): array
    {
		
		return ["Дата", "Номер счета", "Название счета", "Дебет", "Кредит"];
		
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
		
        $response = DB::select("select `account_transactions`.*, `enterprise_invoces`.*, SUM(IF(AT_amount>0, AT_amount, 0)) AS debit, SUM(IF(AT_amount<0, AT_amount, 0)) AS credit, 
		(select (SUM(IF(AT_amount>0, AT_amount, 0)) -  SUM(IF(AT_amount<0, AT_amount, 0))) as lft from account_transactions WHERE AT_createuser = ".$this->user_id." AND AT_createdatetime < '".$this->from."' group by `AT_transactionficheno`) as lft,
		(select (SUM(IF(AT_amount>0, AT_amount, 0)) -  SUM(IF(AT_amount<0, AT_amount, 0))) as lft from account_transactions WHERE AT_createuser = ".$this->user_id." AND AT_createdatetime > '".$this->to."' group by `AT_transactionficheno`) as rght
		from `account_transactions` inner join `enterprise_invoces` on `enterprise_invoces`.`AC_id` = `account_transactions`.`AT_code` where `AT_createuser` = ".$this->user_id." and date(`AT_createdatetime`) >= '".$this->from."' and date(`AT_createdatetime`) <= '".$this->to."' group by `AT_transactionficheno` order by `AT_createdatetime` desc");
    	
		return  collect($response);
	}
}