<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Models\Transaksi\LoggingUser;

class PostSaldoProdukDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stockotomatis:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Posting Saldo Harian Stok Otomatis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(300);
        $kdProfile = 1;
        
        DB::beginTransaction();
        try {
            $tglAyeuna = date('Y-m-d H:i:s');
            $tgl = date('Y-m-d 00:00:00');

            $details = DB::select(
                DB::raw("
            select * from saldoprodukdetail_t where tglsaldo=:tgl and kdprofile=$kdProfile "),
                array(
                    'tgl' => $tgl,
                )
            );

            if (count($details) > 0) {
                $transMessage = "Sudah Update";
                Log::info("Posting Saldo Harian Stok ".$transMessage);
            }
            $stfix = DB::table('settingdatafixed_m')
            ->where('namafield', '=', 'idAsalProdukPersediaan')
            ->where('statusenabled', '=', true)
            ->first();

            $aslProduk = 0;
            if($stfix) {
                $aslProduk = $stfix->nilaifield;
            }
            
            $data = DB::select(DB::raw("

            select objectruanganfk,objectprodukfk,harganetto2,tglkadaluarsa,objectasalprodukfk,
            sum(qtyproduk) as qty
            from stokprodukdetail_t where statusenabled='t' and qtyproduk <> 0
            GROUP BY objectruanganfk,objectprodukfk,harganetto2,tglkadaluarsa,objectasalprodukfk
            order by objectruanganfk;
        "));
            $dataInsert = [];
            foreach ($data as $item) {

                $dataInsert[] = array(
                    'norec' => Uuid::uuid4(),
                    'kdprofile' => $kdProfile,
                    'statusenabled' => true,
                    'objectasalprodukfk' =>  $item->objectasalprodukfk,
                    'harganetto' =>  $item->harganetto2,
                    'objectprodukfk' =>   $item->objectprodukfk,
                    'qtyproduk' =>   $item->qty,
                    'objectruanganfk' =>  $item->objectruanganfk,
                    'tglkadaluarsa' =>  $item->tglkadaluarsa,
                    'tglsaldo' => $tgl,
                    'tglclosing' => $tglAyeuna
                );
            }
            $chunkSize = 2000; // Adjust the chunk size based on your needs

            collect($dataInsert)->chunk($chunkSize)->each(function ($chunk) {
                DB::table('saldoprodukdetail_t')->insert($chunk->toArray());
            });
            $this->LOGGING(
                'Posting Saldo Harian Stok Otomatis',
                '',
                'stokprodukdetail_t',
                'Posting Saldo Harian Stok Otomatis Sukses Tanggal ' . $tglAyeuna
            );

            $transMessage = "Sukses";
            DB::commit();
            Log::info("Posting Saldo Harian Stok BERHASIL");
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            Log::info("Posting Saldo Harian Stok GAGAL");
        }
    }

    public function LOGGING($jenislog, $noreff, $referensi, $keterangan, $dataSend = null, $jsonRes = null)
    {
        $logUser = new LoggingUser();
        $logUser->norec = Uuid::uuid4();
        $logUser->kdprofile = 1;
        $logUser->statusenabled = true;
        $logUser->jenislog = $jenislog;
        $logUser->noreff = $noreff;
        $logUser->referensi = $referensi;
        $logUser->keterangan = $keterangan;
        $logUser->objectloginuserfk = 1;
        $logUser->tanggal = date('Y-m-d H:i:s');
        $logUser->namauser = 'SERVER';
        $logUser->namapegawai = 'SERVER';
        $logUser->data = $dataSend;
        $logUser->response = $jsonRes;
        $logUser->save();
    }
}
