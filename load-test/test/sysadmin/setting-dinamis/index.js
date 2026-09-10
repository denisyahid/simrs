import { generateToken } from '../../../src/api.js'
import getSettingFix from './get-setting-fix.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getSettingFix({ token: data.token })
}
