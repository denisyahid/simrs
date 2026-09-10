import { check } from "k6";
import { generateToken, saveOrderBedahRev } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveOrderBedahRev(
    {
      "data": [
        {
          "idProduk": 2336,
          "hargaLayanan": "4590000",
          "tglpelayanan": "2024-10-27 21:53:17",
          "jumlah": 1,
          "komponenharga": [
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "1740000",
              "objectprodukfk": 2336,
              "iscito": false,
              "hargadijamin": null,
              "diskon": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "2850000",
              "objectprodukfk": 2336,
              "iscito": false,
              "hargadijamin": null,
              "diskon": "0"
            }
          ],
          "pelayananpetugas": [
            {
              "objectjenispetugaspefk": 4,
              "jenispetugaspe": "Dokter Pemeriksa",
              "listpegawai": []
            }
          ]
        }
      ],
      "parameter": {
        "idruangtujuan": 362,
        "pd_norec": "9c013ba7-d596-45ed-97b7-3a1fea399739",
        "tglregistrasi": "2024-10-08 09:55:15",
        "noregistrasi": "2410080010",
        "so_norec": "bf3b1c37-71cf-40cc-899d-ff3005f4c436",
        "objectkelasfk": 1,
        "estimasiwaktuoperasi": "30 menit",
        "kamaroperasifk": 4,
        "dokteroperatorfk": 309,
        "dokteranastesifk": 312,
        "tglselesai": "2024-10-26T13:52:39.695Z",
        "objectpegawaiorderfk": 309,
        "dokteranakfk": null,
        "penerimafk": 308,
        "jenisoperasifk": 27,
        "statusoperasi": 1
      }
    },
    { token: data.token }
  );
  check(res, {
    "dashboard/save-order-pelayanan-bedah is status 200": (r) => r.status === 200,
  });
}
