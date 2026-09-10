import { check } from 'k6'
import { generateToken, getDropdownBundle } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownBundle( { token: data.token })
  check(res, {
    'get-dropdown-bundle is status 200': r => r.status === 200
  })
}
