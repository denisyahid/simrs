import { check } from 'k6'
import { generateToken, getAgendaRapat } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getAgendaRapat({}, { token: data.token })
  check(res, {
    'get-agenda-rapat is status 200': r => r.status === 200
  })
}
