import { check } from 'k6'
import { generateToken, getJadwalOperasi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJadwalOperasi( { token: data.token })
  check(res, {
    'dashboard/jadwal-operasi is status 200': r => r.status === 200
  })
}
