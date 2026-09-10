import { check } from 'k6'
import { generateToken, getTanggalOperasi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTanggalOperasi( { token: data.token })
  check(res, {
    'dashboard/jadwal-operasi is status 200': r => r.status === 200
  })
}
