import { check } from 'k6'
import { generateToken, getListKelas } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListKelas({ token: data.token })
  check(res, {
    'emr/dropdown/kelas_m is status 200': r => r.status === 200
  })
}
