import { check } from "k6";
import { generateToken, savePengembalianDeposit } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePengembalianDeposit(
    {
        "caraBayar": {
        "id": 1,
        "carabayar": "TUNAI",
        "total": 0
        },
        "nominal": -1000000
    },
    { token: data.token }
  );
  check(res, {
    "kasir/pembayaran-tagihan/simpan is status 200": (r) => r.status === 200,
  });
}
