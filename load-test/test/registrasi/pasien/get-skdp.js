import { check } from "k6";
import { generateToken, getSKDP } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = getSKDP(
    {
      "url": "RencanaKontrol/ListRencanaKontrol/Bulan/10/Tahun/2024/Nokartu/0003050784279/filter/2",
      "method": "GET",
      "data": null
    },
    { token: data.token }
  );
  check(res, {
    "bridging/bpjs/tools/RencanaKontrol/ListRencanaKontrol is status 200": (r) => r.status === 200,
  });
}
