import { check } from "k6";
import { generateToken, saveDistribusi } from "../../../src/api.js";
import { formatDateUrlQuery } from "../../../src/utils.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const res = saveDistribusi(
    {
      strukkirim: {
        objectpegawaipengirimfk: 320263,
        objectruanganfk: 553,
        objectruangantujuanfk: 817,
        jenispermintaanfk: 2,
        keteranganlainnyakirim: "tes",
        qtydetailjenisproduk: 0,
        qtyproduk: 1,
        tglkirim: "2023-06-06 11:17:15",
        totalhargasatuan: 0,
        norecOrder: null,
        noreckirim: "",
        norec_apd: 0,
      },
      details: [
        {
          no: 1,
          hargajual: "500",
          stock: "870",
          harganetto: "500",
          nostrukterimafk: "0b3b3990-bf25-11ed-a13f-53a8a677",
          ruanganfk: 553,
          asalprodukfk: 7,
          asalproduk: "Kas Kecil",
          produkfk: 4516,
          kdproduk: null,
          namaproduk: "PARACETAMOL 500 MG",
          nilaikonversi: 1,
          satuanstandarfk: 335,
          satuanstandar: "TABLET",
          satuanviewfk: 335,
          satuanview: "TABLET",
          jmlstok: "870",
          jumlah: "10",
          qtyorder: 0,
          hargasatuan: "500",
          hargadiscount: "0",
          total: 1,
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-distribusi is status 201": (r) => r.status === 201,
  });
}
