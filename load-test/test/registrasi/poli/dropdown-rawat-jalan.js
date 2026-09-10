import { check } from 'k6'
import { generateToken, getDropdownRajal } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownRajal({ token: data.token })
  check(res, {
    'dashboard/dropdown-rawat-jalan is status 200': r => r.status === 200
  })
}
