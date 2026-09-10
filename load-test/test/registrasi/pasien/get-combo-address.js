import { check } from 'k6'
import { generateToken, getComboAddress } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboAddress({ token: data.token })
  check(res, {
    'get-combo-address is status 200': r => r.status === 200
  })
}
