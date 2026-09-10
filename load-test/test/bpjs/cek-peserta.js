import { check } from "k6";
import { generateToken, cekPeserta } from "../../src/api.js";

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cekPeserta(
    {
      url: "Peserta/nik/3207070103940001/tglSEP/2023-06-06",
      method: "GET",
      data: null,
    },
    { token: data.token }
  );
  check(res, {
    "get-integrasi-cek-peserta is status 200": (r) => r.status === 200,
  });
}
