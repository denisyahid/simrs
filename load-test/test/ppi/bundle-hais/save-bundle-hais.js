import { check } from "k6";
import {
  generateToken,
  getDaftarAntrianRawatJalan,
  saveBundleHais,
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
  let listPasien = getDaftarAntrianRawatJalan(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  );
  listPasien = listPasien.status === 200 ? listPasien.json() : [];
  const res = saveBundleHais(
    {
      norec: "",
      tanggal: "2023-06-06 10:43",
      petugas: 320263,
      norec_apd:
        listPasien.length > 0
          ? listPasien[0].norec_apd
          : "d61aad90-03a6-11ee-8462-530f9b24",
      norec_pd:
        listPasien.length > 0
          ? listPasien[0].norec_pd
          : "d5f7a880-03a6-11ee-991c-a3ff7ded",
      jenis: "IAD",
      detail: [
        {
          idbundle: 7,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Lokasi pemasangan sesuai ",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: true,
          statustidak: false,
        },
        {
          idbundle: 1,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Melakukan kebersihan tangan",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: true,
          statustidak: false,
        },
        {
          idbundle: 2,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Menggunakan APD lengkap dan sarung tangan steril",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: true,
          statustidak: false,
        },
        {
          idbundle: 3,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Area insersi dipasang duk bolong steril ",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: true,
          statustidak: false,
        },
        {
          idbundle: 4,
          kelompok: "A. Saat Pemasangan ",
          keterangan:
            "Preparasi kulit area insersi menggunakan chlorhexidine 2%/4%",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 5,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Alat kesehatan yang digunakan steril",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 6,
          kelompok: "A. Saat Pemasangan ",
          keterangan: "Area Insersi ditutup menggunakan transparan dresing",
          jenisbundle: "IAD",
          nourut: "1",
          namakelompok: "Saat Pemasangan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 13,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Desinfeksi area konektor sebelum memberikan injeksi.",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 8,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Slang Infus diganti setiap 72 jam",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 9,
          kelompok: "B. Penggantian Peralatan ",
          keterangan:
            "Slang infus bekas pemberisn parenteral nutrisi diganti setiap 24 jam",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 10,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Spuit yang digunakan disposibel",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 11,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Perawatan lokasi insersi setiap 3 hari dan jika kotor",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 12,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Memberikan injeksi menggunakan port needles",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
        {
          idbundle: 14,
          kelompok: "B. Penggantian Peralatan ",
          keterangan: "Hand Hygiene sesuai 5 moment",
          jenisbundle: "IAD",
          nourut: "2",
          namakelompok: "Penggantian Peralatan ",
          statusiya: false,
          statustidak: false,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-order-resep is status 201": (r) => r.status === 201,
  });
}
