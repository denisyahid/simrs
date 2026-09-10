import { check } from 'k6'
import { generateToken, getDataPasienLama } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDataPasienLama({ token: data.token })
  check(res, {
    'kiosk/get-pasien is status 200': r => r.status === 200
  })
}
