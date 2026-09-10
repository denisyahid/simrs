import { check } from "k6";
import { generateToken, getDaftarAntrianRawatJalan, saveOrderPelayanan } from "../../../src/api.js";
import { formatDateUrlQuery } from "../../../src/utils.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien = getDaftarAntrianRawatJalan(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json() : []
  const res = saveOrderPelayanan(
    {
      tanggal: formatDateUrlQuery(new Date()),
      norec_so: "",
      norec_apd:
        listPasien.length > 0
          ? listPasien[0].norec_apd
          : "32e49db0-d420-11ed-bd8f-9b101b16",
      norec_pd:
        listPasien.length > 0
          ? listPasien[0].norec_pd
          : "32e382a0-d420-11ed-a500-2b40ee3a",
      qtyproduk: 1,
      objectruanganfk: 810,
      objectruangantujuanfk: 575,
      departemenfk: 3,
      pegawaiorderfk: 3047,
      keterangan: null,
      catatanklinis: null,
      iscito: false,
      details: [
        {
          no: 1,
          produkfk: "4071",
          namaproduk: "4071",
          qtyproduk: 1,
          objectruanganfk: 810,
          objectruangantujuanfk: 575,
          pemeriksaanluar: 0,
          iscito: false,
          objectkelasfk: 6,
          nourut: null,
          limit: false,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-order-pelayanan is status 201": (r) => r.status === 201,
  });
}
