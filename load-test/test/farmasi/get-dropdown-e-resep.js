import { check } from 'k6'
import { generateToken, getDropdownEResep } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownEResep( { token: data.token })
  check(res, {
    'get-dropdown-e-resep is status 200': r => r.status === 200
  })
}
