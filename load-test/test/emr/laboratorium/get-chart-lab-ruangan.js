import { check } from 'k6'
import { generateToken, getDataChartLabRuangan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDataChartLabRuangan({ token: data.token })
  check(res, {
    'dashboard/chart-lab-ruangan is status 200': r => r.status === 200
  })
}
