import { check } from 'k6'
import { generateToken, getDropdownKirim } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownKirim( { token: data.token })
  check(res, {
    'get-dropdown-kirim is status 200': r => r.status === 200
  })
}
