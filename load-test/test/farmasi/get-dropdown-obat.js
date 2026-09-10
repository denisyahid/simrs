import { check } from 'k6'
import { generateToken, getDropdownObat } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownObat( { token: data.token })
  check(res, {
    'farmasi/dropdown-obat is status 200': r => r.status === 200
  })
}
