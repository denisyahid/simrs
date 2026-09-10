import { check } from 'k6'
import { generateToken, getJenisPetugas } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getJenisPetugas({ token: data.token })
  check(res, {
    'tindakan/list-map-jenis-petugas is status 200': r => r.status === 200
  })
}
