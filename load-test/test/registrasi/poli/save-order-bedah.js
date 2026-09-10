import { check } from "k6";
import { generateToken, saveOrderBedah } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveOrderBedah(
    {
      "noregistrasi": "2410080010",
      "tanggal": "2024-10-27 20:04:30",
      "tgloperasi": "2024-10-31 20:05:47",
      "norec_so": "",
      "norec_apd": "ee01a75d-3761-4019-8f02-103550d96099",
      "norec_pd": "9c013ba7-d596-45ed-97b7-3a1fea399739",
      "qtyproduk": 0,
      "objectruanganfk": 305,
      "pegawaiorderfk": 349,
      "objectruangantujuanfk": 362,
      "jenisoperasifk": null,
      "kamaroperasifk": null,
      "departemenfk": 45,
      "keterangan": "qwertyuiop",
      "iscito": false,
      "dokteroperatorfk": 309,
      "dokteroperatortambahan": [
        null
      ],
      "dokteranastesifk": 312,
      "diagnosis": "ckmb",
      "persiapan": "tfgyuhijokp[",
      "durasi": "30 menit",
      "tb": "155",
      "bb": "45",
      "jaminan": "BPJS KESEHATAN",
      "nohp": "082339530055",
      "nohpkel": null,
      "iselektif": false,
      "isurgent": false,
      "details": []
    },
    { token: data.token }
  );
  check(res, {
    "bedah/simpan-order is status 200": (r) => r.status === 200,
  });
}
