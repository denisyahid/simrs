import { check } from "k6";
import { generateToken, saveRetur } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveRetur(
    {
        "strukresep": {
          "alasan": "cobaa",
          "namapasien": "RATHNA ANGGREANY NANGI",
          "nocm": "009877",
          "noorder": "EditResep",
          "norecResep": "75884659-45a2-4345-80ff-8380a2058bec",
          "noresep": "O/2410/00690",
          "penulisresepfk": 319,
          "retur": "RERTUR",
          "ruanganfk": 325,
          "jumlahitem": 1,
          "status": 0,
          "totalretur": 5,
          "tglresep": "2024-10-26 13:24:12"
        },
        "pelayananpasien": [
          {
            "no": 1,
            "asalproduk": "BLUD",
            "asalprodukfk": 1,
            "aturanpakai": "2 x sehari",
            "dosis": "1",
            "generik": null,
            "hargadiscount": 0,
            "hargajual": "64000",
            "hargasatuan": "64000",
            "harganetto": "64000",
            "jasa": "0",
            "jeniskemasan": "Non Racikan",
            "jeniskemasanfk": 2,
            "jenisobatfk": null,
            "jenisracikanfk": null,
            "jmldosis": "10000/1/500",
            "jmlstok": "9980",
            "jumlah": 20,
            "jumlahobat": "20",
            "kekuatan": "500",
            "kelasfk": 6,
            "keterangan": null,
            "keteranganpakai": null,
            "namaproduk": "FARMADOL (PARACETAMOL) 500 MG / 50 ML INFUS",
            "namaruangan": "IGD ",
            "objectruanganfk": 322,
            "nilaikonversi": "1",
            "norec_spd": "41000b30-0661-11ed-b774-7050650     ",
            "norecpp": "44c39c88-c229-431a-91a6-7a9932021f22",
            "noresep": "O/2410/00690",
            "nostrukterimafk": "INJK-OA-2024-03-b774-79677ecc       ",
            "noregistrasifk": "a4fb8be4-bc32-4093-b50c-4ea606cda25b",
            "persendiscount": "0",
            "produkfk": 8018,
            "rke": "1",
            "route": null,
            "routefk": null,
            "ruidresep": 325,
            "ruanganresep": "SATELIT FARMASI SENTRAL",
            "satuanresep": null,
            "satuanresepfk": null,
            "satuanstandar": "Infus",
            "satuanstandarfk": 113,
            "satuanviewfk": 113,
            "satuanview": "Infus",
            "stock": "9980",
            "tglkadaluarsa": null,
            "tglresep": "2024-10-26 13:24:12",
            "tglregistrasi": "2024-10-24 00:56:13",
            "jmlretur": 5,
            "total": 960000,
            "totalAwal": 1280000
          }
        ]
    },
    { token: data.token }
  );
  check(res, {
    "farmasi/save-retur-pelayanan is status 200": (r) => r.status === 200,
  });
}
