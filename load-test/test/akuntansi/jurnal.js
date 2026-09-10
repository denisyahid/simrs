import { check } from 'k6'
import { generateToken, jurnal } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = jurnal({}, { token: data.token })
  check(res, {
    'get-jurnal is status 200': r => r.status === 200
  })
}
