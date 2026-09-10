import { check } from 'k6'
import { generateToken, getJumlahLoket } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJumlahLoket({ token: data.token })
  check(res, {
    'kiosk/get-jumlah-loket is status 200': r => r.status === 200
  })
}
