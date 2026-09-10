import { check } from "k6";
import { generateToken, suratRegisRanap } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = suratRegisRanap(
    {
        "norec":"e331a487-a07b-4420-9c62-4e9e0093b865",
        "bahasa":"Indonesia",
        "bantuanPelayanan":"Tidak",
        "bantuanPenerjemah":"tidak",
        "dikunjungi":"Tidak"
    },
    { token: data.token }
  );
  check(res, {
    "dashboard/registrasi/save-surat-regis-ranap is status 200": (r) => r.status === 200,
  });
}
