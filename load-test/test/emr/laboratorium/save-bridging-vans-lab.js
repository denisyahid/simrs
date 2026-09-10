import { check } from "k6";
import { generateToken, saveBridgingVansLab } from "../../../src/api.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = saveBridgingVansLab(
    {
      noorder: "L2304000001",
      iddokterverif: 3113,
      namadokterverif: "dr. CHANNIA",
      details: [
        {
          produkid: 4071,
          hargasatuan: "100000",
          hargadijamin: "0",
          qtyproduk: 1,
          komponenharga: [
            {
              objectkomponenhargafk: 93,
              komponenharga: "JASA RS",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 2,
              komponenharga: "JASA DOKTER",
              hargasatuan: "0",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
            {
              objectkomponenhargafk: 8,
              komponenharga: "TOTAL JASA PELAYANAN",
              hargasatuan: "100000",
              objectprodukfk: 4071,
              objectjenispelayananfk: 1,
              hargadijamin: "0",
            },
          ],
          tglpelayanan: "2023-04-05 14:27:54",
          nourut: 1,
        },
      ],
      catatan: null,
    },
    { token: data.token }
  );
  check(res, {
    "save-bridging-vans-lab is status 201": (r) => r.status === 201,
  });
}
