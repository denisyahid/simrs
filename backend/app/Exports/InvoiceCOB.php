<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class InvoiceCOB extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithColumnFormatting
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
        
        return view('report.kasir.billing-excel-cob',compact('profile', 'pageWidth', 'print', 'res', 'data', 'user'));
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_0,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}