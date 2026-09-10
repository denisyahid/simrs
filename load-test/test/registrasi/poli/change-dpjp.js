import { check } from "k6";
import { generateToken, changeDPJP } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = changeDPJP(
    {
        "objectpegawaifk": 426
    },
    { token: data.token }
  );
  check(res, {
    "registrasi/change-dokter-dpjp is status 200": (r) => r.status === 200,
  });
}
