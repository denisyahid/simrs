<?php

namespace App\Services;

use App\Traits\PelayananPasienTrait;
use App\Traits\Valet;

use Artisaninweb\SoapWrapper\SoapWrapper;
// use App\Soap\Request\GetConversionAmount;
// use App\Soap\Response\GetConversionAmountResponse;

class BridgingBPDService
{

  use Valet, PelayananPasienTrait;
  /**
   * @var SoapWrapper
   */
  protected $soapWrapper;
  protected $username;
  protected $password;
  protected $instansi;
  protected $url;

  /**
   * SoapController constructor.
   *
   * @param SoapWrapper $soapWrapper
   */
  public function __construct(SoapWrapper $soapWrapper)
  {
    $this->soapWrapper = $soapWrapper;
    // $this->username = 'RSUD_MANGUSADA';
    // $this->password = 'M4ngus4DA80351!';
    $this->username = $this->settingDataFixed('usernameBPD',36);
    $this->password = $this->settingDataFixed('passwordBPD',36);
    $this->url = $this->settingDataFixed('urlBPD',36);
    $this->instansi = 'RS_TRANSMEDIC';
  }

  /**
   * Use the SoapWrapper
   */
  public function ws_echo_test() 
  {
    $this->soapWrapper->add('ws_echo_test', function ($service) {
      $service
        ->wsdl($this->url)
        ->trace(true);
    });

    $response = $this->soapWrapper->call('ws_echo_test.ws_echo_test', [
      'username' => $this->username,
      'password' => $this->password
    ]); 

    return $response;
  }

  public function ws_tagihan_insert($noid, $nama, $tagihan, $noregistrasi, $tglpulang, $alamat, $notelp) 
  {
    $this->soapWrapper->add('ws_tagihan_insert', function ($service) {
      $service
        ->wsdl($this->url)
        ->trace(true);
    });

    $response = $this->soapWrapper->call('ws_tagihan_insert.ws_tagihan_insert', [
      'username' => $this->username,
      'password' => $this->password,
      'noid' => $noid,
      'nama' => $nama,
      'tagihan' => $tagihan,
      'instansi' => $this->instansi,
      'ket_1_val' => $noregistrasi,
      'ket_2_val' => $tglpulang,
      'ket_3_val' => $alamat,
      'ket_4_val' => $notelp,

    ]); 

    return $response;
  }

  public function ws_inquiry_tagihan($noid) 
  {
    $this->soapWrapper->add('ws_inquiry_tagihan', function ($service) {
      $service
        ->wsdl($this->url)
        ->trace(true);
    });

    $response = $this->soapWrapper->call('ws_inquiry_tagihan.ws_inquiry_tagihan', [
      'username' => $this->username,
      'password' => $this->password,
      'instansi' => $this->instansi,
      'noid' => $noid

    ]); 

    return $response;
  }

  public function ws_tagihan_delete_by_id($noid) 
  {
    $this->soapWrapper->add('ws_tagihan_delete_by_id', function ($service) {
      $service
        ->wsdl($this->url)
        ->trace(true);
    });

    $response = $this->soapWrapper->call('ws_tagihan_delete_by_id.ws_tagihan_delete_by_id', [
      'username' => $this->username,
      'password' => $this->password,
      'instansi' => $this->instansi,
      'noid' => $noid

    ]); 

    return $response;
  }

  public function ws_laporan_payment_detail_setelah_no_bukti($tanggal, $nobukti) 
  {
    $this->soapWrapper->add('ws_laporan_payment_detail_setelah_no_bukti', function ($service) {
      $service
        ->wsdl($this->url)
        ->trace(true);
    });

    $response = $this->soapWrapper->call('ws_laporan_payment_detail_setelah_no_bukti.ws_laporan_payment_detail_setelah_no_bukti', [
      'username' => $this->username,
      'password' => $this->password,
      'instansi' => $this->instansi,
      'tanggal' => $tanggal,
      'nobukti' => $nobukti

    ]); 

    return $response;
  }
}