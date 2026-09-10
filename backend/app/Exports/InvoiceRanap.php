<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class InvoiceRanap extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    use Exportable;
    protected $profile;
    protected $pagewidth;
    protected $print;
    protected $res;
    protected $data;
    protected $user;

    public function __construct($profile,$pagewidth,$print,$res, $data, $user) {
        $this->profile = $profile;
        $this->pagewidth = $pagewidth;
        $this->print = $print;
        $this->res = $res;
        $this->data = $data;
        $this->user = $user;
    }

    public function view(): View
    {
        $profile = $this->profile;
        $pageWidth = $this->pagewidth;
        $print = $this->print;
        $res = $this->res;
        $data = $this->data;
        $user = $this->user;
        
        return view('report.kasir.billing-excel',compact('profile', 'pageWidth', 'print', 'res', 'data', 'user'));
    }
}