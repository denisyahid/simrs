import { check } from 'k6'
import { generateToken, getDropdownRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownRegistrasi({ token: data.token })
  check(res, {
    'dashboard/registrasi/dropdown is status 200': r => r.status === 200
  })
}
