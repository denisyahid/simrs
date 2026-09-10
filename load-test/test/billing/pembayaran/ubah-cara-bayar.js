import { check } from "k6";
import { generateToken, saveCaraBayarRev } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveCaraBayarRev(
    {"norec": "6f119c85-5d7e-47e3-aaa9-0c5e9f738b4a",
        "carabayar": 12,
        "carabayarname": "TRANSFER BANK MANDIRI",
        "namapasien": "pasien test 2",
        "nocm": null,
        "nosbm": "RV-24100000071"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/daftar-penerimaan/ubah-cara-bayar is status 200": (r) => r.status === 200,
  });
}
