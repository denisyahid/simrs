import { check } from "k6";
import { generateToken, saveHapusTindakan } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveHapusTindakan(
    {
      "data": [
        {
          "norec_pp": "2baa9b61-5b7c-4df6-904a-dfb22f57b962",
          "namaproduk": "Pembuatan Kartu Pasien",
          "namaruangan": "POLI KEDOKTERAN NUKLIR"
        }
      ],
      "nocm": "17.01.27",
      "namapasien": "MANTAN",
      "noregistrasi": "2410250009"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/billing/hapus-tindakan is status 200": (r) => r.status === 200,
  });
}
