import { check } from "k6";
import {
  generateToken,
  getDaftarTagihanPasien,
  getDataPembayaran,
} from "../../../src/api.js";
export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const from = new Date();
  const to = new Date();
  to.setDate(to.getDate() - 1);

  let listPasien = getDaftarTagihanPasien(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  );
  listPasien = listPasien.status === 200 ? listPasien.json()["response"] : [];

  const res = getDataPembayaran(
    {
      noRecStrukPelayanan:
        listPasien.length > 0
          ? listPasien[0].norec
          : "7803334a-0be6-46db-9b7c-9db0722844e3",
    },
    { token: data.token }
  );
  check(res, {
    "get-data-pembayaran is status 200": (r) => r.status === 200,
  });
}
