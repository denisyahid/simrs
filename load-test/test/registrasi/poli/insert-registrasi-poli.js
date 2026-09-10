import { check } from "k6";
import {
  generateToken,
  getPasienLama,
  insertRegistrasiPoli,
} from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default  function (data) {
  let listPasien =  getPasienLama({ Rows: 10 }, { token: data.token });
  listPasien =
    listPasien.status === 200 ? listPasien.json()["response"]["data"] : [];
  const now = new Date();
  const res = insertRegistrasiPoli(
    {
      pasiendaftar: {
        norec: "",
        nocmfk:
          listPasien.length > 0
            ? listPasien[0].id
            : "98821811-6065-40ef-a724-cd8004d16512",
        tglregistrasi: `${now.getFullYear()}-${
          now.getMonth() + 1
        }-${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`,
        tglregistrasidate: `${now.getFullYear()}-${
          now.getMonth() + 1
        }-${now.getDate()}`,
        objectruanganlastfk: 35,
        asalrujukanfk: 5,
        objectkelompokpasienlastfk: 1,
        jenispelayananfk: 1,
        objectpegawaifk: 103,
        objectpegawairawatbersamafk: null,
        objectkelasfk: null,
        israwatinap: false,
        catatan: null,
        statuspasien: "LAMA",
        objectrekananfk: 0,
        nocm: listPasien.length > 0 ? listPasien[0].nocm : "00004109",
        namapasien:
          listPasien.length > 0
            ? listPasien[0].namapasien
            : "TN. TAUFIK ISMAIL",
        antrianpasienregistrasifk: null,
      },
      antrianpasiendiperiksa: { norec: "", objectkamarfk: null, nobed: null },
    },
    { token: data.token }
  );
  check(res, {
    "insert-registrasi is status 200": (r) => r.status === 200,
  });
}
