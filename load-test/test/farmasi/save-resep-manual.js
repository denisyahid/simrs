import { check } from "k6";
import { generateToken, saveResepManual } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveResepManual(
    {
      "strukresep": {
        "tglresep": "2024-10-27 21:43:13",
        "tglregistrasi": "2024-10-27 18:26:13",
        "noregistrasi": "2410270003",
        "pasienfk": "8b547c63-336f-4489-a3da-e23f0cf9c7ec",
        "nocm": "17.01.30",
        "namapasien": "LARASATIZKA AYUNINGTYAS",
        "penulisresepfk": 422,
        "ruanganfk": 329,
        "noorder": "",
        "norecResep": "",
        "noresep": "-",
        "retur": "-",
        "isobatalkes": false,
        "isreseppulang": null,
        "isresepcito": null
      },
      "pelayananpasien": [
        {
          "no": 1,
          "noregistrasifk": "8b547c63-336f-4489-a3da-e23f0cf9c7ec",
          "generik": null,
          "hargajual": "781",
          "jenisobatfk": null,
          "jenisobat": null,
          "kelasfk": 6,
          "stock": "10000",
          "harganetto": "610",
          "nostrukterimafk": "INJK-OA-2024-03-b774-79677ecc       ",
          "norec_spd": "41000b30-0661-11ed-b774-7115690     ",
          "ruanganfk": 329,
          "rke": 1,
          "jeniskemasanfk": 2,
          "jeniskemasan": "Non Racikan",
          "aturanpakaifk": 0,
          "aturanpakai": "1x1",
          "ispagi": 1,
          "issiang": 0,
          "issore": 0,
          "ismalam": 0,
          "iskronis": false,
          "isdonasi": false,
          "lastorder": "",
          "routefk": null,
          "route": null,
          "asalprodukfk": 1,
          "asalproduk": "BLUD",
          "produkfk": 8254,
          "namaproduk": "ACARBOSE 100 MG TABLET",
          "productname": "ACARBOSE 100 MG TABLET",
          "nilaikonversi": 1,
          "satuanstandarfk": 145,
          "satuanstandar": "Tablet",
          "satuanviewfk": 145,
          "satuanview": "Tablet",
          "jmlstok": "10000",
          "jumlah": 7,
          "jumlahobat": 7,
          "dosis": 1,
          "hargasatuan": "781",
          "hargadiscount": "0",
          "persendiscount": 0,
          "total": 5467,
          "jmldosis": "7/1/100",
          "kekuatan": "100",
          "jasa": 0,
          "keterangan": null,
          "satuanresepfk": 56,
          "satuanresep": "Setelah Makan",
          "tglkadaluarsa": null,
          "obtkronis": "",
          "icon": "fa-inverse lnir lnir-medicine-alt",
          "color": "primary"
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "farmasi/input-resep-save is status 200": (r) => r.status === 200,
  });
}
