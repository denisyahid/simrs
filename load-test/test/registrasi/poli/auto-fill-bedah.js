import { check } from 'k6'
import { generateToken, getAutoFillBedah } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getAutoFillBedah({ token: data.token })
  check(res, {
    'bedah/get-data-autofill-bedah is status 200': r => r.status === 200
  })
}
