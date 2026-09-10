import { generateToken } from '../../../src/api.js'
// import getComboAddress from './get-combo-address.js'
// import getComboRegistrasi from './get-combo-registrasi.js'
// import getComboKecamatan from './get-combo-kecamatan.js'
// import getComboDesakelurahan from './get-combo-desakelurahan.js'
// import savePasienFix from './save-pasien-fix.js'
import GetListMenu from './get-list-menu.js'
import StoreNotif from './store-notif.js'
import GetJumlahLoket from './get-jumlah-loket.js'
import SaveAntrianBaru from './save-antrian-baru.js'
import CetakAntrian from './cetak-antrian.js'
import GetPasienLama from './get-data-pasien-lama.js'
import GetJadwalPoli from './get-jadwal-poli.js'
import GetJadwalDokter from './get-jadwal-dokter.js'
import GetDoubleRegis from './get-double-regis.js'
import SaveAntrianLama from './save-antrian-lama.js'
import SaveAdministrasi from './save-administrasi.js'
import GetSettingPrinter from './get-setting-printer.js'
import GetDetailReservasi from './get-detail-reservasi.js'
import GetDashboardRegistrasi from './get-dashboard-registrasi.js'
import GetDropdownRegistrasi from './get-dropdown-registrasi.js'
import ListPasienGrid from './list-pasien-grid.js'
import GetCountDaftar from './get-count-daftar.js'
import GetPasienReservasi from './get-pasien-reservasi.js'
import ListDropdownRegistrasi from './list-dropdown-registrasi.js'
import GetUserNIK from './get-user-by-nik.js'
import GetUserNoka from './get-user-by-noka.js'
import SavePasienBaru from './save-pasien-baru.js'
import PasienRegistrasiRev from './pasien-registrasi-rev.js'
import ListRuangan from './get-list-ruangan.js'
import GetKelompokPasien from './get-kelompok-pasien.js'
import GetAsalRujukan from './get-asal-rujukan.js'
import GetPenjaminKelompok from './get-penjamin-by-kelompok.js'
import GetDokter from './get-dokter.js'
import GetKelasRuangan from './get-kelas-ruangan.js'
import SaveRegistrasi from './save-registrasi.js'
import GetPemakaianAsuransi from './get-pemakaian-asuransi.js'
import GetSubSpesialis from './get-sub-spesialis.js'
import GetRujukanPeserta from './get-rujukan-peserta.js'
import GetPPK from './get-ppk.js'
import GetSKDP from './get-skdp.js'
import GetDiagnosa from './get-diagnosa.js'
import SavePemakaianAsuransi from './save-pemakaian-asuransi.js'
import GetPasienLamaReg from './get-pasien-lama.js'
import CetakSEP from './cetak-sep.js'
import CetakBuktiPendaftaran from './cetak-bukti-pendaftaran.js'
import CetakLabelPasien from './cetak-label-pasien.js'
import CetakIdentitasPasien from './cetak-identitas-pasien.js'
import SuratRegisRanap from './surat-regis-ranap.js'
import GetIsBaby from './get-is-baby.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  // getComboAddress({ token: data.token })
  // getComboRegistrasi({ token: data.token })
  // getComboKecamatan({ token: data.token })
  // getComboDesakelurahan({ token: data.token })
  // savePasienFix({ token: data.token })



        // GetListMenu({ token: data.token })
        // StoreNotif({ token: data.token })
        // SaveAntrianBaru({ token: data.token })
        // GetJumlahLoket({ token: data.token })
        // CetakAntrian({ token: data.token })
        // GetPasienLama({ token: data.token })
        // GetJadwalPoli({ token: data.token })
        // GetJadwalDokter({ token: data.token })
        // GetDoubleRegis({ token: data.token })

        SaveAntrianLama({ token: data.token })
        // SaveAdministrasi({ token: data.token })

        // GetSettingPrinter({ token: data.token })
        // GetDetailReservasi({ token: data.token })
        // GetDashboardRegistrasi({ token: data.token })
        // GetDropdownRegistrasi({ token: data.token })
        // ListPasienGrid({ token: data.token })
        // GetCountDaftar({ token: data.token })
        // GetPasienReservasi({ token: data.token })
        // ListDropdownRegistrasi({ token: data.token })
        // GetUserNIK({ token: data.token })
        // GetUserNoka({ token: data.token })

        SavePasienBaru({ token: data.token })

        // PasienRegistrasiRev({ token: data.token })
        // ListRuangan({ token: data.token })
        // GetKelompokPasien({ token: data.token })
        // GetAsalRujukan({ token: data.token })
        // GetPenjaminKelompok({ token: data.token })
        // GetDokter({ token: data.token })
        // GetKelasRuangan({ token: data.token })

        SaveRegistrasi({ token: data.token })

        // GetPemakaianAsuransi({ token: data.token })
        // GetSubSpesialis({ token: data.token })
        // GetRujukanPeserta({ token: data.token })
        // GetPPK({ token: data.token })
        // GetSKDP({ token: data.token })
        // GetDiagnosa({ token: data.token })
        // SavePemakaianAsuransi({ token: data.token })
        // GetPasienLamaReg({ token: data.token })
        // CetakSEP({ token: data.token })
        // CetakBuktiPendaftaran({ token: data.token })
        // CetakLabelPasien({ token: data.token })
        // CetakIdentitasPasien({ token: data.token })
        // SuratRegisRanap({ token: data.token })
        
        // GetIsBaby({ token: data.token })
}
