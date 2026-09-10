import { check } from 'k6'
import { generateToken, getJasaPelayanan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJasaPelayanan({}, { token: data.token })
  check(res, {
    'get-jasa-pelayanan is status 200': r => r.status === 200
  })
}
