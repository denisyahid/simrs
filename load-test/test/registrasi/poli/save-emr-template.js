import { check } from "k6";
import { generateToken, saveEMRTemplate } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveEMRTemplate(
    {
        "id": "",
        "norec_emr": "5f1a0cd4-a6b1-4005-a34f-b250acb04692",
        "collection": "AsesmenAwalKeperawatanPasienRawatJalan",
        "url_form": "module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan",
        "name_form": "Assesmen Keperawatan",
        "jenis_emr": "asesmen_medis",
        "data": {
          "kebjamKedatangan": "2024-10-21T08:59:19.425Z",
          "kebjamAsesmenAwal": "2024-10-21T08:59:19.425Z",
          "kebtanggalKedatangan": "2024-10-21T08:59:19.425Z",
          "keadaanumumobgyn": 1,
          "gcse": 4,
          "gcsv": 5,
          "gcsm": 6,
          "kebrujukan": "TIDAK",
          "kebrujuklanjutan": "SENDIRI",
          "kebpilihanallo": 4,
          "keluhanutama": "fghuijoiu",
          "riwayatpenyakit": "0opiygghjk",
          "riwayatpenyakitdahulu": "lkjhgvfvbnm",
          "riwayatpengobatan": "zsxdcfgvhbgtfr",
          "riwayatpenyakitkeluarga": "ytredswzxdcfgvbhj",
          "isalergi": "TIDAK",
          "tekananDarahObgyn": "120/80",
          "nadiObgyn": "30",
          "nafasObgyn": "40",
          "celciusObgyn": "37",
          "sao2Obgyn": "70",
          "beratbadanObgyn": "45",
          "tinggibadanObgyn": "155",
          "pasien": {
            "nocm": "168513",
            "nocmfk": "168512",
            "namapasien": "ZAFRAN MIKAIL",
            "tgllahir": "2021-02-25",
            "tempatlahir": "DENPASAR",
            "suku": null,
            "objectjeniskelaminfk": 1,
            "jeniskelamin": "Laki-laki",
            "noidentitas": "5171012502210003",
            "nobpjs": null,
            "statusemr": null,
            "noasuransilain": null,
            "alamatlengkap": "JALAN TUKAD IRAWADI GG.MAHKOTA",
            "kodepos": null,
            "notelepon": null,
            "tglberakhir": "2024-10-22",
            "tglmulai": "2024-10-21",
            "objectkelompokuserfk": "",
            "pegawaipemohonfk": null,
            "nohp": "085965931141",
            "namaayah": null,
            "noregistrasi": "2410210002",
            "namaibu": "ELIK",
            "email": null,
            "objectruanganlastfk": 201,
            "agama": "ISLAM",
            "pendidikan": null,
            "pekerjaan": "Belum/Tidak Bekerja",
            "isfoto": null,
            "objectkebangsaanfk": 1,
            "filename": null,
            "kelompokpasien": "UMUM/PRIBADI",
            "catatan": null,
            "umur": "3thn 7bln 26hr",
            "isFilterProdukLab": "true",
            "enabledEMRSimrsLama": "false",
            "registrasi": {
              "noregistrasi": "2410210002",
              "norec": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
              "isclosing": null,
              "norec_pd": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
              "tglregistrasi": "2024-10-21 15:33:01",
              "tglpulang": "2024-10-21 15:33:01",
              "namadepartemen": "Instalasi Rawat Jalan",
              "kelompokpasien": "UMUM/PRIBADI",
              "asalrujukan": "Datang Sendiri",
              "namaruangan": "POLI INTERNA",
              "namakelas": "NON KELAS",
              "nocmfk": "168512",
              "namarekanan": "Diri Sendiri",
              "dokter": "dr. NI WAYAN INDAH ELYANI, Sp.PD",
              "objectruanganlastfk": 201,
              "objectruanganfk": 201,
              "norec_apd": "84bf2340-2b2a-4b8b-9576-5125346df2fa",
              "objectkelompokpasienlastfk": 1,
              "objectrekananfk": 0,
              "jenispelayanan": 1,
              "jenispelayananfk": 1,
              "objectkelasfk": 6,
              "objectdepartemenfk": 18,
              "objectpegawaifk": 396,
              "nosep": null,
              "inacbg_totalgrouper": null,
              "kdsubspesialisbpjs": "INT",
              "namasubspesialisbpjs": null,
              "dpjplayan_kode": null,
              "dpjplayan_nama": null,
              "tinggibadan": null,
              "beratbadan": null,
              "suhu": null,
              "nadi": null,
              "pernafasan": null,
              "tekanandarah": null,
              "spo2": null,
              "jenispelayananBaru": "REGULER",
              "isberkas": null,
              "apd": {
                "norec_apd": "84bf2340-2b2a-4b8b-9576-5125346df2fa",
                "objectkelasfk": 6,
                "objectruanganfk": 201,
                "namaruangan": "POLI INTERNA",
                "namakelas": "NON KELAS",
                "noregistrasifk": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
                "tglregistrasi": "2024-10-21 15:33:01"
              },
              "billing": 810000,
              "diagnosis": [],
              "laboratorium": [
                {
                  "norec": "a9747ba0-2aba-412d-9bc0-ed13d86b3783",
                  "noregistrasifk": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
                  "keteranganorder": "Order Laboratorium"
                }
              ],
              "radiologi": [
                {
                  "norec": "16bb674e-fe9d-4bb2-94d0-0a6d30484b69",
                  "noregistrasifk": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
                  "keteranganorder": "Order Radiologi"
                }
              ],
              "isloading": false
            }
          },
          "registrasi": {
            "norec_apd": "84bf2340-2b2a-4b8b-9576-5125346df2fa",
            "norec_pd": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
            "noregistrasi": "2410210002",
            "tglregistrasi": "2024-10-21 15:33:01",
            "kelompokpasien": "UMUM/PRIBADI",
            "asalrujukan": "Datang Sendiri",
            "namarekanan": "Diri Sendiri",
            "namakelas": "NON KELAS",
            "objectruanganfk": 201,
            "tglpulang": "2024-10-21 15:33:01",
            "namaruangan": "POLI INTERNA",
            "dokter": "dr. NI WAYAN INDAH ELYANI, Sp.PD",
            "objectpegawaifk": 396
          },
          "user_input": {
            "id": 46,
            "namauser": "user.rawat jalan",
            "pegawaifk": 20,
            "namalengkap": "SIMULASI"
          },
          "profile": {
            "kdprofile": 1,
            "namaprofile": "RSUD Bali Mandara"
          },
          "statusenabled": true,
          "noemr": "MR2410/00000012",
          "emrpasienfk": "5f1a0cd4-a6b1-4005-a34f-b250acb04692",
          "id": "e661af11-b370-4ae0-bf77-f906db8d3e0c",
          "created_at": "2024-10-21 17:30:00",
          "updated_at": null,
          "nilaiSkrining": null,
          "nilaimandi": null,
          "nilai": false,
          "CBKetergantunganTotal": "Ketergantungan total (0-4)",
          "risikokekurangan": "Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif",
          "jaringankeras": "Gangguan jaringan keras gigi",
          "posisikan": "Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)",
          "chest": "Lakukan chest fisioterapi sesuai indikasi/bila perlu",
          "namatemplate": "TEMPLATE INTERNA",
          "nocm": "168513"
        }
    },
    { token: data.token }
  );
  check(res, {
    "emr/simpan-emr-template is status 200": (r) => r.status === 200,
  });
}
