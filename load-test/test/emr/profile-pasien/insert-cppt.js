import { check } from "k6";
import { generateToken, getDashboardRJ, simpanEMR } from "../../../src/api.js";

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
  const res = simpanEMR(
    {
      id: "",
      norec_emr: "",
      collection: "CatatanPerkembanganPasienTerintegrasi",
      url_form: "cppt",
      name_form: "Catatan Perkembangan Pasien Terintegrasi Rawat Jalan",
      jenis_emr: "asesmen_medis",
      data: {
        details: [
          {
            no: 1,
            tgl: "2023-10-04T15:42:17.750Z",
            S: "S",
            O: "O",
            A: "A ",
            P: "P",
          },
        ],
        dpjpUtama: { value: 19, label: "Bela Fitri Astria Yani" },
        dokterRawatBersama: { value: 17, label: "Tati Herawati, S.Kep" },
        pasien: {
          nocm: "0000135",
          nocmfk: "e141b9a0-9133-4adb-a10d-300491c5f784",
          namapasien: "PASIEN TEST k6686562c6-922d-4d84-b695-57dd9571d11d",
          tgllahir: "2016-02-02",
          tempatlahir: "CIAMIS",
          suku: null,
          objectjeniskelaminfk: 1,
          jeniskelamin: "Pria",
          noidentitas: "4b73eb35-5db9-417a-9ede-b13e065597c2",
          nobpjs: "000000000000",
          noasuransilain: null,
          alamatlengkap: "-",
          kodepos: null,
          notelepon: null,
          nohp: "0987654321",
          namaayah: null,
          namaibu: null,
          email: null,
          agama: "ISLAM",
          pendidikan: null,
          pekerjaan: null,
          isfoto: null,
          filename: null,
          umur: "7thn 8bln 2hr",
        },
        registrasi: {
          norec_apd: "5fc6bcd1-bc9c-4de2-b724-a3aa4a93ab48",
          norec_pd: "36e91ab1-1ef8-49de-8fac-79ef68643eaa",
          noregistrasi: "2310000025",
          tglregistrasi: "2023-10-04 22:12:43",
          kelompokpasien: "Umum/Pribadi",
          namarekanan: "Diri Sendiri",
          namakelas: "NON KELAS",
          objectruanganfk: 37,
          namaruangan: "POLI UMUM",
          dokter: null,
        },
      },
    },
    { token: data.token }
  );
  check(res, {
    "insert-cppt is status 200": (r) => r.status === 200,
  });
}
