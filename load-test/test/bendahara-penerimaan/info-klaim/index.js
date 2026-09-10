import { generateToken } from '../../../src/api.js'
import getInfoKlaim from './get-info-klaim.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getInfoKlaim({ token: data.token })
}
