import { check } from 'k6'
import { generateToken, getPegawai } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPegawai({ token: data.token })
  check(res, {
    'emr/dropdown/pegawai_m is status 200': r => r.status === 200
  })
}
