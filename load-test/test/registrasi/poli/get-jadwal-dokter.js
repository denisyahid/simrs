import { check } from 'k6'
import { generateToken, getJadwalDokterPoli } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJadwalDokterPoli({ token: data.token })
  check(res, {
    'kiosk/get-dokterbyruangan-semuatgl is status 200': r => r.status === 200
  })
}
