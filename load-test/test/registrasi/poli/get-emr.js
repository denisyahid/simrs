import { check } from 'k6'
import { generateToken, getEMR } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getEMR({ token: data.token })
  check(res, {
    'emr/get-emr is status 200': r => r.status === 200
  })
}
