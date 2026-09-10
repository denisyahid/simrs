import { check } from "k6";
import { generateToken, savePasienKonsul } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePasienKonsul(
    {
      "norec_pd": "9c013ba7-d596-45ed-97b7-3a1fea399739",
      "asalRujukanfk": 23,
      "norec": "",
      "dokterfk": 101,
      "objectruanganasalfk": 305,
      "objectruangantujuan": 201,
      "kelasfk": 6
    },
    { token: data.token }
  );
  check(res, {
    "registrasi/simpan-pasien-konsul is status 200": (r) => r.status === 200,
  });
}
