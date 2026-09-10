import { check } from 'k6'
import { generateToken, getEMRTerakhir } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getEMRTerakhir({ token: data.token })
  check(res, {
    'emr/get-emr-tgl-terakhir is status 200': r => r.status === 200
  })
}
