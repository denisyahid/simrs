import { check } from 'k6'
import { generateToken, cetakIdentitasPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cetakIdentitasPasien({ token: data.token })
  check(res, {
    'dashboard/registrasi/cetak-identitas-pasien is status 200': r => r.status === 200
  })
}
