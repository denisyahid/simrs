import { check } from "k6";
import { generateToken, saveVerifikasiTagihan } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveVerifikasiTagihan(
    {
        "norec_pd": "37c2c324-6813-49e7-8016-f096b2aaef17",
        "noregistrasi": "2410210003",
        "nocm": "170109",
        "namapasien": "MADE KARMI",
        "total": 43560,
        "deposit": 0,
        "klaim": 0,
        "totalbayar": 43560,
        "totaliurbayar": 0,
        "details": [{
            "norec": "13019de7-e3f7-45d2-8c8c-494fc4d9e005",
            "namaproduk": "PARACETAMOL 500 MG TABLET",
            "namakelas": "NON KELAS",
            "tglpelayanan": "2024-10-21 17:10:27",
            "namaruangan": "POLI GIGI",
            "strukresepfk": "e12b3d5c-103f-457a-b9e1-ecd0d6d1f98f",
            "jumlah": "180",
            "hargasatuan": "242",
            "penulisresep": "dr. I GEDE HERMAWAN, SP. RAD",
            "norec_apd": "131019ca-9b6b-4c7e-bdab-a693c17d9330",
            "jasa": "0",
            "hargadiscount": "0",
            "total": "43560",
            "tglpelayanan_group": "2024-10-21",
            "jenis": "Resep",
            "statusverifikasi": "Belum Verifikasi",
            "dokterpemeriksa": "dr. I GEDE HERMAWAN, SP. RAD",
            "checked": false
        }],
        "multipenjamin": []
    },
    { token: data.token }
  );
  check(res, {
    "kasir/verifikasi-tagihan/simpan is status 200": (r) => r.status === 200,
  });
}
