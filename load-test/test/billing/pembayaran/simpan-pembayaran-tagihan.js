import { check } from "k6";
import { generateToken, savePembayaranTagihan } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePembayaranTagihan(
    {
      "parameterTambahan": "tagihanPasien",
      "norec": "16ec7636-c8cb-4454-9985-5b183f98288b",
      "norec_pd": "94cda121-bfa0-4455-b9c2-6a1a7fcfbb69",
      "nocm": "17.01.30",
      "namapasien": "LARASATIZKA AYUNINGTYAS",
      "jumlahbayar": 20000,
      "namapegawaipenerima": null,
      "tglsbm": "2024-10-27 21:30",
      "ruanganfk": 359,
      "details": [
        {
          "caraBayar": {
            "id": 1,
            "carabayar": "TUNAI",
            "total": 0
          },
          "nominal": 20000
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "kasir/pembayaran-tagihan/simpan is status 200": (r) => r.status === 200,
  });
}
