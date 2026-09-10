import { check } from 'k6'
import { generateToken, getAutoFill } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getAutoFill({ token: data.token })
  check(res, {
    'emr/auto-fill is status 200': r => r.status === 200
  })
}
