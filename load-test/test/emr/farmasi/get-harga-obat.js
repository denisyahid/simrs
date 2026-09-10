import { check } from "k6";
import { generateToken, hargaObat } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"] + ".home",
  };
}

export default  function (data) {
  const res = hargaObat(
    { produkfk: "1", ruanganfk: "3", kpid: "1" },
    { token: data.token }
  );
  check(res, {
    "get-harga-obat is status 200": (r) => r.status === 200,
  });
}
