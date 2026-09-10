import { check } from "k6";
import { generateToken, saveOrder } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveOrder(
    {
      "noregistrasi": "2410080010",
      "tanggal": "2024-10-27 19:56:10",
      "tgloperasi": null,
      "norec_so": "",
      "norec_apd": "ee01a75d-3761-4019-8f02-103550d96099",
      "norec_pd": "9c013ba7-d596-45ed-97b7-3a1fea399739",
      "qtyproduk": 1,
      "objectruanganfk": 305,
      "pegawaiorderfk": 349,
      "objectruangantujuanfk": 335,
      "departemenfk": 3,
      "catatanKlinis": "-",
      "keterangan": null,
      "iscito": false,
      "namafile": "2410080010",
      "details": [
        {
          "no": 1,
          "produkfk": "4005",
          "qtyproduk": 1,
          "objectkelasfk": 1,
          "nourut": null
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "laboratorium/simpan-order is status 200": (r) => r.status === 200,
  });
}
