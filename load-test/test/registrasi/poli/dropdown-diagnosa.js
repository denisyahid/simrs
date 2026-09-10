import { check } from 'k6'
import { generateToken, getDropdownDiagnosa } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownDiagnosa({ token: data.token })
  check(res, {
    'diagnosa/list-dropdown is status 200': r => r.status === 200
  })
}
