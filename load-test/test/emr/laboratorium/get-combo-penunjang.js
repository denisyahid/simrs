import { check } from 'k6'
import { generateToken, getComboPenunjang } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getComboPenunjang({ token: data.token })
  check(res, {
    'get-combo-penunjang is status 200': r => r.status === 200
  })
}
