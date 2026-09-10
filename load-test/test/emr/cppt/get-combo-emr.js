import { check } from 'k6'
import { generateToken, getComboEMR } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboEMR({ token: data.token })
  check(res, {
    'get-combo-emr is status 200': r => r.status === 200
  })
}
