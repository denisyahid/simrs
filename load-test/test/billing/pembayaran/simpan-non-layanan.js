import { check } from "k6";
import { generateToken, saveNonLayanan } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveNonLayanan(
    {
      "norec": "",
      "objectkelompoktransaksifk": 1,
      "keteranganlainnya": null,
      "objectkelompokpasienfk": 1,
      "objectrekananfk": 0,
      "namapasien_klien": "AMA",
      "noteleponfaks": "0812345678",
      "tglstruk": "2024-10-27 21:26:27",
      "totalharusdibayar": 60000,
      "details": [
        {
          "no": 1,
          "produkfk": 3960,
          "namaproduk": "LED",
          "jumlah": 1,
          "qtyoranglast": 0,
          "harga": 60000,
          "keterangan": "-",
          "total": 60000,
          "freetext": false
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "kasir/tagihan-non-layanan/simpan is status 200": (r) => r.status === 200,
  });
}
