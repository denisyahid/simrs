import { check } from 'k6'
import { generateToken, getListPaket } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListPaket({ token: data.token })
  check(res, {
    'tindakan/list-paket is status 200': r => r.status === 200
  })
}
