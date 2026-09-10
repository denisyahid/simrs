import { check } from "k6";
import {
  generateToken,
  getDaftarPasienPulang,
  getVerifikasiTagihan2,
} from "../../../src/api.js";
export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  }
}

export default  function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien =  getDaftarPasienPulang(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json()['response']: []

  const res = getVerifikasiTagihan2(
    {
      noRegister:
        listPasien.length > 0
          ? listPasien[0].norec_pd
          : "a2bdedd5-0a11-4284-b527-9d81b261147c",
    },
    { token: data.token }
  );
  check(res, {
    "get-verifikasi-tagihan is status 200": (r) => r.status === 200,
  });
}
