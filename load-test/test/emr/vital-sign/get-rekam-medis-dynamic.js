import { check } from 'k6'
import { generateToken, getRekamMedisDynamic } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRekamMedisDynamic({ emrid: '147' }, { token: data.token })
  check(res, {
    'get-rekam-medis-dynamic is status 200': r => r.status === 200
  })
}
