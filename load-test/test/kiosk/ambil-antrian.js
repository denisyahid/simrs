import { check } from "k6";
import { generateToken, ambilAntrian } from "../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const res = ambilAntrian(
    {
      jenis: "A",
      ruanganfk: null,
    },
    { token: data.token }
  );
  check(res, {
    "save-antrian-kiosk is status 201": (r) => r.status === 201,
  });
}
