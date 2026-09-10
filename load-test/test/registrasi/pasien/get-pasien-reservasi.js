import { check } from 'k6'
import { generateToken, getPasienReservasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienReservasi({ token: data.token })
  check(res, {
    'registrasi/list-pasien-reservasi is status 200': r => r.status === 200
  })
}
