import { generateToken } from '../../src/api.js'
import getViewBed from './get-view-bed.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getViewBed({ token: data.token })
}
