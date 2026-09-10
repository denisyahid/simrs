import { check } from "k6";
import {
  generateToken,
  getDaftarAntrianRawatJalan,
  verifikasiResep,
} from "../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  // const from = new Date();
  // const to = new Date();
  // to.setDate(to.getDate() - 1);
  // let listPasien = getDaftarAntrianRawatJalan(
  //   { tglAwal: from, tglAkhir: to, jmlRow: 1 },
  //   { token: data.token }
  // );
  // listPasien = listPasien.status === 200 ? listPasien.json() : [];
  const res = verifikasiResep(
    {
      strukresep: {
        tglresep: "2023-06-06 09:46:05",
        pasienfk: "d61aad90-03a6-11ee-8462-530f9b24",
        nocm: "007759",
        namapasien: "EGIE RAMDAN d2ccd955-6be5-4b85-ba6d-53f7ee04d1ca",
        penulisresepfk: 3054,
        ruanganfk: 553,
        noorder: "2306000001",
        status: 0,
        norecResep: "",
        noresep: "-",
        retur: "-",
        isobatalkes: false,
        isreseppulang: 0,
        apotekerfk: 320263,
        cito: false,
      },
      pelayananpasien: [
        {
          no: 1,
          noregistrasifk: "d61aad90-03a6-11ee-8462-530f9b24",
          tglregistrasi: "2023-06-05 20:42",
          generik: null,
          hargajual: 11500,
          jenisobatfk: null,
          kelasfk: 6,
          stock: "10",
          harganetto: 0,
          nostrukterimafk: "4c152ee0-a147-11ed-b495-2b004103",
          ruanganfk: 553,
          rke: "1",
          jeniskemasanfk: 2,
          jeniskemasan: "Non Racikan",
          aturanpakaifk: 1,
          aturanpakai: "1x1",
          routefk: null,
          route: null,
          asalprodukfk: 2,
          asalproduk: "Rupiah Murni",
          produkfk: 4587,
          namaproduk: "ACYCLOVIR 200 MG",
          nilaikonversi: "1",
          satuanstandarfk: 335,
          satuanstandar: "TABLET",
          satuanviewfk: 335,
          satuanview: "TABLET",
          jmlstok: "336",
          jumlah: 1,
          jumlahobat: "1",
          dosis: "1",
          kekuatan: "200",
          hargasatuan: 11500,
          hargadiscount: "0",
          total: 11500,
          sediaan: "MG",
          jmldosis: "1/1",
          jasa: "0",
          ispagi: true,
          issiang: false,
          ismalam: false,
          issore: false,
          keterangan: "",
          satuanresepfk: null,
          satuanresep: null,
          tglkadaluarsa: "2023-02-23 17:00:00",
          isreseppulang: null,
          iter: null,
          obtkronis: "",
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-order-resep is status 201": (r) => r.status === 201,
  });
}
