import { check } from 'k6'
import { generateToken, getComboRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboRegistrasi({ token: data.token })
  check(res, {
    'get-combo-registrasi is status 200': r => r.status === 200
  })
}
