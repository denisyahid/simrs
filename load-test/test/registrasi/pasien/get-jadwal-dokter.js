import { check } from 'k6'
import { generateToken, getJadwalDokter } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJadwalDokter({ token: data.token })
  check(res, {
    'kiosk/get-dokterbyruangan is status 200': r => r.status === 200
  })
}
