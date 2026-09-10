import { check } from 'k6'
import { generateToken, getDataComboKasir } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDataComboKasir({ token: data.token })
  check(res, {
    'get-data-combo-kasir is status 200': r => r.status === 200
  })
}
