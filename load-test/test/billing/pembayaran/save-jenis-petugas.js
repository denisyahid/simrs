import { check } from "k6";
import { generateToken, saveJenisPetugas } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveJenisPetugas(
    {
        "norec": "",
        "objectjenispetugaspefk": {
            "label": "Dokter Pemeriksa",
            "value": 4,
            "default": {
                "id": 4,"jenispetugaspe": "Dokter Pemeriksa"
            }
        },
        "objectpegawaifk": {
            "label": "dr. A. A. Istri Yulan Permatasari, Sp.BP-RE",
            "value": 1356,
            "default": {
                "namalengkap": "dr. A. A. Istri Yulan Permatasari, Sp.BP-RE",
                "id": 1356
            }
        },
        "nomasukfk": "a39941d1-5be0-40d8-abc0-2ee42a9de5a1",
        "noregistrasi": "2410070006",
        "pelayananpasien": "3166fc98-e5d0-4ce2-acbc-dec8a31e66ab",
        "namaproduk": "PeLayanan Rawat Inap",
        "namaruangan": "SANDAT"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/billing/save-jenis-petugas is status 200": (r) => r.status === 200,
  });
}
