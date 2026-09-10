import { check } from "k6";
import { generateToken, saveBatalBayar } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveBatalBayar(
    {
        "norec_sbmcr": "6f119c85-5d7e-47e3-aaa9-0c5e9f738b4a",
        "norec": "d9eed4f3-27f9-49d2-b781-3bc09705371e",
        "norec_sp": "23ed92f0-791c-49b4-bdc6-0c667d6cf227",
        "namapasien": "pasien test 2",
        "nocm": null,
        "nosbm": "RV-24100000071",
        "isdeposit": false
    },
    { token: data.token }
  );
  check(res, {
    "kasir/daftar-penerimaan/batal-bayar is status 200": (r) => r.status === 200,
  });
}
