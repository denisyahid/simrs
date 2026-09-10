import { check } from "k6";
import { generateToken, getRujukanPeserta } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = getRujukanPeserta(
    {
      "url": "Rujukan/RS/List/Peserta/0003050784279",
      "method": "GET",
      "data": null
    },
    { token: data.token }
  );
  check(res, {
    "bridging/bpjs/tools/Rujukan/List/Peserta is status 200": (r) => r.status === 200,
  });
}
