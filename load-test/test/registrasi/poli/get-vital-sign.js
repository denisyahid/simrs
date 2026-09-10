import { check } from 'k6'
import { generateToken, getVitalSign } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getVitalSign({ token: data.token })
  check(res, {
    'emr/get-data-exist is status 200': r => r.status === 200
  })
}
