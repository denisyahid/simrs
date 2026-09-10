import { check } from "k6";
import { generateToken, savePaketObat } from "../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePaketObat(
    {
      "namapaket": "PAKET K7",
      "paketobat": [
        {
          "produkfk": 8267,
          "satuanresepfk": 56,
          "aturanpakai": 28,
          "jumlah": 10
        }
      ]
    },
    { token: data.token }
  );
  check(res, {
    "sysadmin/save-master-paket-obat is status 200": (r) => r.status === 200,
  });
}
