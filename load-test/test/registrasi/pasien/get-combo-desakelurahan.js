import { check } from 'k6'
import { generateToken, getComboDesaKelurahan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboDesaKelurahan({ token: data.token })
  check(res, {
    'get-combo-kecamatan is status 200': r => r.status === 200
  })
}
