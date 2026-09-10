import { check } from 'k6'
import { generateToken, monitoringHistory } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = monitoringHistory({}, { token: data.token })
  check(res, {
    'monitoring-bpjs is status 200': r => r.status === 200
  })
}
