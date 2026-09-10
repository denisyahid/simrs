import { check } from "k6";
import { generateToken, saveRadBilling } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveRadBilling(
    {
      "data": [
        {
          "idProduk": 969,
          "hargaLayanan": "360000",
          "tglpelayanan": "2024-11-02 01:06:54",
          "jumlah": "1",
          "komponenharga": [
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "216000",
              "objectprodukfk": 969,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "324000",
              "objectprodukfk": 969,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "144000",
              "objectprodukfk": 969,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "216000",
              "objectprodukfk": 969,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            }
          ]
        },
        {
          "idProduk": 922,
          "hargaLayanan": "550000",
          "tglpelayanan": "2024-11-02 01:06:54",
          "jumlah": "1",
          "komponenharga": [
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "330000",
              "objectprodukfk": 922,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "495000",
              "objectprodukfk": 922,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "220000",
              "objectprodukfk": 922,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "330000",
              "objectprodukfk": 922,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            }
          ]
        },
        {
          "idProduk": 924,
          "hargaLayanan": "700000",
          "tglpelayanan": "2024-11-02 01:06:54",
          "jumlah": "1",
          "komponenharga": [
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "420000",
              "objectprodukfk": 924,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 93,
              "komponenharga": "JASA SARANA",
              "hargasatuan": "630000",
              "objectprodukfk": 924,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "280000",
              "objectprodukfk": 924,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            },
            {
              "objectkomponenhargafk": 94,
              "komponenharga": "JASA PELAYANAN",
              "hargasatuan": "420000",
              "objectprodukfk": 924,
              "objectjenispelayananfk": 1,
              "hargadijamin": "0"
            }
          ]
        }
      ],
      "parameter": {
        "idruangtujuan": 330,
        "pd_norec": "b783969e-c858-4acd-9081-b4232e170434",
        "objectpegawaiorderfk": 312,
        "tglregistrasi": "2024-10-30 08:21:58",
        "noregistrasi": "2410300001",
        "so_norec": "4a18a3b8-e458-481d-b3ef-63e9b53db8a5",
        "catatan": "b",
        "dokterverify": "386",
        "pegawaiverifikatorfk": 131,
        "radiograferfk": 143,
        "catatanklinis": "a",
        "tglpelayanan": "2024-11-05 23:16:43",
        "nobatchradionuklida": null,
        "nobatchradiofarmaka": null,
        "dosisradiofarmasis": null,
        "jampermintaan": null,
        "dosisfullsyringe": null,
        "jamfullsyringe": null,
        "dosisemptysyringe": null,
        "jamemptysyringe": null,
        "rutelokasisuntik": null,
        "jaminjeksi": null,
        "pemeriksaanradiograferfk": null,
        "jamakuisisi": null,
        "treatment": null,
        "paparanradiasi": null
      }
    },
    { token: data.token }
  );
  check(res, {
    "dashboard/radiologi/save-order-pelayanan is status 200": (r) => r.status === 200,
  });
}


