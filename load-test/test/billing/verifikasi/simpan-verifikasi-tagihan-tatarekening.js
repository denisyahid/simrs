import { check } from "k6";
import {
  generateToken,
  getDaftarPasienPulang,
  getDetailTagihanVerifikasi,
  getVerifikasiTagihan2,
  simpanVerifikasiTagihanTataRekening,
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

  let listPasien = getDaftarPasienPulang(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  );
  listPasien = listPasien.status === 200 ? listPasien.json()["response"] : [];

  const noregistrasi =
    listPasien.length > 0
      ? listPasien[0].norec_pd
      : "a2bdedd5-0a11-4284-b527-9d81b261147c";
  let detail = getVerifikasiTagihan2(
    { noRegister: noregistrasi },
    { token: data.token }
  );
  detail = detail.status === 200 ? detail.json()["response"]["detail"] : [];

  const res = simpanVerifikasiTagihanTataRekening(
    {
      norec_pd: listPasien != undefined && listPasien.length?listPasien[0].norec_od: "a2bdedd5-0a11-4284-b527-9d81b261147c",
      noregistrasi:listPasien!= undefined  && listPasien.length?listPasien[0].noregistrasi: "2310000019",
      nocm: "0000094",
      namapasien: "Juang",
      total: 105000,
      deposit: 0,
      klaim: 0,
      totalbayar: 105000,
      details: detail != undefined && detail.length ? detail :[
        {
          norec: "0f599607-a21c-40d0-bf04-7f6b3bde0cd6",
          namaproduk: "BHP RAPID ANTIGEN COVID NON BTT",
          namakelas: "NON KELAS",
          tglpelayanan: "2023-10-04 23:28:00",
          namaruangan: "CEMPAKA 2",
          strukresepfk: null,
          jumlah: "1",
          hargasatuan: "105000",
          penulisresep: null,
          norec_apd: "0db13882-6147-4779-aabf-4169bedd9c85",
          jasa: "0",
          hargadiscount: "0",
          total: "105000",
          tglpelayanan_group: "2023-10-04",
          jenis: "Layanan",
          dokterpemeriksa: "dr. Administrator",
          checked: false,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "simpan-verifikasi-tagihan is status 200": (r) => r.status === 200,
  });
}
