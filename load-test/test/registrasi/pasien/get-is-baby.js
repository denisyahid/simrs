import { check } from 'k6'
import { generateToken, getIsBaby } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getIsBaby({ token: data.token })
  check(res, {
    'laporan/check-is-baby is status 200': r => r.status === 200
  })
}
