import { check } from 'k6'
import { generateToken, getViewBed } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getViewBed({}, { token: data.token })
  check(res, {
    'get-view-bed is status 200': r => r.status === 200
  })
}
