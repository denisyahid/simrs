import { check } from 'k6'
import { generateToken, getNotulen } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getNotulen({}, { token: data.token })
  check(res, {
    'get-notulen is status 200': r => r.status === 200
  })
}
