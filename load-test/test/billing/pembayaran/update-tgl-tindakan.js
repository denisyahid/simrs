import { check } from "k6";
import { generateToken, saveTglTindakan } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveTglTindakan(
    {
      "norec": "de2120d0-2df7-4075-bf53-28942de6128d",
      "namaproduk": "Biaya Registrasi Pasien",
      "noregistrasi": "2410270002",
      "tglpelayanan": "2024-10-26 18:32"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/billing/update-tgl-tindakan is status 200": (r) => r.status === 200,
  });
}
