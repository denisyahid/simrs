import { check } from "k6";
import { generateToken, saveOrderObat } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveOrderObat(
    {
      "data": [
        {
          "strukorder": {
            "norec": "",
            "tglresep": "2024-10-31 10:25:05",
            "penulisresepfk": 313,
            "ruanganfk": 325,
            "noregistrasifk": "5a982a7f-26c7-4567-81b7-ed78d7c72ee6",
            "qtyproduk": 1,
            "cito": false,
            "isrutin": false,
            "isbpl": false,
            "alergiobat": "-",
            "noruangan": null,
            "isreseppulang": null
          },
          "orderpelayanan": [
            {
              "no": 1,
              "noregistrasifk": "5a982a7f-26c7-4567-81b7-ed78d7c72ee6",
              "generik": null,
              "hargajual": "1153.28",
              "jenisobatfk": null,
              "jenisobat": null,
              "kelasfk": 6,
              "stock": "9940",
              "harganetto": "1153.28",
              "nostrukterimafk": "INJK-OA-2024-03-b774-79677ecc       ",
              "norec_spd": "41000b30-0661-11ed-b774-7051830     ",
              "ruanganfk": 325,
              "rke": 1,
              "jeniskemasanfk": 2,
              "jeniskemasan": "Non Racikan",
              "aturanpakaifk": 0,
              "aturanpakai": "1x1",
              "ispagi": 0,
              "issiang": 0,
              "issore": 0,
              "ismalam": 0,
              "routefk": null,
              "route": null,
              "asalprodukfk": 1,
              "asalproduk": "BLUD",
              "produkfk": 8254,
              "namaproduk": "ACARBOSE 100 MG TABLET",
              "productname": "ACARBOSE 100 MG TABLET",
              "nilaikonversi": null,
              "satuanstandarfk": 145,
              "satuanstandar": "Tablet",
              "satuanviewfk": 145,
              "satuanview": "Tablet",
              "jmlstok": "9940",
              "jumlah": "7",
              "jumlahobat": 0,
              "dosis": 1,
              "hargasatuan": "1153.28",
              "hargadiscount": "0",
              "persendiscount": 0,
              "total": 8072.96,
              "jmldosis": "0/1/undefined",
              "jasa": 0,
              "keterangan": null,
              "satuanresepfk": null,
              "satuanresep": null,
              "tglkadaluarsa": null,
              "obtkronis": ""
            }
          ],
          "noregistrasi": "2410300003"
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "farmasi/simpan-order is status 200": (r) => r.status === 200,
  });
}
