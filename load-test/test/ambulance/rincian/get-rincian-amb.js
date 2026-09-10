import { check } from 'k6'
import { generateToken, getRincianAmb } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRincianAmb({}, { token: data.token })
  check(res, {
    'get-rincian-ambulance is status 200': r => r.status === 200
  })
}
