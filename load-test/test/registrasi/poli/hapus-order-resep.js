import { check } from "k6";
import { generateToken, hapusOrderResep } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = hapusOrderResep(
    {
      "norec": "c700962d-a422-4023-8909-95366d0a0f91"
    },
    { token: data.token }
  );
  check(res, {
    "farmasi/hapus-order is status 200": (r) => r.status === 200,
  });
}
