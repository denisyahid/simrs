import { check } from 'k6'
import { generateToken, getListPegawai } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListPegawai({}, { token: data.token })
  check(res, {
    'get-list-pegawai is status 200': r => r.status === 200
  })
}
