import { check } from 'k6'
import { generateToken, getComboKecamatan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboKecamatan({ token: data.token })
  check(res, {
    'get-combo-kecamatan is status 200': r => r.status === 200
  })
}
