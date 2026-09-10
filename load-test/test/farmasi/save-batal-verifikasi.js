import { check } from "k6";
import { generateToken, saveBatalVerifikasi } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveBatalVerifikasi(
    {
        "norec": "6c66b620-49a1-48d8-b982-52ec9c5ea2b4",
        "nocm": "163023"
    },
    { token: data.token }
  );
  check(res, {
    "dashboard/apotik/batal-verifikasi is status 200": (r) => r.status === 200,
  });
}
