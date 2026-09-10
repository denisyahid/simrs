import { check } from "k6";
import { generateToken, infoStok } from "../../../src/api.js";
export function setup() {
  return {
    token: generateToken().json()["response"]["token"] + ".home",
  };
}
export default async function (data) {
  const res = infoStok({}, { token: data.token });
  check(res, {
    "get-info-stok is status 200": (r) => r.status === 200,
  });
}
