import { check } from 'k6'
import { generateToken, getPasienAktif } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienAktif({ token: data.token })
  check(res, {
    'kasir/daftar-pasien-aktif is status 200': r => r.status === 200
  })
}
