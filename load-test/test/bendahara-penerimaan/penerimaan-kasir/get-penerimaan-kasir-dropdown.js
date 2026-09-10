import { check } from 'k6'
import { generateToken, getPenerimaanKasirDropdown } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenerimaanKasirDropdown({}, { token: data.token })
  check(res, {
    'get-penerimaan-kasir-dropdown is status 200': r => r.status === 200
  })
}
