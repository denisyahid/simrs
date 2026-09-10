import { check } from 'k6'
import { generateToken, getDropdownUser } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownUser({}, { token: data.token })
  check(res, {
    'get-dropdown-user is status 200': r => r.status === 200
  })
}
