import { check } from 'k6'
import { generateToken, getDropdownPegawai } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownPegawai({ token: data.token })
  check(res, {
    'get-dropdown-pegawai is status 200': r => r.status === 200
  })
}
