import { check } from "k6";
import { generateToken, saveStokOpname } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const res = saveStokOpname(
    {
      ruanganId: 553,
      namaRuangan: "DEPO FARMASI",
      tglClosing: "2023-06-06 11:15",
      stokProduk: [
        { produkfk: 4592, stokSistem: 75, stokReal: 100, selisih: 25 },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-stok-opname is status 201": (r) => r.status === 201,
  });
}
