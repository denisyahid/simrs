import { check } from 'k6'
import { generateToken, getSettingPrinter } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getSettingPrinter({ token: data.token })
  check(res, {
    'general/printer is status 200': r => r.status === 200
  })
}
