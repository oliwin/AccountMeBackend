<?php
namespace App\Export;
use App\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class ReportReminds implements FromCollection, WithHeadings
{
    private $user_id;
	private $date;
	
	 public function headings(): array
    {
		
		return ["Номер счета", "Код счета", "Название счета", "Баланс"];
		
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
		$this->date = $request->date;
    }
	
    public function collection()
    {
		
        return DB::table('enterprise_invoces')
            ->select('enterprise_invoces.*', DB::raw("AT_code, SUM(IF(AT_amount > 0, AT_amount, 0)) AS Debit, SUM(IF(AT_amount < 0, AT_amount, 0)) AS Credit"))
            ->leftJoin('account_transactions', 'account_transactions.AT_code', '=', 'enterprise_invoces.AC_id')
			->where('AT_createdatetime', '<=', $this->date.' 00:00:00')
            ->where('enterprise_invoces.enterprise_id', $this->user_id)
            ->orderBy('AC_code')
            ->groupBy('enterprise_invoces.AC_id')
            ->get();
        
    }
}