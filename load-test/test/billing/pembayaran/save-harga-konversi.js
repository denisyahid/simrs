import { check } from "k6";
import { generateToken, saveHargaKonversi } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveHargaKonversi(
    {
      "norec_pd": "84f9e271-e66d-4f81-8884-c6279477fa2c",
      "noregistrasi": "2410070006",
      "idKelas": 1,
      "idKelPasien": 1,
      "idPenjamin": null,
      "tglawal": null,
      "tglakhir": null,
      "data": []
    },
    { token: data.token }
  );
  check(res, {
    "kasir/billing/simpan-harga-konversi is status 200": (r) => r.status === 200,
  });
}
