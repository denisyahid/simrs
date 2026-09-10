import { check } from "k6";
import { generateToken, savePasienFix } from "../../../src/api.js";
import { uuidv4,randomIntBetween } from "https://jslib.k6.io/k6-utils/1.4.0/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  const res = savePasienFix(
    {
      pasien: {
        id: "",
        isPenunjang: false,
        isbayi: false,
        nocmfkibu: null,
        noidentitas: uuidv4(),
        nobpjs: "000000000000",
        namapasien: "PASIEN SIMULASI " + randomIntBetween(1, 100),
        tempatlahir: "CIAMIS",
        tgllahir: "2016-02-02",
        objectjeniskelaminfk: 1,
        nohp: "0987654321",
        objectagamafk: 20,
        email: null,
        namaibu: null,
        objectstatusperkawinanfk: null,
        objectgolongandarahfk: null,
        objectpendidikanfk: null,
        objectpekerjaanfk: null,
        objectsukufk: null,
        noaditional: null,
        notelepon: null,
        namaayah: null,
        namakeluarga: null,
        namasuamiistri: null,
        penanggungjawab: null,
        hubungankeluargapj: null,
        telponpenanggungjawab: null,
        bahasa: null,
        jeniskelaminpenanggungjawab: null,
        umurpenanggungjawab: null,
        pekerjaanpenangggungjawab: null,
        alamatrmh: null,
        objectkebangsaanfk: 1,
        objectnegarafk: 0,
        progress: 99,
      },
      alamat: {
        alamatlengkap: "-",
        'rtrw': 0,
        objectpropinsifk: 12,
        objectkotakabupatenfk: 186,
        objectkecamatanfk: null,
        objectdesakelurahanfk: null,
        kodepos: null,
      },
    },
    { token: data.token }
  );
  check(res, {
    "save-pasien is status 200": (r) => r.status === 200,
  });
}
