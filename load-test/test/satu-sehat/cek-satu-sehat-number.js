import { check } from "k6";
import { generateToken, cekSatuSehatNumber } from "../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"] + ".home",
  };
}

export default function (data) {
  const res = cekSatuSehatNumber(
    {
      url: "Patient?identifier=https://fhir.kemkes.go.id/id/nik|3207070103940001",
      method: "GET",
      data: null,
    },
    { token: data.token }
  );
  check(res, {
    "get-satu-sehat is status 200": (r) => r.status === 200,
  });
}
