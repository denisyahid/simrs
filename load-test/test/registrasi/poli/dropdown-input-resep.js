import { check } from 'k6'
import { generateToken, getDropdownInputResep } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownInputResep({ token: data.token })
  check(res, {
    'farmasi/input-resep-cbo is status 200': r => r.status === 200
  })
}
