import { generateToken } from '../../../src/api.js'
import getDaftarRegistrasiPasien from './get-daftar-registrasi-pasien.js'
import DashboardRegistrasi from './dashboard-registrasi.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDaftarRegistrasiPasien({ token: data.token })
  DashboardRegistrasi({ token: data.token })
}
