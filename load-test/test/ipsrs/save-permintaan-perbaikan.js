import { check } from "k6";
import { generateToken, savePermintaanPerbaikan } from "../../src/api.js";
import { formatDateUrlQuery } from "../../src/utils.js";


export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const res = savePermintaanPerbaikan(
    {
      norec: "",
      tglplanning: formatDateUrlQuery(new Date()),
      ruangandesc: 819,
      rincian: "AC BOCOR",
      idpelapor: 3017,
      pelapor: "ANASTASIA DEWI, Amd.Kep",
      ruangantujuan: 58,
    },
    { token: data.token }
  );
  check(res, {
    "save-permintaan-perbaikan is status 201": (r) => r.status === 201,
  });
}
