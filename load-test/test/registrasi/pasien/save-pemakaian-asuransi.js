import { check } from "k6";
import { generateToken, savePemakaianAsuransi } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePemakaianAsuransi(
    {"asuransipasien":{"id":174,"nocmfk":"169718","nocm":"169719","noregistrasi":"2410080010","kdpenjaminpasien":2551,"objectkelasdijaminfk":3,"namapeserta":"NI NENGAH UNIARI","noasuransi":"0003050784279","noidentitas":"5107054107870024","kelompokpasien":2,"tgllahir":"1987-07-27","jenispeserta":"PBI (APBD)","notelpmobile":"082339530055"},"pemakaianasuransi":{"norec":"3cf113d4-7c09-4409-a68b-9a1ef6214945","noregistrasifk":"9c013ba7-d596-45ed-97b7-3a1fea399739","norujukan":"22080701","nosep":"0233R7791024V002751","nokartu":"0003050784279","tglsep":"2024-10-08","ppkpelayanan":"0233R779","jnspelayanan":"2","klsrawathak_kode":3,"klsrawathak_nama":"Kelas 3","klsrawatnaik_kode":null,"klsrawatnaik_nama":null,"pembiayaan_kode":null,"pembiayaan_nama":null,"nomr":"169719","asalrujukan":1,"tglrujukan":"2024-10-08","ppkrujukan":"0233R779","ppkrujukan_nama":"RSUD BALI MANDARA","kdprovider":"22080701","nmprovider":"ABANG  I","catatan":null,"diagawal_kode":"D32","diagawal_nama":"D32 - Benign neoplasm of meninges","poli_kode":"IGD","poli_nama":"Instalasi Gawat Darurat","eksekutif":"0","cob":"0","katarak":"0","lakalantas_kode":0,"lakalantas_nama":"Bukan Kecelakaan","nolp":null,"tglkejadian":null,"keterangan":null,"suplesi":0,"nosepsuplesi":null,"kdpropinsi_kode":null,"kdpropinsi_nama":null,"kdkabupaten_kode":null,"kdkabupaten_nama":null,"kdkecamatan_kode":"22080701","kdkecamatan_nama":"22080701","tujuankun_kode":null,"tujuankun_nama":null,"flagprocedure_kode":null,"flagprocedure_nama":null,"kdpenunjang_kode":null,"kdpenunjang_nama":null,"assesmentpel_kode":null,"assesmentpel_nama":null,"nosurat":"0233R7791024K001490","kodedpjp":"534926","namadpjp":"KOMANG SENA ADISTIRA ARTHA","dpjplayan_kode":null,"dpjplayan_nama":null,"notelp":"082339530055","user":"User Administrator ","backdate":true,"LOG":"Ubah No. SEP 0233R7791024V002751 dibuat BRIDGING dengan tgl 2024-10-08 ","isrujukinternal":null}},
    { token: data.token }
  );
  check(res, {
    "registrasi/pemakaian-asuransi/save is status 200": (r) => r.status === 200,
  });
}
