import { generateToken } from '../../../src/api.js'
// import getComboAddress from './get-combo-address.js'
// import getComboRegistrasi from './get-combo-registrasi.js'
// import getComboKecamatan from './get-combo-kecamatan.js'
// import getComboDesakelurahan from './get-combo-desakelurahan.js'
// import savePasienFix from './save-pasien-fix.js'
import DashboardRegistrasi from './dashboard-registrasi.js'
import DashboardKonsul from './dashboard-konsul.js'
import DashboardReservasi from './dashboard-reservasi.js'
import DropdownRawatJalan from './dropdown-rawat-jalan.js'
import DropdownPegawaiRegis from './dropdown-pegawai.js'
import ChangeDPJP from './change-dpjp.js'
import HeaderPasien from './header-pasien.js'
import GetTotalBilling from './get-total-billing.js'
import GetRiwayatPelayanan from './get-riwayat-pelayanan.js'
import GetVitalSign from './get-vital-sign.js'
import GetEMR from './get-emr.js'
import GetEMRTerakhir from './get-emr-terakhir.js'
import SaveEMRTemplate from './save-emr-template.js'
import SaveEMR from './save-emr.js'
import GetCPPT from './get-cppt.js'
import GetDropdownDiagnosa from './dropdown-diagnosa.js'
import GetAutoFill from './get-auto-fill.js'
import GetRiwayatKunjungan from './get-riwayat-kunjungan.js'
import GetListTindakan from './get-list-tindakan.js'
import GetStatusClose from './get-status-close.js'
import GetKomponenHarga from './get-komponen-harga.js'
import SaveTindakanPoli from './save-tindakan.js'
import GetListPaket from './get-list-paket.js'
import GetTindakanOrder from './get-tindakan-order.js'
import SaveOrder from './save-order.js'
import GetRiwayatOrder from './get-riwayat-order.js'
import GetDetailOrder from './get-detail-order.js'
import GetRiwayatKontrol from './get-riwayat-kontrol.js'
import GetListRuanganRajal from './list-ruangan-rajal.js'
import GetJadwalDokter from './get-jadwal-dokter.js'
import SaveSuratKontrol from './save-surat-kontrol.js'
import CreateRiwayatKontrol from './create-riwayat-kontrol.js'
import GetAutoFillBedah from './auto-fill-bedah.js'
import SaveOrderBedah from './save-order-bedah.js'
import GetRiwayatOrderBedah from './riwayat-order-bedah.js'
import GetDetailOrderBedah from './detail-order-bedah.js'
import DropdownInputResep from './dropdown-input-resep.js'
import GetOrderResepHariIni from './order-resep-hariini.js'
import GetDetailObat from './get-detail-obat.js'
import SaveOrderObat from './simpan-order-obat.js'
import GetRiwayatOrderResep from './riwayat-order-resep.js'
import HapusOrderResep from './hapus-order-resep.js'
import GetTransferPasien from './get-transfer-pasien.js'
import GetListKelas from './get-list-kelas.js'
import GetMasterJadwal from './get-master-jadwal.js'
import SavePasienKonsul from './simpan-pasien-konsul.js'
import SaveBatalPasienKonsul from './batal-transfer-pasien.js'


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

  
        DashboardRegistrasi({ token: data.token })
        DashboardKonsul({ token: data.token })
        DropdownRawatJalan({ token: data.token })
        DashboardReservasi({ token: data.token })
        DropdownPegawaiRegis({ token: data.token })
        ChangeDPJP({ token: data.token })
        HeaderPasien({ token: data.token })
        GetTotalBilling({ token: data.token })
        GetRiwayatPelayanan({ token: data.token })
        GetVitalSign({ token: data.token })
        GetEMR({ token: data.token })
        GetEMRTerakhir({ token: data.token })
        SaveEMRTemplate({ token: data.token })
        SaveEMR({ token: data.token })
        GetCPPT({ token: data.token })
        GetDropdownDiagnosa({ token: data.token })
        GetAutoFill({ token: data.token })
        GetRiwayatKunjungan({ token: data.token })
        GetListTindakan({ token: data.token })
        GetStatusClose({ token: data.token })
        GetKomponenHarga({ token: data.token })
        SaveTindakanPoli({ token: data.token })
        GetListPaket({ token: data.token })
        GetTindakanOrder({ token: data.token })
        SaveOrder({ token: data.token })
        GetRiwayatOrder({ token: data.token })
        GetDetailOrder({ token: data.token })
        GetRiwayatKontrol({ token: data.token })
        GetListRuanganRajal({ token: data.token })
        GetJadwalDokter({ token: data.token })
        // SaveSuratKontrol({ token: data.token })
        // CreateRiwayatKontrol({ token: data.token })
        GetAutoFillBedah({ token: data.token })
        SaveOrderBedah({ token: data.token })
        GetRiwayatOrderBedah({ token: data.token })
        GetDetailOrderBedah({ token: data.token })
        DropdownInputResep({ token: data.token })
        GetOrderResepHariIni({ token: data.token })
        GetDetailObat({ token: data.token })
        SaveOrderObat({ token: data.token })
        GetRiwayatOrderResep({ token: data.token })
        HapusOrderResep({ token: data.token })
        GetTransferPasien({ token: data.token })
        GetListKelas({ token: data.token })
        GetMasterJadwal({ token: data.token })
        SavePasienKonsul({ token: data.token })
        SaveBatalPasienKonsul({ token: data.token })
}
