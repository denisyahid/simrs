import { check } from 'k6'
import { generateToken, getModulApp } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getModulApp({}, { token: data.token })
  check(res, {
    'get-modul-aplikasi is status 200': r => r.status === 200
  })
}
