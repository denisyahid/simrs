import { check } from "k6";
import { generateToken, saveInputResep } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveInputResep(
    {
        "strukresep": {
          "tglresep": "2024-10-25 15:43:10",
          "tglregistrasi": "2024-10-03 08:12:20",
          "noregistrasi": "2410030199",
          "pasienfk": "e8ba9e22-aef2-4019-a25b-eb1fa5ded0a2",
          "nocm": "169825",
          "namapasien": "ALMIRA SANARI RUBINA",
          "penulisresepfk": 352,
          "ruanganfk": 325,
          "noorder": "bfc87f6a-975c-4dc9-9cf7-d6769bc7b334",
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
            "noregistrasifk": "e8ba9e22-aef2-4019-a25b-eb1fa5ded0a2",
            "tglregistrasi": "2024-10-03 08:12:20",
            "generik": null,
            "hargajual": 14140,
            "jenisobatfk": null,
            "kelasfk": 6,
            "lastorder": "",
            "stock": "9992",
            "harganetto": 0,
            "nostrukterimafk": "INJK-OA-2024-03-b774-79677ecc       ",
            "ruanganfk": 325,
            "rke": "1",
            "jeniskemasanfk": 2,
            "jeniskemasan": "Non Racikan",
            "aturanpakaifk": 0,
            "aturanpakai": "2",
            "routefk": null,
            "route": null,
            "asalprodukfk": 1,
            "asalproduk": "BLUD",
            "produkfk": 8825,
            "namaproduk": "PARACETAMOL 10 MG/ML INFUS",
            "nilaikonversi": "1",
            "satuanstandarfk": 113,
            "satuanstandar": "Infus",
            "satuanviewfk": 113,
            "satuanview": "Infus",
            "jmlstok": "9992",
            "jumlah": 5,
            "jumlahobat": "5",
            "dosis": "1",
            "kekuatan": "10",
            "hargasatuan": 14140,
            "hargadiscount": "0",
            "total": 70700,
            "sediaan": null,
            "jmldosis": "5/1/10",
            "jasa": 0,
            "ispagi": false,
            "issiang": false,
            "ismalam": false,
            "issore": false,
            "keterangan": null,
            "satuanresepfk": 56,
            "satuanresep": "Setelah Makan",
            "tglkadaluarsa": null,
            "isreseppulang": null,
            "norec_spd": "41000b30-0661-11ed-b774-7054685     ",
            "obatkronis": false,
            "norec_pp": "bfc87f6a-975c-4dc9-9cf7-d6769bc7b334",
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
