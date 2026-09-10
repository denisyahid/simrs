import { check } from 'k6'
import { generateToken, listDropdownRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = listDropdownRegistrasi({ token: data.token })
  check(res, {
    'registrasi/list-dropdown is status 200': r => r.status === 200
  })
}
