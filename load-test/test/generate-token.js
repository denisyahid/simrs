import { check } from 'k6'
import { generateToken } from '../src/api.js'

export default function () {
  const res = generateToken()
  check(res, {
    'is status 200': r => r.status === 200
  })
}
