import { check } from "k6";
import {
  generateToken,
  getDaftarAntrianRawatJalan,
  getDashboardRJ,
  saveOrderResep,
} from "../../../src/api.js";
import { formatDateUrlQuery } from "../../../src/utils.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default  function (data) {
  // const from = new Date();
  // const to = new Date();
  // to.setDate(to.getDate() - 1);
  // let listPasien = getDashboardRJ(
  //   { tglAwal: from, tglAkhir: to, jmlRow: 1 },
  //   { token: data.token }
  // );
  // listPasien = listPasien.status === 200 ? listPasien.json() : [];
  const res = saveOrderResep(
    // {
    //   data: [
    //     {
    //       strukorder: {
    //         norec: "",
    //         tglresep: formatDateUrlQuery(new Date()),
    //         penulisresepfk: 3054,
    //         ruanganfk: 553,
    //         noregistrasifk:
    //           listPasien.length > 0
    //             ? listPasien[0].norec_apd
    //             : "d61aad90-03a6-11ee-8462-530f9b24",
    //         qtyproduk: 1,
    //         noruangan: 0,
    //         isreseppulang: 0,
    //       },
    //       orderfarmasi: [
    //         {
    //           no: 1,
    //           generik: null,
    //           hargajual: 11500,
    //           jenisobatfk: null,
    //           stock: 336,
    //           harganetto: 11500,
    //           nostrukterimafk: "4c152ee0-a147-11ed-b495-2b004103",
    //           ruanganfk: 553,
    //           rke: 1,
    //           jeniskemasanfk: 2,
    //           jeniskemasan: "Non Racikan",
    //           aturanpakaifk: 0,
    //           aturanpakai: "1x1",
    //           ispagi: 1,
    //           issiang: 0,
    //           issore: 0,
    //           ismalam: 0,
    //           asalprodukfk: 0,
    //           asalproduk: "",
    //           produkfk: 4587,
    //           namaproduk: "ACYCLOVIR 200 MG",
    //           nilaikonversi: 1,
    //           satuanstandarfk: 335,
    //           satuanstandar: "TABLET",
    //           satuanviewfk: 335,
    //           satuanview: "TABLET",
    //           jmlstok: 336,
    //           jumlah: 1,
    //           hargasatuan: 11500,
    //           hargadiscount: 0,
    //           total: 11500,
    //           dosis: 1,
    //           jmldosis: "1/1",
    //           keterangan: "",
    //           satuanresepfk: null,
    //           satuanresep: null,
    //           tglkadaluarsa: formatDateUrlQuery(new Date()),
    //           isoutofstok: false,
    //         },
    //       ],
    //     },
    //   ],
    // },
    {
      data: [
        {
          strukorder: {
            norec: "",
            tglresep: "2023-10-04 11:14:58",
            penulisresepfk: 1,
            ruanganfk: 3,
            noregistrasifk: "5fc6bcd1-bc9c-4de2-b724-a3aa4a93ab48",
            qtyproduk: 1,
            noruangan: null,
            isreseppulang: null,
          },
          orderpelayanan: [
            {
              no: 1,
              noregistrasifk: "5fc6bcd1-bc9c-4de2-b724-a3aa4a93ab48",
              generik: null,
              hargajual: "3900",
              jenisobatfk: null,
              jenisobat: null,
              kelasfk: 6,
              stock: "1",
              harganetto: "3000",
              nostrukterimafk: "ca7cba5c-20df-4eb4-897a-27ea610d01e0",
              norec_spd: "b1e6c728-53c1-4fdd-a154-e6ee4b916507",
              ruanganfk: 3,
              rke: 1,
              jeniskemasanfk: 2,
              jeniskemasan: "Non Racikan",
              aturanpakaifk: 0,
              aturanpakai: "2x1",
              ispagi: 0,
              issiang: 1,
              issore: 0,
              ismalam: 1,
              routefk: null,
              route: null,
              asalprodukfk: 1,
              asalproduk: "Badan Layanan Umum",
              produkfk: 1,
              namaproduk: "PARACETAMOL 500MG",
              nilaikonversi: 1,
              satuanstandarfk: 335,
              satuanstandar: "TABLET",
              satuanviewfk: 335,
              satuanview: "TABLET",
              jmlstok: "1",
              jumlah: 1,
              jumlahobat: 1,
              dosis: 1,
              hargasatuan: "3900",
              hargadiscount: "0",
              persendiscount: 0,
              total: 3900,
              jmldosis: "1/1/500",
              jasa: 0,
              keterangan: null,
              satuanresepfk: null,
              satuanresep: null,
              obtkronis: "",
              icon: "fa-inverse lnir lnir-medicine-alt",
              color: "primary",
            },
          ],
          noregistrasi: "2310000025",
        },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-order-resep is status 200": (r) => r.status === 200,
  });
}
