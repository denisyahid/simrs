import { check } from 'k6'
import { generateToken, getInputResepOrder } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getInputResepOrder( { token: data.token })
  check(res, {
    'farmasi/input-resep-order is status 200': r => r.status === 200
  })
}
