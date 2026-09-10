import { check } from "k6";
import { generateToken, saveBatalPasienKonsul } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveBatalPasienKonsul(
    {
        "norec": "dcd2d2c8-abf3-48f5-8712-5ca30fe3e72c",
        "noregistrasi": "2410210002",
        "ruangantujuan": "POLI OBGYN",
        "nocm": "168513",
        "namapasien": "ZAFRAN MIKAIL"
    },
    { token: data.token }
  );
  check(res, {
    "emr/hapus-order-konsul is status 200": (r) => r.status === 200,
  });
}
