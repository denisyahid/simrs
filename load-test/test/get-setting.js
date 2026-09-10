import { check } from 'k6'
import http from 'k6/http'
import { baseURL } from '../src/api.js'

export default function () {
  const res = http.get(`${baseURL}/app/javascripts/Setting.js`)
  check(res, {
    'is status 200': r => r.status === 200
  })
}
