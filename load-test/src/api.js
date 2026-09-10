import { URL } from "https://jslib.k6.io/url/1.0.0/index.js";
import { uuidv4 } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";
import http from "k6/http";
import {
  objToQueryString,
  formatDateUrlQuery as formatDate,
  formatDateOnlyUrlQuery,
} from "./utils.js";

// export const baseURL = "http://127.0.0.1:3002";
export const baseURL = "https://simrsbm-test.baliprov.go.id";


export function generateToken() {
  return http.post(`${baseURL}/service/auth/login`, {
    namaUser: "his.registrasi",
    kataSandi: "nasilengko",
  });
}

// testing rsud bali mandara

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */

export function getListMenu(opts) {
  return http.get(
    `${baseURL}/service/general/menu/list-menu?idUser=46`,
    { headers: { "token": opts.token } }
  );
}

export function storeNotif(data, opts) {
  return http.post(
    `${baseURL}/service/general/store-notif`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveAntrianBaru(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/kiosk/save-antrian-baru`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getJumlahLoket(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/kiosk/get-jumlah-loket`,
    { headers: { "token": opts.token } }
  );
}

export function cetakAntrian(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/report/cetak-antrian?pdf=true&norec=057391a9-9342-494a-97ae-bcaa86129f16`,
    { headers: { "token": opts.token } }
  );
}

export function getDataPasienLama(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/kiosk/get-pasien/000486/null`,
    { headers: { "token": opts.token } }
  );
}

export function getJadwalPoli(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/kiosk/get-ruangan?loketid=1`,
    { headers: { "token": opts.token } }
  );
}

export function getJadwalDokter(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/kiosk/get-dokterbyruangan?objectruanganfk=201`,
    { headers: { "token": opts.token } }
  );
}

export function getDoubleRegis(opts) {
  return http.get(
    `${baseURL}/service/registrasi/pasien-hari-ini?nocmfk=486`,
    { headers: { "token": opts.token } }
  );
}

export function saveAntrianLama(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/kiosk/save-antrian`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveAdministrasi(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/save-adminsitrasi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getSettingPrinter(opts) {
  return http.get(
    `${baseURL}/service/general/printer?device=&namaexternal=ANTRIAN%20LOKET`,
    { headers: { "token": opts.token } }
  );
}

export function getDetailReservasi(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/reservasionline/get-history?noReservasi=16.89.75&cekin=true`,
    { headers: { "token": opts.token } }
  );
}

export function getDashboardRegistrasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi?ruanganfk=&tgl=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownRegistrasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi/dropdown`,
    { headers: { "token": opts.token } }
  );
}

export function listPasienGrid(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-pasien-grid?page=1&dari=2024-10-21%2000:00&sampai=2024-10-21%2023:59&limit=50&pasien_aktif=true`,
    { headers: { "token": opts.token } }
  );
}

export function getCountDaftar(opts) {
  return http.get(
    `${baseURL}/service/registrasi/count-daftar?dari=2024-10-21&sampai=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function getPasienReservasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi/list-pasien-reservasi?_total=true&dari=2024-10-01%2000:00&sampai=2024-10-21%2023:59&offset=0&limit=6&rows=50&pasien_aktif=true&kelompokpasienfk=&instalasifk=`,
    { headers: { "token": opts.token } }
  );
}

export function listDropdownRegistrasi(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-dropdown`,
    { headers: { "token": opts.token } }
  );
}

export function getUserNIK(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getUserNoka(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function savePasienBaru(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/save-pasien`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getPasienRegistrasiRev(opts) {
  return http.get(
    `${baseURL}/service/registrasi/pasien-registrasi-rev?id=bd37ea6a-97ba-4a76-9b8e-7b397b3c07d1`,
    { headers: { "token": opts.token } }
  );
}

export function getListRuangan(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-ruangan-ri-rj?query=poli%20penyakit%20dalam`,
    { headers: { "token": opts.token } }
  );
}

export function getKelompokPasien(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-kelompokpasien-all`,
    { headers: { "token": opts.token } }
  );
}

export function getAsalRujukan(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-asalrujukan-pasien`,
    { headers: { "token": opts.token } }
  );
}

export function getPenjaminKelompok(opts) {
  return http.get(
    `${baseURL}/service/registrasi/penjamin-by-kelompokpasien?id=2`,
    { headers: { "token": opts.token } }
  );
}

export function getDokter(opts) {
  return http.get(
    `${baseURL}/service/registrasi/dokter-paging?name=dr.%20&limit=10`,
    { headers: { "token": opts.token } }
  );
}

export function getKelasRuangan(opts) {
  return http.get(
    `${baseURL}/service/registrasi/kelas-by-ruangan?id=304`,
    { headers: { "token": opts.token } }
  );
}

export function saveRegistrasi(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/save-registrasi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getPemakaianAsuransi(opts) {
  return http.get(
    `${baseURL}/service/registrasi/pemakaian-asuransi?id=169718&norec_pd=9c013ba7-d596-45ed-97b7-3a1fea399739&norec_apd=`,
    { headers: { "token": opts.token } }
  );
}

export function getSubSpesialis(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRujukanPeserta(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getPPK(opts) {
  return http.get(
    `${baseURL}/service/general/ppk-bpjs`,
    { headers: { "token": opts.token } }
  );
}

export function getSKDP(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getDiagnosa(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function savePemakaianAsuransi(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/pemakaian-asuransi/save`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getPasienLamaReg(opts) {
  return http.get(
    `${baseURL}/service/registrasi/pasien-lama`,
    { headers: { "token": opts.token } }
  );
}

export function cetakSEP(opts) {
  return http.get(
    `${baseURL}/service/registrasi/pemakaian-asuransi/sep?pdf=true&nosep=0233R7791024V002751`,
    { headers: { "token": opts.token } }
  );
}

export function cetakBuktiPendaftaran(opts) {
  return http.get(
    `${baseURL}/service/report/bukti-pendaftaran?pdf=true&noregistrasi=2410070672`,
    { headers: { "token": opts.token } }
  );
}

export function cetakLabelPasien(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=2410070672`,
    { headers: { "token": opts.token } }
  );
}

export function cetakIdentitasPasien(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi/cetak-identitas-pasien?pdf=true&noregistrasi=2410070672`,
    { headers: { "token": opts.token } }
  );
}

export function suratRegisRanap(data, opts) {
  return http.post(
    `${baseURL}/service/dashboard/registrasi/save-surat-regis-ranap`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getIsBaby(opts) {
  return http.get(
    `${baseURL}/service/laporan/check-is-baby?nocmfk=169749`,
    { headers: { "token": opts.token } }
  );
}

export function getDashboard(opts) {
  return http.get(
    `${baseURL}/service/dashboard/rawat-jalan-pasien?dari=2024-10-21&sampai=2024-10-21&ruanganfk=&page=1&limit=50`,
    { headers: { "token": opts.token } }
  );
}

export function getDashboardKonsul(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-jumlah-konsul?idpegawai=2`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownRajal(opts) {
  return http.get(
    `${baseURL}/service/dashboard/dropdown-rawat-jalan`,
    { headers: { "token": opts.token } }
  );
}

export function getDashboardReservasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/rawat-jalan-reservasi?dari=2024-10-21&sampai=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownPegawaiRegis(opts) {
  return http.get(
    `${baseURL}/service/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`,
    { headers: { "token": opts.token } }
  );
}

export function changeDPJP(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/change-dokter-dpjp`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getHeaderPasien(opts) {
  return http.get(
    `${baseURL}/service/emr/header-pasien?nocmfk=77&norec_pd=409346e6-ddac-4e41-aec7-d4ce90e47913&norec_apd=d38e6fa4-91ea-4959-a636-0c76292b4c35&dari=2022-10-24&sampai=2024-12-31&limit=5&offset=0`,
    { headers: { "token": opts.token } }
  );
}

export function getTotalBilling(opts) {
  return http.get(
    `${baseURL}/service/emr/total-biliing?noregistrasi=2410210002`,
    { headers: { "token": opts.token } }
  );
}

export function getRiwayatPelayanan(opts) {
  return http.get(
    `${baseURL}/service/emr/detail-pelayanan?norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f&nocmfk=168512`,
    { headers: { "token": opts.token } }
  );
}

export function getVitalSign(opts) {
  return http.get(
    `${baseURL}/service/emr/get-data-exist?nocmfk=168512`,
    { headers: { "token": opts.token } }
  );
}

export function getEMR(opts) {
  return http.get(
    `${baseURL}/service/emr/get-emr?nocmfk=168512&norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f&collection=AsesmenAwalKeperawatanPasienRawatJalan&emrpasienfk=`,
    { headers: { "token": opts.token } }
  );
}

export function getEMRTerakhir(opts) {
  return http.get(
    `${baseURL}/service/emr/get-emr-tgl-terakhir?nocmfk=168512&collection=AsesmenAwalKeperawatanPasienRawatJalan`,
    { headers: { "token": opts.token } }
  );
}

export function saveEMRTemplate(data, opts) {
  return http.post(
    `${baseURL}/service/emr/simpan-emr-template`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveEMR(data, opts) {
  return http.post(
    `${baseURL}/service/emr/simpan-emr`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getCPPT(opts) {
  return http.get(
    `${baseURL}/service/emr/get-emr-cppt?nocmfk=168512&collection=CatatanPerkembanganPasienTerintegrasi&flag=perawat`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownDiagnosa(opts) {
  return http.get(
    `${baseURL}/service/diagnosa/list-dropdown`,
    { headers: { "token": opts.token } }
  );
}

export function getAutoFill(opts) {
  return http.get(
    `${baseURL}/service/emr/auto-fill?norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f&collection=VitalSign&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2`,
    { headers: { "token": opts.token } }
  );
}

export function getRiwayatKunjungan(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-dropdown-registrasi?nocmfk=168512`,
    { headers: { "token": opts.token } }
  );
}

export function getListTindakan(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-tindakan`,
    { headers: { "token": opts.token } }
  );
}

export function getStatusClose(opts) {
  return http.get(
    `${baseURL}/service/general/get-status-close?key=3561f89d-6194-436f-92fc-0dc5cda13e8f`,
    { headers: { "token": opts.token } }
  );
}

export function getKomponenHargaRegis(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-tindakan-komponen?idRuangan=201&idKelas=6&idProduk=3378&idJenisPelayanan=1&idPenjamin=0&objectkebangsaanfk=1`,
    { headers: { "token": opts.token } }
  );
}

export function saveTindakanPoli(data, opts) {
  return http.post(
    `${baseURL}/service/tindakan/save-tindakan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getListPaket(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-paket`,
    { headers: { "token": opts.token } }
  );
}

export function getTindakanOrder(opts) {
  return http.get(
    `${baseURL}/service/laboratorium/list-tindakan-for-order?ruanganfk=335`,
    { headers: { "token": opts.token } }
  );
}

export function saveOrder(data, opts) {
  return http.post(
    `${baseURL}/service/laboratorium/simpan-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRiwayatOrder(opts) {
  return http.get(
    `${baseURL}/service/laboratorium/riwayat-order?nocmfk=168512&norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f`,
    { headers: { "token": opts.token } }
  );
}

export function getDetailOrder(opts) {
  return http.get(
    `${baseURL}/service/laboratorium/detail-order?norec=a9747ba0-2aba-412d-9bc0-ed13d86b3783`,
    { headers: { "token": opts.token } }
  );
}

export function getRiwayatKontrol(opts) {
  return http.get(
    `${baseURL}/service/pasien/get-riwayat-kontrol?nocmfk=141132&norec_pd=1cf3001f-fcac-4450-99bf-0adcce22a36a&dari=2024-10-21&sampai=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function getListRuanganRajal(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-ruangan-rawat-jalan?query=poli`,
    { headers: { "token": opts.token } }
  );
}

export function getJadwalDokterPoli(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/kiosk/get-dokterbyruangan-semuatgl?objectruanganfk=332`,
    { headers: { "token": opts.token } }
  );
}

export function saveSuratKontrol(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function createRiwayatKontrol(data, opts) {
  return http.post(
    `${baseURL}/service/pasien/create-riwayat-kontrol`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getAutoFillBedah(opts) {
  return http.get(
    `${baseURL}/service/bedah/get-data-autofill-bedah?norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f`,
    { headers: { "token": opts.token } }
  );
}

export function saveOrderBedah(data, opts) {
  return http.post(
    `${baseURL}/service/bedah/simpan-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRiwayatOrderBedah(opts) {
  return http.get(
    `${baseURL}/service/bedah/riwayat-order?nocmfk=b4658043-09f4-4b39-83c4-38a410ca641f&norec_pd=37c2c324-6813-49e7-8016-f096b2aaef17`,
    { headers: { "token": opts.token } }
  );
}

export function getDetailOrderBedah(opts) {
  return http.get(
    `${baseURL}/service/bedah/detail-order?norec=f8eb8089-fa53-49b2-a883-2fb96327d246`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownInputResep(opts) {
  return http.get(
    `${baseURL}/service/farmasi/input-resep-cbo?ruanganfk=201&departemenfk=18`,
    { headers: { "token": opts.token } }
  );
}

export function getOrderResepHariIni(opts) {
  return http.get(
    `${baseURL}/service/farmasi/data-order-resep-hari-ini?norec=3561f89d-6194-436f-92fc-0dc5cda13e8f`,
    { headers: { "token": opts.token } }
  );
}

export function getDetailObat(opts) {
  return http.get(
    `${baseURL}/service/farmasi/get-produkdetail-ceklis?ruanganfk=324&limit=1000&namaproduk=&kodeproduk=&kpid=1&norec_apd=84bf2340-2b2a-4b8b-9576-5125346df2fa`,
    { headers: { "token": opts.token } }
  );
}

export function saveOrderObat(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/simpan-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRiwayatOrderResep(opts) {
  return http.get(
    `${baseURL}/service/farmasi/riwayat-order-resep?nocmfk=168512&verif=false&norec_pd=3561f89d-6194-436f-92fc-0dc5cda13e8f`,
    { headers: { "token": opts.token } }
  );
}

export function hapusOrderResep(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/hapus-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getTransferPasien(opts) {
  return http.get(
    `${baseURL}/service/emr/get-order-konsul?nocm=169719&noregistrasi=2410080010&tglAwal=2024-10-27&tglAkhir=2024-10-27`,
    { headers: { "token": opts.token } }
  );
}

export function getListKelas(opts) {
  return http.get(
    `${baseURL}/service/emr/dropdown/kelas_m?select=id,namakelas`,
    { headers: { "token": opts.token } }
  );
}

export function getMasterJadwal(opts) {
  return http.get(
    `${baseURL}/service/sysadmin/master-jadwal-dokter?objectruanganfk=202&tanggal=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function savePasienKonsul(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/simpan-pasien-konsul`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveBatalPasienKonsul(data, opts) {
  return http.post(
    `${baseURL}/service/emr/hapus-order-konsul`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getDashboardKasir(opts) {
  return http.get(
    `${baseURL}/service/dashboard/kasir?dari=2024-10-21&sampai=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function getTagihanPasien(opts) {
  return http.get(
    `${baseURL}/service/dashboard/kasir/list-tagihan-pasien?&dari=2024-10-21&sampai=2024-10-21&search=`,
    { headers: { "token": opts.token } }
  );
}

export function getPasienPulang(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-pasien-pulang?tglAwal=2024-10-21&tglAkhir=2024-10-21&offset=0&limit=5&rows=50&namaPasien=SLAMET%20HERMANTO`,
    { headers: { "token": opts.token } }
  );
}

export function getNonLayanan(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-tagihan-non-layanan?&dari=2024-10-21&sampai=2024-10-21&search=`,
    { headers: { "token": opts.token } }
  );
}

export function getPasienAktif(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-pasien-aktif?&dari=2024-10-21&sampai=2024-10-21&search=&search=169996&jenis=Rawat%20Inap&offset=0&limit=5&rows=50`,
    { headers: { "token": opts.token } }
  );
}

export function getPiutangPasien(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-pasien-aktif?&dari=2024-10-21&sampai=2024-10-21&search=&search=169996&jenis=Rawat%20Inap&offset=0&limit=5&rows=50`,
    { headers: { "token": opts.token } }
  );
}

export function getPenerimaanKasirRev(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-penerimaan?&dari=2024-10-01&sampai=2024-10-21&namapasien=&nocm=&noreg=&carabayar=&ins=&ruang=&ruangkasir=&search=170084&kasirArr=&offset=0&limit=5&rows=50`,
    { headers: { "token": opts.token } }
  );
}

export function getPengeluaranKasirRev(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-pengeluaran?&dari=2024-10-01&sampai=2024-10-03&namapasien=&nocm=&noreg=&carabayar=&ins=&ruang=&kasirArr=&offset=0&limit=5&rows=50`,
    { headers: { "token": opts.token } }
  );
}

export function getDepositPasien(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-deposit-pasien?tglAwal=2024-10-01&tglAkhir=2024-10-05`,
    { headers: { "token": opts.token } }
  );
}

export function getTindakanNonLayanan(opts) {
  return http.get(
    `${baseURL}/service/kasir/tagihan-non-layanan/pelayanan?namaproduk=visite&limit=10`,
    { headers: { "token": opts.token } }
  );
}

export function getPembayaranTagihan(opts) {
  return http.get(
    `${baseURL}/service/kasir/pembayaran-tagihan?norec=16ec7636-c8cb-4454-9985-5b183f98288b&norec_pd=94cda121-bfa0-4455-b9c2-6a1a7fcfbb69`,
    { headers: { "token": opts.token } }
  );
}

export function getKwitansiRJWNA(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-penerimaan/report/kwitansi-rajal-wna?noregistrasi=2410270002&rekap=true&user=User%20Administrator%20&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ1c2VyLmthc2lyIiwiZXhwIjoxNzMwMDQxNTk5fQ._IScPwFbQQxA1j9fZwHwXs_h2qrZ5-8mXIaxd6FtSjAqTKJup6DKRrf9rAwKFYgsqLNcc7ZMdECyknwjsBK05Q.MQ==`,
    { headers: { "token": opts.token } }
  );
}

export function getKwitansiRI(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-penerimaan/report/kwitansi-ranap-wna?noregistrasi=2410270002&rekap=true&user=User%20Administrator%20&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ1c2VyLmthc2lyIiwiZXhwIjoxNzMwMDQxNTk5fQ._IScPwFbQQxA1j9fZwHwXs_h2qrZ5-8mXIaxd6FtSjAqTKJup6DKRrf9rAwKFYgsqLNcc7ZMdECyknwjsBK05Q.MQ==`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownPenerimaan(opts) {
  return http.get(
    `${baseURL}/service/kasir/daftar-penerimaan/dropdown`,
    { headers: { "token": opts.token } }
  );
}

export function getPenerimaanHarian(opts) {
  return http.get(
    `${baseURL}/service/report/kasir/laporan-penerimaan-harian?pdf=true&tglAwal=2024-10-21&tglAkhir=2024-10-21&idKasir=undefined&idRuangan=&user=Administrator%20SIMRS&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMua2FzaXIiLCJleHAiOjE3Mjk1MDkyODR9.aetsWxAdmc-peN7qhO7x1uTOXGwRmSyu1CEBf_048SB8QEv8p2uIS4kC0TSE2QhYWYrTTImc7w8X7PmHIK60EQ.MQ==`,
    { headers: { "token": opts.token } }
  );
}

export function getBillingKasir(opts) {
  return http.get(
    `${baseURL}/service/kasir/billing?norec_pd=d037ec35-8d27-48a9-93aa-45700fa4c027`,
    { headers: { "token": opts.token } }
  );
}

export function getPetugasTindakan(opts) {
  return http.get(
    `${baseURL}/service/kasir/billing/petugas-tindakan?norec=3166fc98-e5d0-4ce2-acbc-dec8a31e66ab`,
    { headers: { "token": opts.token } }
  );
}

export function getJenisPetugas(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-map-jenis-petugas?idJenisPetugas=4`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownKonversi(opts) {
  return http.get(
    `${baseURL}/service/kasir/billing/tagihan-konversi-dropdown`,
    { headers: { "token": opts.token } }
  );
}

export function getTagihanKonversi(opts) {
  return http.get(
    `${baseURL}/service/kasir/billing/tagihan-konversi?norec_pd=c45d29f7-51c0-4c63-9f37-8a3897fcabd6`,
    { headers: { "token": opts.token } }
  );
}

export function saveTglTindakan(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/update-tgl-tindakan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function deleteJenisPetugas(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/delete-jenis-petugas`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveJenisPetugas(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/save-jenis-petugas`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function updateHargaKonversi(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/update-harga-konversi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveNonLayanan(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/tagihan-non-layanan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function savePembayaranTagihan(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/pembayaran-tagihan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveCaraBayar(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/daftar-penerimaan/ubah-cara-bayar`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveCaraBayarRev(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/daftar-penerimaan/ubah-cara-bayar`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveVerifikasiTagihan(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/verifikasi-tagihan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveBatalPulang(data, opts) {
  return http.post(
    `${baseURL}/service/rawatinap/batal-pulang-pasien`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveBatalBayar(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/daftar-penerimaan/batal-bayar`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveHapusTindakan(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/hapus-tindakan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveHargaKonversi(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/billing/simpan-harga-konversi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function saveClosing(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/closing-pemeriksaan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function savePengembalianDeposit(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/pembayaran-tagihan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getDashboardEResep(opts) {
  return http.get(
    `${baseURL}/service/dashboard/apotik?tglAwal=2024-10-01&tglAkhir=2024-10-21&statusorder=&limit=5&offset=0`,
    { headers: { "token": opts.token } }
  );
}

export function getProdukDetail(opts) {
  return http.get(
    `${baseURL}/service/farmasi/get-produkdetail?produkfk=8340&ruanganfk=328&kpid=2&norec_apd=8da89168-731d-49db-ae0e-b499e91b42dc`,
    { headers: { "token": opts.token } }
  );
}

export function getDropdownObat(opts) {
  return http.get(
    `${baseURL}/service/farmasi/dropdown-obat?namaproduk=para&ruanganfk=328&limit=10`,
    { headers: { "token": opts.token } }
  );
}

export function saveInputResep(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/input-resep-save`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getCetakResep(opts) {
  return http.get(
    `${baseURL}/service/report/farmasi/resep?pdf=true&norec=0de0de5f-fce4-42bd-b693-59576c2c2071&norec_order=4f444bc9-42d0-462`,
    { headers: { "token": opts.token } }
  );
}

export function getCetakLabel(opts) {
  return http.get(
    `${baseURL}/service/report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=fd04b25e-8ec3-496f-bddc-0a9a368a4a2f&norec=0de0d`,
    { headers: { "token": opts.token } }
  );
}

export function getTransaksiPelayanan(opts) {
  return http.get(
    `${baseURL}/service/farmasi/transaksi-pelayanan-farmasi?norec_pd=3c8841cc-ce11-4330-9446-ba781f69670f`,
    { headers: { "token": opts.token } }
  );
}

export function saveResepManual(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/input-resep-save`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getPasienFramasi(opts) {
  return http.get(
    `${baseURL}/service/farmasi/daftar-pasien-farmasi-grid?dari=2024-10-21&sampai=2024-10-21&offset=0&limit=5&rows=50`,
    { headers: { "token": opts.token } }
  );
}

export function saveRetur(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/save-retur-pelayanan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getStokRuanganApt(opts) {
  return http.get(
    `${baseURL}/service/logistik/stok-ruangan-grid?offset=0&limit=5&rows=50&namaproduk=PARACETAMOL%2010%20MG/ML%20INFUS&idruangan=329`,
    { headers: { "token": opts.token } }
  );
}

export function savePaketObat(data, opts) {
  return http.post(
    `${baseURL}/service/sysadmin/save-master-paket-obat`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getDaftarRetur(opts) {
  return http.get(
    `${baseURL}/service/farmasi/daftar-retur-obat-alkes?tglAwal=2024-10-21&tglAkhir=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function saveBatalVerifikasi(data, opts) {
  return http.post(
    `${baseURL}/service/dashboard/apotik/batal-verifikasi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getInputResepOrder(opts) {
  return http.get(
    `${baseURL}/service/farmasi/input-resep-order?norec=bfc87f6a-975c-4dc9-9cf7-d6769bc7b334&nocmfk=169824`,
    { headers: { "token": opts.token } }
  );
}

export function getOrderBedah(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-order-bedah?&dari=2024-10-21&sampai=2024-10-21&opsi=tglorder`,
    { headers: { "token": opts.token } }
  );
}

export function getJadwalOperasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-order-bedah?&dari=2024-10-21&sampai=2024-10-21&opsi=tgloperasi`,
    { headers: { "token": opts.token } }
  );
}

export function getLaporanTindakan(opts) {
  return http.get(
    `${baseURL}/service/dashboard/laporan-tindakan-operasi?&dari=2024-09-01&sampai=2024-09-30`,
    { headers: { "token": opts.token } }
  );
}

export function getTanggalOperasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/jadwal-operasi?ruanganid=363&tgl=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}

export function saveOrderBedahRev(data, opts) {
  return http.post(
    `${baseURL}/service/dashboard/save-order-pelayanan-bedah`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}























































/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getComboAddress(opts) {
  return http.get(
    `${baseURL}/service/registrasi/kotakabupaten?provfk=1`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getComboRegistrasi(opts) {
  return http.get(
    `${baseURL}/service/registrasi/list-dropdown`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDesaKelurahanPaging(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/registrasi/get-desa-kelurahan-paging?take=10?select=*`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {Object} data
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function savePasienFix(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/save-pasien`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getComboRegistrasiPoli(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/registrasi/get-data-combo-new`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getPagingDokter(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/registrasi/get-daftar-combo-pegawai-all?select=*`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {Object} query
 * @param {string} query.noCm
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getPasienByNoCM(query, opts) {
  const url = new URL(
    `${baseURL}/service/registrasi/pasien-registrasi`
  );
  url.searchParams.append("id", query.id);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.kdKelompokPasien
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getPenjaminPasienByKelompokPasien(query, opts) {
  const url = new URL(
    `${baseURL}/service/registrasi/penjamin-by-kelompokpasien`
  );
  url.searchParams.append("id", query.kdKelompokPasien);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} data
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function insertRegistrasiPoli(data, opts) {
  return http.post(
    `${baseURL}/service/registrasi/save-registrasi`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} query
 * @param {Date} [query.tglAwal]
 * @param {Date} [query.tglAkhir]
 * @param {number} [query.jmlRows]
 * @param {string} [query.jenisPel]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDaftarPasienRegistrasi(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/registrasi/list-pasien-grid`
  );
  url.searchParams.append("dari", formatDate(query.tglAwal || dayago));
  url.searchParams.append("sampai", formatDate(query.tglAkhir || now));
  url.searchParams.append("offset", 0);
  url.searchParams.append("limit", 6);
  url.searchParams.append("rows", 50);
  url.searchParams.append("pasien_aktif", true);
  url.searchParams.append("_total", true);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {Date} [query.tglAwal]
 * @param {Date} [query.tglAkhir]
 * @param {number} [query.jmlRow]
 * @param {string} [query.norm]
 * @param {string} [query.noreg]
 * @param {string} [query.nama]
 * @param {string} [query.ruanganArr]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDaftarAntrianRawatJalan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/rawatjalan/get-daftar-antrian-rajal`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRow", query.jmlRow || 10);
  url.searchParams.append("norm", query.norm || "");
  url.searchParams.append("noreg", query.noreg || "");
  url.searchParams.append("nama", query.nama || "");
  url.searchParams.append("ruanganArr", query.ruanganArr || "");

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noRegistrasi
 * @param {string} [query.jenisEmr]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getEMRTransaksi(query, opts) {
  const url = new URL(`${baseURL}/service/medifirst2000/emr/get-emr-transaksi`);
  url.searchParams.append("noregistrasi", query.noRegistrasi);
  url.searchParams.append("jenisEmr", query.jenisEmr || "navigasi");

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} [query.namaEmr]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getMenuRekamMedisDynamic(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-menu-rekam-medis-dynamic`
  );
  url.searchParams.append("namaemr", query.namaEmr || "navigasi");

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.emrid
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getRekamMedisDynamic(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-rekam-medis-dynamic`
  );
  url.searchParams.append("emrid", query.emrid);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.idRuangan
 * @param {string} query.idKelas
 * @param {string} query.idJenisPelayanan
 * @param {string} [query.idPenjamin]
 * @param {Object} query.filter
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getTindakan(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/tatarekening/tindakan/get-tindakan`
  );
  url.searchParams.append("idRuangan", query.idRuangan);
  url.searchParams.append("idKelas", query.idKelas);
  url.searchParams.append("idJenisPelayanan", query.idJenisPelayanan);
  url.searchParams.append("idPenjamin", query.idPenjamin || "null");
  url.searchParams.append("filter", objToQueryString(query.filter));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.idRuangan
 * @param {string} query.idKelas
 * @param {string} query.idJenisPelayanan
 * @param {string} [query.idPenjamin]
 * @param {string} query.idProduk
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getKomponenHarga(query, opts) {
  const url = new URL(
    `${baseURL}/service/tindakan/list-tindakan-komponen`
  );
  url.searchParams.append("idRuangan", query.idRuangan);
  url.searchParams.append("idKelas", query.idKelas);
  url.searchParams.append("idProduk", query.idProduk);
  url.searchParams.append("idJenisPelayanan", query.idJenisPelayanan);
  url.searchParams.append("idPenjamin", query.idPenjamin || "null");

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.idJenisPetugas
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getPegawayByJenisPetugas(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/tatarekening/tindakan/get-pegawaibyjenispetugas?idJenisPetugas=4`
  );
  url.searchParams.append("idJenisPetugas", query.idJenisPetugas);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function saveEMRDinamis(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/emr/save-emr-dinamis`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getComboTindakan(opts) {
  return http.get(
    `${baseURL}/service/tindakan/list-tindakan?idruangan=37`,
    { headers: { "token": opts.token } }
  );
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function saveTindakan(data, opts) {
  return http.post(
    `${baseURL}/service/tindakan/save-tindakan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} query
 * @param {string} query.departemenfk
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getComboPenunjang(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-combo-penunjang`
  );
  url.searchParams.append("departemenfk", query.departemenfk);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noregistrasi
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getRiwayatOrderPenunjang(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-riwayat-order-penunjang`
  );
  url.searchParams.append("noregistrasi", query.noregistrasi);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.idRuangan
 * @param {string} query.idKelas
 * @param {string} query.idJenisPelayanan
 * @param {boolean} query.isLabRad
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getTindakanWithDetails(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/general/get-tindakan-with-details`
  );
  url.searchParams.append("idRuangan", query.idRuangan);
  url.searchParams.append("idKelas", query.idKelas);
  url.searchParams.append("idJenisPelayanan", query.idJenisPelayanan);
  url.searchParams.append("isLabRad", query.isLabRad);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {boolean} query.isNotVerif
 * @param {string} query.jmlRow
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDaftarOrder(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/radiologi/get-daftar-order`
  );
  url.searchParams.append("isNotVerif", query.isNotVerif);
  url.searchParams.append("jmlRow", query.jmlRow || 10);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function saveOrderPelayanan(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/emr/save-order-pelayanan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function saveBridgingVansLab(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/bridging/penunjang/save-bridging-vans-lab`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function savePelayananPasien(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/radiologi/save-pelayanan-pasien`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} query
 * @param {string} query.noregistrasifk
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getRincianPelayanan(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/radiologi/get-rincian-pelayanan`
  );
  url.searchParams.append("noregistrasifk", query.noregistrasifk);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noRegister
 * @param {string} [query.jenisdata]
 * @param {string} [query.idruangan]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDetailTagihan(query, opts) {
  const url = new URL(
    `${baseURL}/service/kasir/billing`
  );
  url.searchParams.append("norec_pd", query.norec_pd);
  // url.searchParams.append("idruangan", query.idruangan);

  return http.get(url.toString(), {
    tags: `${baseURL}/service/kasir/billing/\${}`,
    headers: { "token": opts.token },
  });
}

/**
 *
 * @param {Object} query
 * @param {string} [query.namaPasien]
 * @param {string} [query.ruanganId]
 * @param {string} [query.status]
 * @param {Date} [query.tglAwal]
 * @param {Date} [query.tglAkhir]
 * @param {string} [query.noReg]
 * @param {string} [query.instalasiId]
 * @param {string} [query.noRm]
 * @param {number} [query.jmlRows]
 * @param {string} [query.kelompokPasienId]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDaftarPasienPulang(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/dashboard/daftar-pasien-pulang`
  );
  url.searchParams.append("namaPasien", query.namaPasien);
  url.searchParams.append("ruanganId", query.ruanganId);
  url.searchParams.append("status", query.status);
  url.searchParams.append(
    "dari",
    formatDateOnlyUrlQuery(query.tglAwal || dayago)
  );
  url.searchParams.append(
    "sampai",
    formatDateOnlyUrlQuery(query.tglAkhir || now)
  );
  url.searchParams.append("noReg", query.noReg);
  url.searchParams.append("instalasiId", query.instalasiId);
  url.searchParams.append("noRm", query.noRm);
  url.searchParams.append("rows", query.jmlRows || 10);
  url.searchParams.append("kelompokPasienId", query.kelompokPasienId);

  return http.get(url.toString(), {
    tags: `${baseURL}/service/medifirst2000/tatarekening/detail-tagihan/\${}`,
    headers: { "token": opts.token },
  });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noRegister
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getVerifikasiTagihan2(query, opts) {
  const url = new URL(
    `${baseURL}/service/kasir/verifikasi-tagihan`
  );
  url.searchParams.append("norec_pd", query.noRegister);
  url.searchParams.append("strukfk", null);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noRegister
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDetailTagihanVerifikasi(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/tatarekening/detail-tagihan-verifikasi`
  );
  url.searchParams.append("noRegister", query.noRegister);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function simpanVerifikasiTagihanTataRekening(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/verifikasi-tagihan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} query
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDataComboKasir(opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/kasir/get-data-combo-kasir`
  );

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noRecStrukPelayanan
 * @param {string} [query.tipePembayaran]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDataPembayaran(query, opts) {
  const url = new URL(
    `${baseURL}/service/kasir/pembayaran-tagihan`
  );
  url.searchParams.append("norec", query.noRecStrukPelayanan);

  url.searchParams.append("norec_pd", query.norec_pd);


  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function simpanDataPembayaran(data, opts) {
  return http.post(
    `${baseURL}/service/kasir/pembayaran-tagihan/simpan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

/**
 *
 * @param {Object} query
 * @param {Date} [query.dateStartTglSbm]
 * @param {Date} [query.dateEndTglSbm]
 * @param {string} [query.idPegawai]
 * @param {string} [query.ins]
 * @param {string} [query.idCaraBayar]
 * @param {string} [query.idKelTransaksi]
 * @param {string} [query.nosbm]
 * @param {string} [query.nocm]
 * @param {string} [query.nama]
 * @param {string} [query.desk]
 * @param {string} [query.KasirArr]
 * @param {string} [query.jmlRow]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDataDaftarSBM(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(`${baseURL}/service/kasir/daftar-penerimaan?`);
  url.searchParams.append(
    "dari",
    formatDateOnlyUrlQuery(query.dateStartTglSbm || dayago)
  );
  url.searchParams.append(
    "sampai",
    formatDateOnlyUrlQuery(query.dateEndTglSbm || now)
  );
  url.searchParams.append("idPegawai", query.idPegawai);
  url.searchParams.append("ins", query.ins);
  url.searchParams.append("idCaraBayar", query.idCaraBayar);
  url.searchParams.append("idKelTransaksi", query.idKelTransaksi);
  url.searchParams.append("nosbm", query.nosbm);
  url.searchParams.append("nocm", query.nocm);
  url.searchParams.append("nama", query.nama);
  url.searchParams.append("desk", query.desk);
  url.searchParams.append("KasirArr", query.KasirArr);
  url.searchParams.append("rows", query.jmlRow || 10);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {Date} [query.tglAwal]
 * @param {Date} [query.tglAkhir]
 * @param {Number} [query.jmlRow]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDaftarTagihanPasien(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/dashboard/kasir/list-tagihan-pasien`
  );

  url.searchParams.append(
    "dari",
    formatDateOnlyUrlQuery(query.tglAwal || dayago)
  );
  url.searchParams.append(
    "sampai",
    formatDateOnlyUrlQuery(query.tglAkhir || now)
  );
  url.searchParams.append("jmlRow", query.jmlRow || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {String} [query.Rows]
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getPasienLama(query, opts) {
  const url = new URL(`${baseURL}/service/registrasi/pasien-lama?offset=0&limit=5&rows=50&isbayi=undefined&`);

  // url.searchParams.append(query.Rows, 10);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
/**
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDropdownResepEmr(opts) {
  const url = new URL(
    `${baseURL}/service/farmasi/input-resep-cbo`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
/**
 *
 * @param {Object} query
 * @param {string} query.noReg
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getRiwayatResep(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-transaksi-pelayanan`
  );
  url.searchParams.append("noReg", query.noReg);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noReg
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function getDetailOrderResep(query, opts) {
  const url = new URL(
    `${baseURL}/service/farmasi/riwayat-order-resep?nocmfk=e141b9a0-9133-4adb-a10d-300495f784&norec_pd=36e91ab1-1ef8-49de-8fac-79ef68643e`
  );
  url.searchParams.append("noreg", query.noReg);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {Object} query
 * @param {string} query.noReg
 * @param {Object} opts
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function hargaObat(query, opts) {
  const url = new URL(`${baseURL}/service/farmasi/get-produkdetail`);
  url.searchParams.append("produkfk", query.produkfk || 4587);
  url.searchParams.append("ruanganfk", query.ruanganfk || 553);
  url.searchParams.append("kpid", query.kpid || 1);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

/**
 *
 * @param {*} data
 * @param {string} opts.token
 * @returns {import('k6/http').RefinedResponse}
 */
export function saveOrderResep(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/simpan-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRiwayatBundleHais(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/emr/get-data-trans-bundlehais`
  );
  url.searchParams.append("norec_pd", query.norec_pd);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDropdownBundle(opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/general/get-data-master-bundlehai-iad`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function saveBundleHais(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/emr/save-emr-bundlehai`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function getDropdownEResep(opts) {
  const url = new URL(`${baseURL}/service/medifirst2000/farmasi/get-datacombo`);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDetailOrderEResep(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/farmasi/get-detail-order`
  );
  url.searchParams.append("noorder", query.noorder);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function listEResep(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/farmasi/get-daftar-order`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago).substring(0,10));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now).substring(0,10));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function verifikasiResep(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/farmasi/save-pelayananobat`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function getStokRuangan(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-stok-ruangan-detail`
  );
  url.searchParams.append("kelompokprodukid", query.kelompokprodukid || "");
  url.searchParams.append("jeniskprodukid", query.jeniskprodukid || "");
  url.searchParams.append("namaproduk", query.namaproduk || "");
  url.searchParams.append("ruanganfk", query.ruanganfk || 553);
  url.searchParams.append("asalprodukfk", query.asalprodukfk || "");
  url.searchParams.append("jmlRows", query.tglAkhir || 10);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getStokOpname(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-stok-ruangan-so`
  );
  url.searchParams.append("kelompokprodukid", query.kelompokprodukid || "");
  url.searchParams.append("jeniskprodukid", query.jeniskprodukid || "");
  url.searchParams.append("namaproduk", query.namaproduk || "");
  url.searchParams.append("ruanganfk", query.ruanganfk || 553);
  url.searchParams.append("detailjenisprodukfk", query.asalprodukfk || "");

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function saveStokOpname(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/logistik/save-data-stock-opname`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function getDropdownKirim(opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-combo-logistik`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function infoStok(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-produkdetail`
  );
  url.searchParams.append("produkfk", query.kelompokprodukid || 4516);
  url.searchParams.append("ruanganfk", query.ruanganfk || 553);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function saveDistribusi(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/logistik/save-kirim-barang-ruangan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function daftarDistribusi(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-daftar-distribusi-barang`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function ambilAntrian(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/kiosk/save-antrian`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function getDropdownUser(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/menu/svc-modul?get=pegawai`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getListUser(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/menu/get-daftar-user`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getMenu(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/menu/data?jenis=objekMenuRecursive&id=242`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getModulApp(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/menu/data?jenis=modulaplikasi&id=241`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getSubSistem(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/menu/data?jenis=subsistem`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getSettingFix(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/settingdatafixed/get-kelompok-setting`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getHargaNetto(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/master/get-tarif-harganettoprodukbykelas?`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDropdownHarga(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sysadmin/master/get-combo-tarif`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getViewBed(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/humas/get-data-view-bed`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function monitoringHistory(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/bridging/bpjs/get-monitoring-kunjungan?tglsep=2023-06-06&jenispelayanan=2`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function cekPeserta(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/bpjs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function cekSatuSehatNumber(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/bridging/ihs/tools`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function savePegawai(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/sdm/save-rekam-data-pegawai`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function getListPegawai(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sdm/get-data-pegawai-all-sdm`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDropdownPegawai(opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sdm/get-data-combo-sdm`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDropdownAmb(opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/ambulance/get-data-for-combo`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getOrderAmbulance(query,opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/ambulance/get-data-order-ambulan?isNotVerif=true&jmlRow=100`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDaftarRegAmbulance(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/ambulance/get-data-registrasi-pasien-ambulan`
  );

  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 100);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getRincianAmb(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/ambulance/get-data-rincian-ambulan`
  );
  url.searchParams.append(
    "noregistrasifk",
    query.noregistrasifk || "c0bbc970-0438-11ee-88e3-9550bfa2"
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getBukuKas(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/bendaharapenerimaan/get-daftar-penerimaan-bank`
  );
  url.searchParams.append(
    "tglAwal",
    formatDate(query.dateStartTglSbm || dayago).substring(0,10)
  );
  url.searchParams.append("tglAkhir", formatDate(query.dateEndTglSbm || now).substring(0,10));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getPenerimaanKasirDropdown(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/bendaharapenerimaan/get-data-combo`
  );

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getPenerimaanKasir(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/bendaharapenerimaan/get-daftar-sbm`
  );
  url.searchParams.append(
    "dateStartTglSbm",
    formatDate(query.dateStartTglSbm || dayago)
  );
  url.searchParams.append(
    "dateEndTglSbm",
    formatDate(query.dateEndTglSbm || now)
  );

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getInfoKlaim(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/bridging/bpjs/get-monitoring-klaim`
  );
  url.searchParams.append("tglsep", formatDate(query.tglsep || now).substring(0,10));
  url.searchParams.append("jenispelayanan", query.jenispelayanan || 2);
  url.searchParams.append("status", query.status || 3);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getBendaharaKeluar(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/bendaharapengeluaran/get-data-tagihan-suplier`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getDaftarOrderGizi(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(`${baseURL}/service/medifirst2000/gizi/get-daftar-order`);
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getDaftarKirimGizi(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(`${baseURL}/service/medifirst2000/gizi/get-daftar-kirim`);
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getJasaPelayanan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/remunerasi/get-jasa-layanan-pagu-rev2`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows|| 50);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getPaguLayanan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/remunerasi/get-daftar-jasa-layanan-pagu-rev2`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 50);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function daftarRemunPegawai(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/remunerasi/get-daftar-remunerasi-pegawai`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function jurnal(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/akuntansi/get-data-jurnal-umum-2018`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function neraca(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/akuntansi/get-data-trial-balance`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function bukuBesar(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/akuntansi/get-data-buku-besar-rev2`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  url.searchParams.append("noaccount", query.noaccount || '-');
  url.searchParams.append("noaccount2", query.noaccount2 || '-');
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getAgendaRapat(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/eoffice/get-agendarapat?&thn=2023`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getNotulen(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/eoffice/get-notulen?q=q`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getSuratMasuk(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/eoffice/get-daftar-surat`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function getDaftarBarangAset(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/logistik/get-daftar-asset`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getPermintaanPerbaikan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/ipsrs/get-daftar-permohonan`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  url.searchParams.append("manage", query.manage || "aingmacan");
  url.searchParams.append("idRuangan", query.idRuangan || 58);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function savePermintaanPerbaikan(data, opts) {
  return http.post(
    `${baseURL}/service/medifirst2000/ipsrs/save-permohonan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function listAnggaran(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/perencanaan/get-daftar-mataanggaran?tahun=2023`
  );

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function rekapKehadiranPelatihan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/sdm/pelatihan/get-daftar-rekapitulasi-kehadiran-peserta-pelatihan`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function daftarPermintaanPelatihan(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/sdm/pelatihan/get-data-pengajuan-pelatihan`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function barangAlatMedis(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/sterilisasi/get-data-stok-steril`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function listPermohonanSanitasi(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/medifirst2000/sanitasi/get-daftar-permohonan-sanitasi`
  );
  url.searchParams.append("tglAwal", formatDate(query.tglAwal || dayago));
  url.searchParams.append("tglAkhir", formatDate(query.tglAkhir || now));
  url.searchParams.append("jmlRows", query.jmlRows || 10);
  url.searchParams.append("manage", query.manage || "aingmacan");
  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function gajiPegawai(query, opts) {
  const url = new URL(
    `${baseURL}/service/medifirst2000/sdm/get-gaji-pegawai?&tahun=2023&bulan=5&norec=&blntahun=2023-06`
  );
  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getComboOperatorSelect  (opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/registrasi/daftar-registrasi/get-data-combo-operator-select?departemen=1&kelompokpasien=1&jaminankhusus=1&ruanganall=1&dokter=1&pegawaiLogin=1&pembatalan=1&jenisdiagnosa=1`,
    { headers: { "token": opts.token } }
  );
}
export function getComboEMR(opts) {
  return http.get(
    `${baseURL}/service/medifirst2000/emr/get-combo`,
    { headers: { "token": opts.token } }
  );
}
export function getHistoryCPPT(query, opts) {
  const url = new URL(`${baseURL}/service/medifirst2000/emr/get-history-cppt`);
  url.searchParams.append("nocm", query.nocm);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}


export function getDiagnosaPasienByNoreg(query, opts) {
  const url = new URL(`${baseURL}/service/medifirst2000/emr/get-diagnosapasienbynoreg`);
  url.searchParams.append("noReg", query.noReg);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getDashboardRJ(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/registrasi/list-pasien-grid`
  );
  url.searchParams.append("dari", formatDateOnlyUrlQuery(query.tglAwal || dayago));
  url.searchParams.append("sampai", formatDateOnlyUrlQuery(query.tglAkhir || now));
  url.searchParams.append("limit", query.jmlRows || 10);

  return http.get(url.toString(), { headers: { "token": opts.token } });
}

export function getDetailEMR(query, opts) {
  const now = new Date();
  const dayago = new Date();
  dayago.setDate(dayago.getDate() - 1);

  const url = new URL(
    `${baseURL}/service/emr/detail-pelayanan`
  );
  url.searchParams.append("norec_pd", query.norec_pd);


  return http.get(url.toString(), { headers: { "token": opts.token } });
}


export function simpanEMR(data, opts) {
  return http.post(
    `${baseURL}/service/emr/simpan-emr`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function simpanOrderLab(data, opts) {
  return http.post(
    `${baseURL}/service/laboratorium/simpan-order`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}

export function getRIwayatOrderLab(query, opts) {
 
  const url = new URL(
    `${baseURL}/service/laboratorium/riwayat-order?nocmfk=e141b9a0-9133-4adb-a10d-300491c5f784&norec_pd=36e91ab1-1ef8-49de-8fac-79ef68643eaa`
  );

  return http.get(url.toString(), { headers: { "token": opts.token } });
}
export function simpanResep(data, opts) {
  return http.post(
    `${baseURL}/service/farmasi/input-resep-save`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
//update new
export function DashboardRegistrasi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/registrasi?ruanganfk=&tgl=2024-04-24`,
    { headers: { "token": opts.token } }
  );
}

export function getComboKecamatan(opts) {
  return http.get(
    `${baseURL}/service/registrasi/kecamatan?kotafk=5`,
    { headers: { "token": opts.token } }
  );
}

export function getComboDesaKelurahan(opts) {
  return http.get(
    `${baseURL}/service/registrasi/desakelurahan?kecfk=86`,
    { headers: { "token": opts.token } }
  );
}

export function RiwayatEMR(opts) {
  return http.get(
    `${baseURL}/service/emr/riwayat-emr?jenis_emr=asesmenawal&nocmfk=f7a5504c-32b5-4074-9a67-944ab4472d21&norec_pd=undefined`,
    { headers: { "token": opts.token } }
  );
}


// fito
export function getDashboardSOLab(opts) {
  return http.get(
    `${baseURL}/service/dashboard/so-lab?ruanganid=&tglAwal=2024-10-21&tglAkhir=2024-10-21&limit=5&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getLabDetail(opts) {
  return http.get(
    `${baseURL}/service/dashboard/lab-detail`,
    { headers: { "token": opts.token } }   
  );
}
export function getDataChartLabRuangan(opts) {
  return http.get(
    `${baseURL}/service/dashboard/chart-lab-ruangan?tglAwal=2024-10-21&tglAkhir=2024-10-21`,
    { headers: { "token": opts.token } }
  );
}
export function getPenunjangLab(opts) {
  return http.get(
    `${baseURL}/service/dashboard/penunjang-lab?ruanganid=&tglAwal=2024-10-21&tglAkhir=2024-10-21&limit=6&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getRegistrasiPasien(opts) {
  return http.get(
    `${baseURL}/service/laboratorium/get-regis-pasien?ruanganid=&dari=2024-10-21&sampai=2024-10-21&namapasien=&nocm=&search=&noregistrasi=&limit=10&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getDetailLab(opts) {
  return http.get(
    `${baseURL}/service/dashboard/so-lab?statusorder=&noorder=L2410000008`,
    { headers: { "token": opts.token } }
  );
}
export function getListVerif(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-lab-verify?norec_so=a04f85a3-3027-4276-8b87-2d5a3353a2c4`,
    { headers: { "token": opts.token } }
  );
}
export function getStatusCloseLab(opts) {
  return http.get(
    `${baseURL}/service/general/get-status-close?key=2410030237`,
    { headers: { "token": opts.token } }
  );
}
export function getOrder(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-order?strukorderfk=a04f85a3-3027-4276-8b87-2d5a3353a2c4`,
    { headers: { "token": opts.token } }
  );
}
export function getPelayananLab(opts) {
  return http.get(
    `${baseURL}/service/dashboard/get-pelayanan-lab?idkelas=6&idjenispelayanan=1&idruangan=335`,
    { headers: { "token": opts.token } }
  );
}
export function getPegawai(opts) {
  return http.get(
    `${baseURL}/service/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`,
    { headers: { "token": opts.token } }
  );
}
export function getListLab(opts) {
  return http.get(
    `${baseURL}/service/dashboard/list-lab`,
    { headers: { "token": opts.token } }
  );
}

//fito
//Radiologi

export function getDashRad(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi?tglAwal=2024-10-21&tglAkhir=2024-10-21&statusorder=0&limit=50&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getDokterRadiologi(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi?tglAwal=2024-10-21&tglAkhir=2024-10-21&statusorder=0&limit=50&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getPenunjang(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi/get-penunjang-rad?tglAwal=2024-10-21&tglAkhir=2024-10-21&limit=50&offset=0`,
    { headers: { "token": opts.token } }
  );
}
export function getDetail(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi/get-detail-rad`,
    { headers: { "token": opts.token } }
  );
}
export function getDetailPelayan(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi/get-pelayanan?idkelas=6&idjenispelayanan=1`,
    { headers: { "token": opts.token } }
  );
}
export function getDetailOrderRad(opts) {
  return http.get(
    `${baseURL}/service/dashboard/radiologi/get-order-layanan-rad?strukorderfk=16bb674e-fe9d-4bb2-94d0-0a6d30484b69&objectkelasfk=6`,
    { headers: { "token": opts.token } }
  );
}
export function saveRadBilling(data, opts) {
  return http.post(
    `${baseURL}/service/dashboard/radiologi/save-order-pelayanan`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}
export function saveVerifRad(data, opts) {
  return http.post(
    `${baseURL}/service/bridging/penunjang/save-bridging-zeta`,
    JSON.stringify(data),
    {
      headers: {
        "token": opts.token,
        "Content-Type": "application/json",
      },
    }
  );
}