import { check } from "k6";
import { generateToken, saveTindakanPoli } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveTindakanPoli(
    {
        "pelayananpasien": [
          {
            "id": 1031,
            "namaproduk": "Abdomen 3 posisi",
            "objectruanganfk": 330,
            "hargasatuan": "300000",
            "jumlah": 1,
            "komponenharga": [
              {
                "objectkomponenhargafk": 93,
                "komponenharga": "JASA SARANA",
                "hargasatuan": "240000",
                "objectprodukfk": 3378,
                "iscito": false,
                "hargadijamin": null,
                "diskon": "0"
              },
              {
                "objectkomponenhargafk": 94,
                "komponenharga": "JASA PELAYANAN",
                "hargasatuan": "160000",
                "objectprodukfk": 3378,
                "iscito": false,
                "hargadijamin": null,
                "diskon": "0"
              }
            ],
            "tanggal": "21-10-2024",
            "kelasfk": 6,
            "norec_apd": "84bf2340-2b2a-4b8b-9576-5125346df2fa",
            "norec_pd": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
            "tglregistrasi": "2024-10-21 15:33:01",
            "dokter": 396,
            "diskon": 0,
            "iscito": 0,
            "isparamedis": 0,
            "produkfk": 1031,
            "tglpelayanan": "2024-10-21 17:07:39"
          },
          {
            "id": 3378,
            "namaproduk": "ADA",
            "objectruanganfk": 335,
            "hargasatuan": "400000",
            "jumlah": 1,
            "komponenharga": [
              {
                "objectkomponenhargafk": 93,
                "komponenharga": "JASA SARANA",
                "hargasatuan": "240000",
                "objectprodukfk": 3378,
                "iscito": false,
                "hargadijamin": null,
                "diskon": "0"
              },
              {
                "objectkomponenhargafk": 94,
                "komponenharga": "JASA PELAYANAN",
                "hargasatuan": "160000",
                "objectprodukfk": 3378,
                "iscito": false,
                "hargadijamin": null,
                "diskon": "0"
              }
            ],
            "tanggal": "21-10-2024",
            "kelasfk": 6,
            "norec_apd": "84bf2340-2b2a-4b8b-9576-5125346df2fa",
            "norec_pd": "3561f89d-6194-436f-92fc-0dc5cda13e8f",
            "tglregistrasi": "2024-10-21 15:33:01",
            "dokter": 396,
            "diskon": 0,
            "iscito": 0,
            "isparamedis": 0,
            "produkfk": 3378,
            "tglpelayanan": "2024-10-21 17:07:39"
          }
        ],
        "noregistrasi": "2410210002",
        "nocm": "168513",
        "namapasien": "ZAFRAN MIKAIL",
        "namaruangan": "POLI INTERNA"
    },
    { token: data.token }
  );
  check(res, {
    "tindakan/save-tindakan is status 200": (r) => r.status === 200,
  });
}
