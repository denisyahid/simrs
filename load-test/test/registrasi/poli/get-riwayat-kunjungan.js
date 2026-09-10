import { check } from 'k6'
import { generateToken, getRiwayatKunjungan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatKunjungan({ token: data.token })
  check(res, {
    'tindakan/list-dropdown-registrasi is status 200': r => r.status === 200
  })
}
