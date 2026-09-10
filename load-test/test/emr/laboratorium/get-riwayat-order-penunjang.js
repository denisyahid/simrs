import { check } from "k6";
import {
  generateToken,
  getDaftarAntrianRawatJalan,
  getRiwayatOrderPenunjang,
} from "../../../src/api.js";

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}
export default  function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien =  getDaftarAntrianRawatJalan(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json() : []
  const res = getRiwayatOrderPenunjang(
    {
      noregistrasi:
        listPasien.length > 0 ? listPasien[0].noregistrasi : "2210000002",
    },
    { token: data.token }
  );
  check(res, {
    "get-riwayat-order-penunjang is status 200": (r) => r.status === 200,
  });
}
