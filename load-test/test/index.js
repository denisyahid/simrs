import { generateToken } from "../src/api.js";
// import billingGetDaftarPasienPulang from "./billing/get-daftar-pasien-pulang.js";
// import billingGetDetailTagihan from "./billing/get-detail-tagihan.js";
import billingPembayaran from "./billing/pembayaran/index.js";
// import billingVerifikasi from "./billing/verifikasi/index.js";
import emrLaboratorium from "./emr/laboratorium/index.js";
import radiologi from "./emr/radiologi/index.js";
// import emrTindakan from "./emr/tindakan/index.js";
// import emrVitalSign from "./emr/vital-sign/index.js";
// import emrFarmasi from "./emr/farmasi/index.js";
// import registrasiList from "./registrasi/list/index.js";
import registrasiPasien from "./registrasi/pasien/index.js";
import registrasiPoli from "./registrasi/poli/index.js";

// import BPJS from "./bpjs/index.js";
import farmasi from "./farmasi/index.js";
import bedah from "./bedah/index.js";
// import gizi from "./gizi/index.js";
// import KIOSK from "./kiosk/index.js";
// import SATUSEHAT from "./satu-sehat/index.js";
// import profilePasien from "./emr/profile-pasien/index.js";
// import tindakan from "./emr/tindakan/index.js";
export function setup() {
  return {
    token: generateToken().json()['response']['token'],
  };
}

export default function (data) {
  // registrasiList({ token: data.token });/
  // profilePasien({ token: data.token });/
  // tindakan({ token: data.token });/
  // emrVitalSign({ token: data.token })
  // emrTindakan({ token: data.token })
  // emrFarmasi({ token: data.token });
  // billingGetDetailTagihan({ token: data.token });/
  // billingGetDaftarPasienPulang({ token: data.token });/
  // billingVerifikasi({ token: data.token });/
  // modul add on
  // BPJS({ token: data.token })
  // gizi({ token: data.token })
  // KIOSK({ token: data.token })
  // SATUSEHAT({ token: data.token })


  
  registrasiPasien({ token: data.token });
  registrasiPoli({ token: data.token });
  emrLaboratorium({ token: data.token });
  radiologi({token:data.token})
  billingPembayaran({ token: data.token });
  farmasi({ token: data.token })
  bedah({ token: data.token })

}
