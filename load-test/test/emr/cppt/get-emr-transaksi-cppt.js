import { check } from "k6";
import {
  generateToken,
  getDaftarAntrianRawatJalan,
  getEMRTransaksi,
} from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const from = new Date();
  const to = new Date();
  to.setDate(to.getDate() - 1);
  let listPasien = await getDaftarAntrianRawatJalan(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  );
  listPasien = listPasien.status === 200 ? listPasien.json() : [];
  const res = getEMRTransaksi(
    {
      noRegistrasi:
        listPasien.length > 0 ? listPasien[0].noregistrasi : "2304000001",
      jenisEmr: "catatandokter",
    },
    { token: data.token }
  );
  check(res, {
    "get-emr-traksaksi-cppt is status 200": (r) => r.status === 200,
  });
}
