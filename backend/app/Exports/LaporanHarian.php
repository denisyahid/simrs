<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class LaporanHarian extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithColumnWidths
{
    use Exportable;
    protected $penerimaan;
    protected $nonlayanan;
    protected $deposit;
    protected $piutang;
    protected $tglAwal;
    protected $profile;
    protected $views;
    protected $userKasir;

    public function __construct($penerimaan,$nonlayanan,$deposit,$piutang, $tglAwal, $tglAkhir,$profile, $views = '', $userKasir) {
        $this->penerimaan = $penerimaan;
        $this->nonlayanan = $nonlayanan;
        $this->deposit = $deposit;
        $this->piutang = $piutang;
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->profile = $profile;
        $this->views = $views;
        $this->userKasir = $userKasir;
    }

    public function view(): View
    {
        $penerimaan = $this->penerimaan;
        $nonlayanan = $this->nonlayanan;
        $deposit = $this->deposit;
        $piutang = $this->piutang;
        $tglAwal = $this->tglAwal;
        $tglAkhir = $this->tglAkhir;
        $profile = $this->profile;
        $views = $this->views;
        $userKasir = $this->userKasir;
        
        return view('report.kasir.laporan-penerimaan-kasir-excel', compact('penerimaan', 'tglAwal', 'tglAkhir', 'profile', 'nonlayanan', 'deposit', 'piutang','userKasir'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
        ];
    }
}