import { check } from "k6";
import { generateToken, saveSuratKontrol } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveSuratKontrol(
    {
        "url":"/RencanaKontrol/insert",
        "method":"POST",
        "data":{
            "request":{
                "noSEP":"0233R7791024V000580",
                "poliKontrol":"UMU",
                "tglRencanaKontrol":"2024-10-21",
                "user":"User Administrator "
            }
        }
    },
    { token: data.token }
  );
  check(res, {
    "bridging/bpjs/tools/RencanaKontrol/insert is status 200": (r) => r.status === 200,
  });
}
