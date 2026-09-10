import { check } from "k6";
import {
  generateToken,
  getDaftarTagihanPasien,
  simpanDataPembayaran,
} from "../../../src/api.js";
import { formatDateUrlQuery } from "../../../src/utils.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default  function (data) {
  const from = new Date();
  const to = new Date();
  to.setDate(to.getDate() - 1);

  let listPasien =  getDaftarTagihanPasien(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  );
  listPasien = listPasien.status === 200 ? listPasien.json()['response'] : [];
  const res = simpanDataPembayaran(
    {
      parameterTambahan: "tagihanPasien",
      norec: listPasien.length? listPasien[0].norec :"7803334a-0be6-46db-9b7c-9db0722844e3",
      norec_pd:listPasien.length? listPasien[0].norec_pd : "a2bdedd5-0a11-4284-b527-9d81b261147c",
      nocm: "0000094",
      namapasien: "Juang",
      jumlahbayar: "105000",
      namapegawaipenerima: null,
      tglsbm: "2023-10-04 23:45",
      details: [
        {
          caraBayar: { id: 1, carabayar: "TUNAI", total: 0 },
          nominal: "105000",
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "simpan-data-pembayaran is status 200": (r) => r.status === 200,
  });
}
