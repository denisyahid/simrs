import { check } from "k6";
import { generateToken, saveAntrianBaru } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveAntrianBaru(
    {
        "jenis": "B",
        "loketid": "2",
        "noantrian": null
    },
    { token: data.token }
  );
  check(res, {
    "kiosk/save-antrian-baru is status 200": (r) => r.status === 200,
  });
}
