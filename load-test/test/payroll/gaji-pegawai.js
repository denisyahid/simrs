import { check } from 'k6'
import { generateToken, gajiPegawai } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = gajiPegawai({}, { token: data.token })
  check(res, {
    'get-payroll is status 200': r => r.status === 200
  })
}
