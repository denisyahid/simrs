import { check } from 'k6'
import { generateToken, getPasienRegistrasiRev } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienRegistrasiRev({ token: data.token })
  check(res, {
    'registrasi/pasien-registrasi-rev is status 200': r => r.status === 200
  })
}
