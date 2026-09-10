import { check } from 'k6'
import { generateToken, getSettingFix } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getSettingFix({}, { token: data.token })
  check(res, {
    'get-stting-fix is status 200': r => r.status === 200
  })
}
