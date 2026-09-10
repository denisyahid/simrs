import { check } from "k6";
import {
  generateToken,

  getDashboardRJ,

  saveTindakan,
} from "../../../src/api.js";
import { formatDateUrlQuery } from "../../../src/utils.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const from = new Date();
  const to = new Date();
  to.setDate(to.getDate() - 1);
  let listPasien = getDashboardRJ(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  );
  listPasien =
    listPasien.status === 200 ? listPasien.json()["response"]["data"] : [];
  const res = saveTindakan(
    {
      pelayananpasien: [
        {
          no: 1,
          tglpelayanan: formatDateUrlQuery(new Date()),
          produkfk: 43022,
          namaproduk: "Aff jahitan- Khusus II, > 25 Jahitan",
          hargasatuan: "170000",
          jumlah: 1,
          pelayananpetugas: [
            {
              objectjenispetugaspefk: 4,
              jenispetugaspe: "Dokter Pemeriksa",
              listpegawai: [{ id: 103, namalengkap: "AULIA RIZKI MAULANA" }],
            },
          ],
          komponenharga: [
            {
              objectkomponenhargafk: 93,
              komponenharga: "JASA SARANA",
              hargasatuan: "102000",
              objectprodukfk: 43022,
              iscito: false,
              hargadijamin: null,
              diskon: "0",
            },
            {
              objectkomponenhargafk: 94,
              komponenharga: "JASA PELAYANAN",
              hargasatuan: "68000",
              objectprodukfk: 43022,
              iscito: false,
              hargadijamin: null,
              diskon: "0",
            },
          ],
          iscito: false,
          icon: "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
          jasacito: 0,
          isparamedis: false,
          diskon: 0,
          subtotal: 170000,
          kelasfk: 6,
          norec_apd:
            listPasien.length > 0
              ? listPasien[0].norec_apd
              : "5fc6bcd1-bc9c-4de2-b724-a3aa4a93ab48",
          norec_pd:
            listPasien.length > 0
              ? listPasien[0].norec_pd
              : "36e91ab1-1ef8-49de-8fac-79ef68643eaa",
          tglregistrasi: "2023-10-04 22:12:43",
        },
      ],
      noregistrasi:
        listPasien.length > 0 ? listPasien[0].noregistrasi : "2310000025",
      nocm: listPasien.length > 0 ? listPasien[0].nocm : "0000135",
      namapasien: "PASIEN TEST k6686562c6-922d-4d84-b695-57dd9571d11d",
      namaruangan: "POLI UMUM",
    },
    { token: data.token }
  );
  check(res, {
    "input-tindakan is status 200": (r) => r.status === 200,
  });
}
