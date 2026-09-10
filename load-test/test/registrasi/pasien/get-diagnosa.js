import { check } from "k6";
import { generateToken, getDiagnosa } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = getDiagnosa(
    {"url":"referensi/diagnosa/Benign%20neoplasm%20of%20meninges","method":"GET","data":null},
    { token: data.token }
  );
  check(res, {
    "bridging/bpjs/tools/referensi/diagnosa is status 200": (r) => r.status === 200,
  });
}
