import { check } from 'k6'
import { generateToken, getDropdownKonversi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownKonversi({ token: data.token })
  check(res, {
    'kasir/billing/tagihan-konversi-dropdown is status 200': r => r.status === 200
  })
}
