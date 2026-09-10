import { check } from "k6";
import { generateToken, saveAntrianLama } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveAntrianLama(
    {
        "jenis": "LB",
        "ruanganfk": 201,
        "nocmfk": "486",
        "loketid": "1",
        "nopeserta": null,
        "namapeserta": null,
        "kelompokpasien": 2,
        "jenispelayanan": 1,
        "objectpegawaifk": 396,
        "noantrian": null,
        "ruanganfklama": "SEC033"
    },
    { token: data.token }
  );
  check(res, {
    "kiosk/save-antrian is status 200": (r) => r.status === 200,
  });
}
