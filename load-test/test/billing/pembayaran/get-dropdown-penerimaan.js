import { check } from 'k6'
import { generateToken, getDropdownPenerimaan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownPenerimaan({ token: data.token })
  check(res, {
    'kasir/daftar-penerimaan/dropdown is status 200': r => r.status === 200
  })
}
