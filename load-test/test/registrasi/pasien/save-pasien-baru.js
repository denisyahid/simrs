import { check } from "k6";
import { generateToken, savePasienBaru } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePasienBaru(
    {
      "pasien": {
        "id": "",
        "isPenunjang": false,
        "isJenazah": false,
        "isbayi": false,
        "nocmfkibu": null,
        "noidentitas": "",
        "nobpjs": "0001869906227",
        "namapasien": "LARASATIZKA AYUNINGTYAS",
        "tempatlahir": "MALANG",
        "tgllahir": "2001-08-08",
        "objectjeniskelaminfk": 2,
        "nohp": "08923456789",
        "objectagamafk": 1,
        "email": null,
        "namaibu": "aminah",
        "kode_pasien_baru": null,
        "objectstatusperkawinanfk": null,
        "objectgolongandarahfk": null,
        "objectpendidikanfk": null,
        "objectpekerjaanfk": null,
        "objectsukufk": null,
        "noaditional": null,
        "notelepon": null,
        "namaayah": null,
        "namakeluarga": null,
        "namasuamiistri": null,
        "penanggungjawab": null,
        "hubungankeluargapj": null,
        "telponpenanggungjawab": null,
        "bahasa": null,
        "jeniskelaminpenanggungjawab": null,
        "umurpenanggungjawab": null,
        "pekerjaanpenangggungjawab": null,
        "alamatrmh": null,
        "objectkebangsaanfk": 1,
        "objectnegarafk": 1,
        "progress": 42.857142857142854,
        "isReservasi": false,
        "antrianpasienregistrasifk": null,
        "norecEMR": null,
        "isIGD": false
      },
      "alamat": {
        "alamatlengkap": "jl malang",
        "rtrw": null,
        "objectpropinsifk": null,
        "objectkotakabupatenfk": null,
        "objectkecamatanfk": null,
        "objectdesakelurahanfk": null,
        "kodepos": null
      }
    },
    { token: data.token }
  );
  check(res, {
    "registrasi/save-pasien-baru is status 200": (r) => r.status === 200,
  });
}
