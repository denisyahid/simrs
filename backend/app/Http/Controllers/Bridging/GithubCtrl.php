<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\LoggingUser;
use App\Traits\Valet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;
use Exception;

class GithubCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getLogCommit(Request $r){
       ;
        $headers['Content-Type']  = 'application/json';
        $headers['Authorization']  = 'Bearer '. $this->settingFix('tokenGitChangeLog');
        $url = $this->settingFix('urlGitChangeLog').'/commits?page='.$r['page'].'&per_page='.$r['limit'];
        $response = Http::withHeaders($headers)
        ->withoutVerifying()
        ->withOptions(["verify" => false])
        ->get($url);

        if ($response->ok()) {
            return $this->respond($response->json());
        }else{
            return $this->respond([]);
        }
       
    }
}

