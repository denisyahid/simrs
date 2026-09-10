import { check } from "k6";
import {
  generateToken,
  getDaftarOrder,
  saveOrderPelayanan,
  savePelayananPasien,
} from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  let listPasien = await getDaftarOrder(
    { isNotVerif: true, jmlRow: '5' },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json()['data'] : []
  const res = savePelayananPasien(
    {
      bridging: [
        {
          produkid: 4071,
          hargasatuan: "100000",
          hargadijamin: "0",
          qtyproduk: 1,
          komponenharga: [
            {
              objectkomponenhargafk: 93,
              komponenharga: "JASA RS",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 2,
              komponenharga: "JASA DOKTER",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 8,
              komponenharga: "TOTAL JASA PELAYANAN",
              hargasatuan: "100000",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
          ],
          tglpelayanan: listPasien.length > 0
          ? listPasien[0].tglorder : "2023-04-05 14:27:54",
          nourut: 1,
        },
      ],
      norec_pp: "",
      noorder:    listPasien.length > 0
      ? listPasien[0].noorder : "L2304000001",
      norec_so:listPasien.length > 0
      ? listPasien[0].norec_so : "611d8370-d383-11ed-afbf-a99390c0",
      objectkelasfk: 6,
      norec_pd: listPasien.length > 0
      ? listPasien[0].norec_pd : "95d69580-d382-11ed-9612-c5a36fb7",
      objectruangantujuanfk: 575,
      objectpegawaiorderfk: 3047,
      iddokterverif: 3113,
      namadokterverif: "dr. CHANNIA",
      iddokterorder: 3047,
      namadokterorder: "dr. APRILIA KAREN MANDAGIE, Sp.KK",
      idradiografer: null,
      namaradiografer: null,
      details: [
        {
          produkid: 4071,
          hargasatuan: "100000",
          hargadijamin: "0",
          qtyproduk: 1,
          komponenharga: [
            {
              objectkomponenhargafk: 93,
              komponenharga: "JASA RS",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 2,
              komponenharga: "JASA DOKTER",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 8,
              komponenharga: "TOTAL JASA PELAYANAN",
              hargasatuan: "100000",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
          ],
          tglpelayanan: listPasien.length > 0
          ? listPasien[0].tglorder : "2023-04-05 14:27:54",
          nourut: 1,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-pelayanan-pasien is status 201": (r) => r.status === 201,
  });
}
