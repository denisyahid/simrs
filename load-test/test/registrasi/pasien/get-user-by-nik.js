import { check } from "k6";
import { generateToken, getUserNIK } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = getUserNIK(
    {"url":"Peserta/nik/3273121204000001/tglSEP/2024-10-21","method":"GET","data":null},
    { token: data.token }
  );
  check(res, {
    "bridging/bpjs/tools/Peserta/nik is status 200": (r) => r.status === 200,
  });
}
