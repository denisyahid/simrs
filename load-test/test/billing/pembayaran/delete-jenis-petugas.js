import { check } from "k6";
import { generateToken, deleteJenisPetugas } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = deleteJenisPetugas(
    {
        "norec": "8f100143-0145-4a21-b03e-56377a5f1d38",
        "objectpegawaifk": 402,
        "namaproduk": "PeLayanan Rawat Inap",
        "noregistrasi": "2410070006"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/billing/delete-jenis-petugas is status 200": (r) => r.status === 200,
  });
}
