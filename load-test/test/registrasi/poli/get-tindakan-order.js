import { check } from 'k6'
import { generateToken, getTindakanOrder } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTindakanOrder({ token: data.token })
  check(res, {
    'laboratorium/list-tindakan-for-order is status 200': r => r.status === 200
  })
}
