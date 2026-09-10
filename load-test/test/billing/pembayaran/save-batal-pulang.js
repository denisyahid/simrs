import { check } from "k6";
import { generateToken, saveBatalPulang } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveBatalPulang(
    {
        "norec_pd": "c2e094d4-a81f-4a8f-9b19-ccc7df7e97d9"
    },
    { token: data.token }
  );
  check(res, {
    "rawatinap/batal-pulang-pasien is status 200": (r) => r.status === 200,
  });
}
