import { check } from 'k6'
import { generateToken, getComboTindakan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboTindakan({ token: data.token })
  check(res, {
    'get-combo-tindakan is status 200': r => r.status === 200
  })
}
