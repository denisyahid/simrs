import { generateToken } from "../src/api.js";


// import registrasiList from "./registrasi/list/index.js";
import registrasiPasien from "./registrasi/pasien/index.js";
import registrasiPoli from "./registrasi/poli/index.js";

export function setup() {
  return {
    token: generateToken().json()["response"]["token"],
  };
}

export default function (data) {
  registrasiPasien({ token: data.token });
  registrasiPoli({ token: data.token });
  // registrasiList({ token: data.token });
 
}
