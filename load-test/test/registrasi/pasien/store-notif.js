import { check } from "k6";
import { generateToken, storeNotif } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = storeNotif(
    {
        "method":"get"
    },
    { token: data.token }
  );
  check(res, {
    "general/store-notif is status 200": (r) => r.status === 200,
  });
}
