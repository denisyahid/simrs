import { check } from 'k6'
import { generateToken, getListVerif } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListVerif({ token: data.token })
  check(res, {
    'dashboard/get-lab-verify is status 200': r => r.status === 200
  })
}
