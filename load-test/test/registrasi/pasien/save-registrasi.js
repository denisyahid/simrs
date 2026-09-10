import { check } from "k6";
import { generateToken, saveRegistrasi } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveRegistrasi(
    {
      "pasiendaftar": {
        "norec": "",
        "nocmfk": "9327f35c-f361-46c1-b43a-ee8f28f6f6d5",
        "tglregistrasi": "2024-10-27 18:26:13",
        "objectruanganlastfk": 220,
        "asalrujukanfk": 5,
        "keteranganasalrujukan": null,
        "objectkelompokpasienlastfk": 1,
        "jenispelayananfk": 1,
        "objectpegawaifk": 395,
        "objectpegawairawatbersamafk": null,
        "objectkelasfk": null,
        "objectkelasrawatfk": null,
        "israwatinap": false,
        "catatan": null,
        "statuspasien": "BARU",
        "objectrekananfk": 0,
        "nocm": "17.01.30",
        "namapasien": "LARASATIZKA AYUNINGTYAS",
        "antrianpasienregistrasifk": null,
        "iskelastitip": null
      },
      "antrianpasiendiperiksa": {
        "norec": "",
        "objectkamarfk": null,
        "nobed": null,
        "israwatgabung": null
      }
    },
    { token: data.token }
  );
  check(res, {
    "registrasi/save-registrasi is status 200": (r) => r.status === 200,
  });
}
