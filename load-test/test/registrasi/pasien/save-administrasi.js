import { check } from "k6";
import { generateToken, saveAdministrasi } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveAdministrasi(
    {
      "norec": "e0cec5d1-b6f3-4ffe-bfe0-383b7cc50b5f",
      "norec_apd": "89933b73-9655-4522-a816-82a9d6f8d6ec",
      "objectkebangsaanfk": 1
    },
    { token: data.token }
  );
  check(res, {
    "registrasi/save-adminsitrasi is status 200": (r) => r.status === 200,
  });
}
