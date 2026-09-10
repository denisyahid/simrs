import { check } from 'k6'
import { generateToken, getDropdownPegawaiRegis } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownPegawaiRegis({ token: data.token })
  check(res, {
    'dropdown/pegawai_m is status 200': r => r.status === 200
  })
}
