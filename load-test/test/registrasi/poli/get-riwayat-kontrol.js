import { check } from 'k6'
import { generateToken, getRiwayatKontrol } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatKontrol({ token: data.token })
  check(res, {
    'pasien/get-riwayat-kontrol is status 200': r => r.status === 200
  })
}
