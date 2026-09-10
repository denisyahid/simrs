import { check } from 'k6'
import { generateToken, getCPPT } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getCPPT({ token: data.token })
  check(res, {
    'emr/get-emr-cppt is status 200': r => r.status === 200
  })
}
