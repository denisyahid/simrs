import { check } from 'k6'
import { generateToken, getMasterJadwal } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getMasterJadwal({ token: data.token })
  check(res, {
    'sysadmin/master-jadwal-dokter is status 200': r => r.status === 200
  })
}
