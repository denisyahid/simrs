import { check } from 'k6'
import { generateToken, getPasienPulang } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienPulang({ token: data.token })
  check(res, {
    'kasir/daftar-pasien-pulang is status 200': r => r.status === 200
  })
}
