import { check } from "k6";
import { generateToken, getDashboardRJ, simpanOrderLab } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default  function (data) {
  const from = new Date();
  // const to = new Date();
  // to.setDate(to.getDate() - 1);
  // let listPasien = await getDaftarAntrianRawatJalan(
  //   { tglAwal: from, tglAkhir: to, jmlRow: 1 },
  //   { token: data.token }
  // );
  // listPasien = listPasien.status === 200 ? listPasien.json()['response']['data'] : [];
  const res = simpanOrderLab(
    {
      noregistrasi: "2310000025",
      tanggal: "2023-10-04 22:44:51",
      tgloperasi: null,
      norec_so: "",
      norec_apd: "5fc6bcd1-bc9c-4de2-b724-a3aa4a93ab48",
      norec_pd: "36e91ab1-1ef8-49de-8fac-79ef68643eaa",
      qtyproduk: 3,
      objectruanganfk: 37,
      pegawaiorderfk: 1,
      objectruangantujuanfk: 83,
      departemenfk: 3,
      keterangan: null,
      iscito: false,
      details: [
        {
          no: 3,
          produkfk: "1002121866",
          qtyproduk: 1,
          objectkelasfk: 6,
          nourut: null,
        },
        {
          no: 2,
          produkfk: "1002121558",
          qtyproduk: 1,
          objectkelasfk: 6,
          nourut: null,
        },
        {
          no: 1,
          produkfk: "28168",
          qtyproduk: 1,
          objectkelasfk: 6,
          nourut: null,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "insert-order-laboratorium is status 200": (r) => r.status === 200,
  });
}
