import { check } from "k6";
import { generateToken, saveClosing } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveClosing(
    {
      "close": true,
      "noregistrasi": "2410310007"
    },
    { token: data.token }
  );
  check(res, {
    "kasir/closing-pemeriksaan is status 200": (r) => r.status === 201 || r.status === 200,
  });
}
