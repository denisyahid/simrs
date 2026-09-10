import { check } from 'k6'
import { generateToken, getCetakResep } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getCetakResep( { token: data.token })
  check(res, {
    'report/farmasi/resep is status 200': r => r.status === 200
  })
}
