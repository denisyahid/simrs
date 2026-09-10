import { check } from "k6";
import { generateToken, savePegawai } from "../../src/api.js";
import { uuidv4 } from 'https://jslib.k6.io/k6-utils/1.4.0/index.js'

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default async function (data) {
  const res = savePegawai(
    {
      datapegawai: {
        id: "",
        nippns: "-",
        namalengkap: "RAMDAN" + uuidv4(),
        gelardepan: "",
        gelarbelakang: "",
        nama: "RAMDAN",
        tempatlahir: "CIAMIS",
        tgllahir: "1995-03-01 00:00:00",
        jeniskelamin: 1,
        pendidikan: 3,
        statusperkawinan: 8,
        npwp: "-",
        agama: 4,
        tglmeninggal: null,
        unitkerjafk: 114,
        nomorrekening: "-",
        namarekening: "-",
        namabank: "-",
        alamat: "-",
        tglmasuk: null,
        tglkeluar: null,
        pensiun: null,
        tglpensiun: null,
        statuspegawai: null,
        kedudukan: null,
        golongan: null,
        jabatan: null,
        kelompokjabatan: null,
        eselon: null,
        idfinger: null,
        shiftkerja: null,
        nilaijabatan: null,
        grade: null,
        jenispegawai: 2,
        email: null,
      },
      datakeluarga: [],
      riwayatpendidikan: [],
      riwayatpelatihan: [],
      riwayatjabatan: [],
      komponengajiadd: [],
      komponengajidel: [],
      simpantelp: [
        { norec: "", noTelp: "", providerfk: null },
        { norec: "", noTelp: "", providerfk: null },
      ],
    },
    { token: data.token }
  );
  check(res, {
    "save-pegawai is status 201": (r) => r.status === 201,
  });
}
