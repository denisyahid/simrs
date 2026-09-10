import { check } from "k6";
import { generateToken, saveVerifRad } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveVerifRad(
    {
      details: [
        {
        produkfk: 1057,
        namaproduk: "Bone Survey",
        qtyproduk: "1",
        objectkelasfk: 6
        },
        {
        produkfk: 1031,
        namaproduk: "Abdomen 3 posisi",
        qtyproduk: "1",
        objectkelasfk: 6
        }
    ],
    noorder: "R2410000001",
    objectkelasfk: 6,
    objectruangantujuanfk: 330,
    objectpegawaiorderfk: 396,
    iddokterverif: "386",
    namadokterverif: "dr. PUTU DIAH SAVITRI, Sp.Rad",
    idadmin: 143,
    namaadmin: "TRISNANDARI",
    idradiografer: 130,
    namaradiografer: "I KOMANG WIDIANA",
    catatan_klinis: "-"
    },
    { token: data.token }
  );
  check(res, {
    "bridging/penunjang/save-bridging-zeta is status 200": (r) => r.status === 200,
  });
}

