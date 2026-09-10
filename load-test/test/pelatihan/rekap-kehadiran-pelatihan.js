import { check } from 'k6'
import { generateToken, rekapKehadiranPelatihan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = rekapKehadiranPelatihan({}, { token: data.token })
  check(res, {
    'get-rekap-kehadiran-pelatihan is status 200': r => r.status === 200
  })
}
