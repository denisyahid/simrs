import { generateToken } from '../src/api.js'
import aset from './aset/index.js'
import bed from './bed/index.js'
import CSSD from './cssd/index.js'
import EOffice from './e-office/index.js'
import IPSRS from './ipsrs/index.js'
import logistikDistribusi from './logistik/distribusi-barang/index.js'
import logistikStokOpname from './logistik/stok-opname/index.js'
import logistikStokRuangan from './logistik/stok-ruangan/index.js'
import payroll from './payroll/index.js'
import pelatihan from './pelatihan/index.js'
import perencanaan from './perencanaan/index.js'
import remunerasi from './remunerasi/index.js'
import sanitasi from './sanitasi/index.js'
import SDM from './sdm/index.js'
import sysadminHarga from './sysadmin/harga-netto/index.js'
import sysadminUser from './sysadmin/list-user/index.js'
import sysadminMap from './sysadmin/map-menu/index.js'
import sysadminSetting from './sysadmin/setting-dinamis/index.js'
import akuntansi from './akuntansi/index.js'
import ambulanceDaftarOrder from './ambulance/daftar-order/index.js'
import ambulanceDaftarRegistrasi from './ambulance/daftar-registrasi/index.js'
import ambulanceRincian from './ambulance/rincian/index.js'
import bendaharaPenerimaanKas from './bendahara-penerimaan/buku-kas/index.js'
import bendaharaPenerimaanklaim from './bendahara-penerimaan/info-klaim/index.js'
import bendaharaPenerimaanKasir from './bendahara-penerimaan/penerimaan-kasir/index.js'
import bendaharaPengeluaran from './bendahara-pengeluaran/index.js'
import PPI from './ppi/bundle-hais/index.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  aset({ token: data.token })
  bed({ token: data.token })
  CSSD({ token: data.token })
  EOffice({ token: data.token })
  IPSRS({ token: data.token })
  remunerasi({ token: data.token })
  logistikDistribusi({ token: data.token })
  logistikStokOpname({ token: data.token })
  logistikStokRuangan({ token: data.token })
  payroll({ token: data.token })
  pelatihan({ token: data.token })
  perencanaan({ token: data.token })
  sanitasi({ token: data.token })
  SDM({ token: data.token })
  sysadminHarga({ token: data.token })
  sysadminUser({ token: data.token })
  sysadminMap({ token: data.token })
  sysadminSetting({ token: data.token })
  akuntansi({ token: data.token })
  
  ambulanceDaftarOrder({ token: data.token })
  ambulanceDaftarRegistrasi({ token: data.token })
  ambulanceRincian({ token: data.token })
  bendaharaPenerimaanKas({ token: data.token })
  bendaharaPenerimaanklaim({ token: data.token })
  bendaharaPenerimaanKasir({ token: data.token })
  PPI({ token: data.token })
  bendaharaPengeluaran({ token: data.token })
}
