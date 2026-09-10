export function signIn(): any {
    return [
        {
            "title": "1. Identitas pasien dan jenis tindakan benar. (gelang identitas terpasang)",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "gelangPasien_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                
                
            ]
        },
        {
            "title": "2. Screening Vital Sign",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "skrining_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "skrining_",
                },
                
                
            ]
        },
        {
            "title": "3. Informed Consent sudah di lakukan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "informedConsent_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "informedConsent_",
                },
                
                
            ]
        },
        {
            "title": "4. Melakukan identifikasi pada area pungsi",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "pungsi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "pungsi_",
                },
                
                
            ]
        },
        {
            "title": "5. Pasien sedang hamil (wanita)",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "hamil_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "hamil_",
                },
                
                
            ]
        },
        {
            "title": "6. Pasien memiliki riwayat alergi",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                
                
            ]
        },
        {
            "title": "7. Pemeriksaan Laboratorium sudah di lakukan (cek hasil tidak normal)",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "riwayatLab_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatLab_",
                },
                
                
            ]
        },
        {
            "title": "8. Pemeriksaan Laboratorium sudah di lakukan (cek hasil tidak normal)",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "riwayatLab_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatLab_",
                },
                
            ]
        },
        {
            "title": "9. Pemberian Antikoagulan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "Antikoagulan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "Antikoagulan_",
                },
                
            ]
        },
        {
            "title": "10. IV Line Lancar ",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "line_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "line_",
                },
                
            ]
        },

    ]
}

export function timeOut(): any {
    return [
        {
            "title": "1. Identitas pasien benar. (nama lengkap, tanggal lahir)",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "identitasLengkap_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "identitasLengkap_",
                },    
            ]
        }, 
        {
            "title": "2. Prosedure tindakan dan akses yang akan digunakan benar",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "prosedurTindakan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "prosedurTindakan_",
                },    
            ]
        }, 
        {
            "title": "3. Pasien memiliki riwayat alergi",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },    
            ]
        }, 
        {
            "title": "4. Riwayat tindakan sebelumnya",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },    
            ]
        }, 
        {
            "title": "5. Pasien memiliki penyakit diabetes",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "diabetes_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "diabetes_",
                },    
            ]
        }, 
        {
            "title": "6. Terdapat hasil Laboratorium Kritis",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "hasilLab_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "hasilLab_",
                },    
            ]
        }, 
        {
            "title": "7. Diberikan antibiotik propilaksis",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "antibiotik_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "antibiotik_",
                },    
            ]
        }, 
        {
            "title": "8. Mengkonsumsi obat antikoagulan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "antikoagulanTO_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "antikoagulanTO_",
                },    
            ]
        }, 
        {
            "title": "9. Peralatan yang akan digunakan berfungsi dengan baik sebelum tindakan dilakukan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "peralatanTindakan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "peralatanTindakan_",
                },    
            ]
        }, 
    ]
}

export function signOut(): any {
    return [
        {
            "title": "1. Dokter Operator melakukan konfirmasi tindakan selesai",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "konfirmasiTindakan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "konfirmasiTindakan_",
                },    
            ]
        }, 
        {
            "title": "2. Terjadinya kerusakan alat pada saat berlangsungnya tindakan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "kerusakanTindakan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "kerusakanTindakan_",
                },    
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "KetkerusakanTindakan_",
                },    
            ]
        }, 
        {
            "title": "3. Terjadi Perubahan Rencana pada prosedur tindakan yang telah di dokumentasikan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "perubahanRencana_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "perubahanRencana_",
                },    
            ]
        }, 
        {
            "title": "4. Semua alat implant sudah dicatat",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "implant_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "implant_",
                },    
            ]
        }, 
        {
            "title": "5. Terjadi insiden yang tidak diharapkan ",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "insidenTidakdiHarapkan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "insidenTidakdiHarapkan_",
                },    
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketInsiden_",
                },    
            ]
        }, 
        {
            "title": "6. Memerlukan insiden report",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "insidenReport_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "insidenReport_",
                },    
            ]
        }, 
        {
            "title": "7. Jumlah zat kontras, cairan masuk dan perdarahan sudah dicatat",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "jumlahZat_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "jumlahZat_",
                },    
            ]
        }, 
        {
            "title": "8. Jumlah instrument sudah dihitung",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "instrumen_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "instrumen_",
                },    
            ]
        }, 
    ]
}