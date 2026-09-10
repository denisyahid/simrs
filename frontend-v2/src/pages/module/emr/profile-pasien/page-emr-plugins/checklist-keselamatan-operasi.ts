export function konfirmasi(): any {
    return [
        {
            "title": "Identitas (gelang pasien)",
            "model": "konfirmasi_",
            "value": {
                "code": "ID",
                "description": "Identitas (gelang pasien)",
            }
        },
        {
            "title": "Surat Ijin Operasi (Bedah)",
            "model": "konfirmasi_",
            "value": {
                "code": "SOB",
                "description": "Surat Ijin Operasi (Bedah)",
            }
        },
        {
            "title": "Surat Ijin Pembiusan (Anestesi)",
            "model": "konfirmasi_",
            "value": {
                "code": "SPA",
                "description": "Surat Ijin Pembiusan (Anestesi)",
            }
        },
        {
            "title": "Form Penggunaan Kamar Bedah",
            "model": "konfirmasi_",
            "value": {
                "code": "FPK",
                "title": "Form Penggunaan Kamar Bedah",
            }
        },
       
       
    ]
}

export function tandaOperasi(): any {
    return [
        {
            "title": "Pemberian Tanda Operasi",
            "value": [
                {
                    "subTitle": "Ya, Lokasi",
                    "type": "checkBox",
                    "model": "tandaOperasi_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "kettandaOperasi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "tandaOperasi_",
                },
            ]
        },  
    ]
}
export function riwayatAlergi(): any {
    return[
        {
            "title": "Riwayat Alergi",
            "value": [
                {
                    "subTitle": "Rhinitis Alergi",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Asma Bronchiale",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Dermatitis Alergi",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Terhadap Alergen",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketriwayatAlergi_",
                },
                
            ]
        },  
        {
            "title": "Perlengkapan Anestesi",
            "value": [
                {
                    "subTitle": "IV Line",
                    "type": "checkBox",
                    "model": "perlengkapanAnastesi_",
                },
                {
                    "subTitle": "Mesin / Pump",
                    "type": "checkBox",
                    "model": "perlengkapanAnastesi_",
                },
                {
                    "subTitle": "Oxymetri",
                    "type": "checkBox",
                    "model": "perlengkapanAnastesi_",
                },
                {
                    "subTitle": "Obat-Obatan",
                    "type": "checkBox",
                    "model": "perlengkapanAnastesi_",
                },
              
                
            ]
        },  
    ]
}
export function tandaVitalSI(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Tekanan darah",
                    "model": "tekananDarah",
                    "type": "textfiled",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "Nadi",
                    "model": "nadi",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
        
                {
                    "subTitle": "Pernafasan",
                    "model": "pernapasan",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "Saturasi O2",
                    "model": "saturasi",
                    "type": "textfiled",
                    "satuan": "%"
                },
                {
                    "subTitle": "Suhu",
                    "model": "suhu",
                    "type": "textfiled",
                    "satuan": "C",
    
                },
            ],
        },
    ]
}
export function resiko(): any {
    return [
        {
            "title": "Resiko Aspirasi / Kesulitan Jalan Nafas",
            "value": [
                {
                    "subTitle": "Ya, Alat Bantu",
                    "type": "checkBox",
                    "model": "resikoAspirasi_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketAlatBantu_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "resikoAspirasi_",
                },
            ]
        },  
        {
            "title": "Riwayat Penyakit",
            "value": [
                {
                    "subTitle": "Diabetes Melitus",
                    "type": "checkBox",
                    "model": "diabetes_",
                },
                {
                    "subTitle": "Hypertensi",
                    "type": "checkBox",
                    "model": "hipertensi_",
                },
                {
                    "subTitle": "Penyakit Jantung",
                    "type": "checkBox",
                    "model": "penyakitJantung_",
                },
                {
                    "subTitle": "Lainnya",
                    "type": "checkBox",
                    "model": "lainnya_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketAlatBantu_",
                },
               
            ]
        },  
        {
            "title": "Risiko Kehilangan darah >500 ml/7 ml/kg untuk anak",
            "value": [
                {
                    "subTitle": "IV akses adekuat",
                    "type": "checkBox",
                    "model": "Aksesadekuat_",
                },
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "Aksesadekuat_Ya",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "Aksesadekuat_Tdk",
                },
                {
                    "subTitle": "Rencana Tranfusi",
                    "type": "checkBox",
                    "model": "rencanaTranfusi",
                },
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "rencanaTranfusi_Ya",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "rencanaTranfusi_Tdk",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketrencanaTranfusi_",
                },
               
            ]
        },  
    ]
}
export function implant(): any {
    return[
        {
            "title": "Terpasang Implant/ Protesa",
            "value": [
                {
                    "subTitle": "Ya, Jelaskan",
                    "type": "checkBox",
                    "model": "terpasangImplant_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketterpasangImplant_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "terpasangImplant_",
                },
            ]
        },   
        {
            "title": "Rencana pemasangan implant/Protesa",
            "value": [
                {
                    "subTitle": "Ya, Jelaskan",
                    "type": "checkBox",
                    "model": "rencanaImplant_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "rencanaImplant_",
                },
            ]
        },       
    ]
}
export function instrumen(): any {
    return [
        {
            "title": "Instrumen yang dipakai Lengkap",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "lengkapInstrumen_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "lengkapInstrumen_",
                },
            ]
        },  
        {
            "title": "Kelengkapan Alkes",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "kelengkapanAlkes_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "kelengkapanAlkes_",
                },
            ]
        },  
    ]
}
export function alkes(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kasssa",
                    "model": "kassa",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
                {
                    "subTitle": "Kacang",
                    "model": "kacang",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
                {
                    "subTitle": "Bigkassa",
                    "model": "bigkassa",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
                {
                    "subTitle": "Leus",
                    "model": "Leus",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
                {
                    "subTitle": "Depper",
                    "model": "Depper",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
                {
                    "subTitle": "Roll Kassa",
                    "model": "rollkassa",
                    "type": "textfiled",
                    "satuan": "Bh"
                },
            
            ],
        },
    ]
}