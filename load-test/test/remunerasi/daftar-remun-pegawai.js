import { check } from 'k6'
import { generateToken, daftarRemunPegawai } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = daftarRemunPegawai({}, { token: data.token })
  check(res, {
    'get-daftar-remun-pegawai is status 200': r => r.status === 200
  })
}
