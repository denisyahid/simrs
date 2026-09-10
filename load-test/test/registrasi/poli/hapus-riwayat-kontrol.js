import { check } from "k6";
import { generateToken, hapusRiwayatKontrol } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = hapusRiwayatKontrol(
    {
        
    },
    { token: data.token }
  );
  check(res, {
    "pasien/hapus-riwayat-kontrol is status 200": (r) => r.status === 200,
  });
}
