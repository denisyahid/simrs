export function verifikasi(): any {
    return [
        {
            "title": "Identitas dan gelang pasien",
            "model": "verif_",
            "value": {
                "code": "iden",
                "description": "Identitas dan gelang pasien",
            }
        },
        {
            "title": "Informed consent",
            "model": "verif_",
            "value": {
                "code": "ic",
                "description": "Informed consent",
            }
        },
        {
            "title": "Dokter bedah",
            "model": "verif_",
            "value": {
                "code": "DB",
                "description": "Dokter bedah",
            }
        },
        {
            "title": "Dokter anestesi",
            "model": "verif_",
            "value": {
                "code": "DA",
                "description": "Dokter anestesi",
            }
        },
        {
            "title": "Pemberian tanda di lokasi operasi",
            "model": "verif_",
            "value": {
                "code": "PTO",
                "description": "Pemberian tanda di lokasi operasi",
            }
        },
        {
            "title": "Ya",
            "model": "verif_",
            "value": {
                "code": "Y",
                "description": "Ya",
            }
        },
        {
            "title": "Tidak",
            "model": "verif_",
            "value": {
                "code": "N",
                "description": "Tidak",
            }
        },
    ]
}

export function kelengkapan(): any {
    return [
        {
            "title": "Mesin anestesi",
            "model": "kelengkapan_",
            "value": {
                "code": "MA",
                "description": "Mesin anestesi",
            }
        }, 
        {
            "title": "Obat-obatan",
            "model": "kelengkapan_",
            "value": {
                "code": "OO",
                "description": "Obat-obatan",
            }
        }, 
        {
            "title": "Pulsa oximeter",
            "model": "kelengkapan_",
            "value": {
                "code": "PO",
                "description": "Pulsa oximeter",
            }
        }, 
        {
            "title": "Laboratorium",
            "model": "kelengkapan_",
            "value": {
                "code": "LB",
                "description": "Laboratorium",
            }
        }, 
        {
            "title": "OIV Line",
            "model": "kelengkapan_",
            "value": {
                "code": "",
                "description": "OIV Line",
            }
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
export function resiko(): any{
    return [
        {
            "title": "Riwayat Alergi",
            "value": [
                {
                    "subTitle": "Ya, Jelaskan",
                    "type": "checkBox",
                    "model": "alergi_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "Ketalergi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "alergi_",
                },
            ]
        },
        {
            "title": "Resiko Aspirasi atau Gangguan Pernafasan",
            "value": [
                {
                    "subTitle": "Ya, Jelaskan",
                    "type": "checkBox",
                    "model": "resikoAspirasi",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketresikoAspirasi",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "resikoAspirasi",
                },
            ]
        },
        {
            "title": "Resiko Perdarahan (Kehilangan darah > 500ml (7ml/kg pada anak) ",
            "value": [
                {
                    "subTitle": "Ya, dengan dua IV line atau CVC",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },
            ]
        },
        {
            "title": "Rencana Anestesi",
            "value": [
                {
                    "subTitle": "Umum",
                    "type": "checkBox",
                    "model": "rencanaAnestesi_",
                },
                {
                    "subTitle": "Spinal",
                    "type": "checkBox",
                    "model": "rencanaAnestesi_",
                },
                {
                    "subTitle": "Blok",
                    "type": "checkBox",
                    "model": "rencanaAnestesi_",
                },
                {
                    "subTitle": "Lokal",
                    "type": "checkBox",
                    "model": "rencanaAnestesi_",
                },
            ]
        },
    ]
}
export function time(): any {
    return [
        {
            "title": "Kelengkapan Tim dan Fasilitas Operasi",
            "value": [
                {
                    "subTitle": "Tidak lengkap, Alasan",
                    "type": "checkBox",
                    "model": "kelengkapanTim_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketkelengkapanTim_",
                },
                {
                    "subTitle": "Lengkap",
                    "type": "checkBox",
                    "model": "kelengkapanTim_",
                },
            ]
        },
        {
            "title": "Pemeriksaan Kelengkapan Peralatan Operasi",
            "value": [
                {
                    "subTitle": "Intrumen",
                    "type": "checkBox",
                    "model": "pemeriksanaan_",
                },
                {
                    "subTitle": "Kasa",
                    "type": "checkBox",
                    "model": "pemeriksanaan_",
                },
                {
                    "subTitle": "Jarum",
                    "type": "checkBox",
                    "model": "pemeriksanaan_",
                },
            ]
        },
        {
            "title": "Baca Secara Verbal",
            "value": [
                {
                    "subTitle": "Tanggal Tindakan",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Identitas Pasien",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Nama Tindakan",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Lokasi Tindakan",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Penggunaan Implan",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "bahasa_",
                },
            ]
        },
        {
            "title": "Ketersediaan Implan/Protesa",
            "value": [
                {
                    "subTitle": "Ada, Sebutkan",
                    "type": "checkBox",
                    "model": "ketersediaan_",
                },
            
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketketersediaan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "ketersediaan_",
                },
            ]
        },
        {
            "title": "Premidikasi",
            "value": [
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "Premidikasi_",
                },
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "Premidikasi_",
                },
            ]
        },
    ]
}
export function foto(): any {
    return [
        {
            "title": "Foto pemeriksaan Radiologi yang Diperlukan",
            "value": [
                {
                    "subTitle": "Dipasang",
                    "type": "checkBox",
                    "model": "foto_",
                },
                {
                    "subTitle": "Tidak dipasang",
                    "type": "checkBox",
                    "model": "foto_",
                },
            ]
        },  
    ]
}
export function signOut(): any {
    return [
        {
            "title": "Baca secara verbal",
            "value": [
                {
                    "subTitle": "Nama Tindakan",
                    "type": "checkBox",
                    "model": "verbal_",
                },
               
            ]
        }, 
        {
            "title": "Periksa Kelengkapan Sebelum Luka Operasi Ditutup",
            "value": [
                {
                    "subTitle": "Instrumen",
                    "type": "checkBox",
                    "model": "periksaLengkap_",
                },
                {
                    "subTitle": "Kasa",
                    "type": "checkBox",
                    "model": "periksaLengkap_",
                },
                {
                    "subTitle": "Jarum",
                    "type": "checkBox",
                    "model": "periksaLengkap_",
                },
                {
                    "subTitle": "Implan",
                    "type": "checkBox",
                    "model": "periksaLengkap_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "KetperiksaLengkap_",
                },
            ]
        }, 
        
    ]
}
export function bahanPemeriksaan(): any {
    return [
        {
            "title": "Preparat",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "bahanPemeriksaan_",
                },
                {
                    "subTitle": "PA",
                    "type": "checkBox",
                    "model": "bahanPemeriksaan_",
                },
                {
                    "subTitle": "Kultur",
                    "type": "checkBox",
                    "model": "bahanPemeriksaan_",
                },
                {
                    "subTitle": "Sitologi",
                    "type": "checkBox",
                    "model": "bahanPemeriksaan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "bahanPemeriksaan_",
                },
               
            ]
        }, 
        {
            "title": "Formulir permintaan pemeriksaan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "formulirPermintaan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "formulirPermintaan_",
                },
            ]
            },
            {
                "title": "Telah dilengkapi identitas pasien",
                "value": [
                    {
                        "subTitle": "Ya",
                        "type": "checkBox",
                        "model": "lengkapi_",
                    },
                    {
                        "subTitle": "Tidak",
                        "type": "checkBox",
                        "model": "lengkapi_",
                    },
                ]
                },
                {
                    "title": "Penjelasan oleh operator kepada keluarga pasien",
                    "value": [
                        {
                            "subTitle": "Ya",
                            "type": "checkBox",
                            "model": "penjelasan_",
                        },
                        {
                            "subTitle": "Tidak",
                            "type": "checkBox",
                            "model": "penjelasan_",
                        },
                    ]
                    },
                    {
                        "title": "Obat-obatan yang diberikan Selama Operasi",
                        "value": [
                            {
                                "subTitle": "Diberikan",
                                "type": "checkBox",
                                "model": "obat_",
                            },
                            {
                                "subTitle": "Tidak Diberikan",
                                "type": "checkBox",
                                "model": "obat_",
                            },
                        ]
                        },
            

    ]
}
export function tandaVitalSO(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kesadaran",
                    "model": "kesadaran_",
                    "type": "textbox",
                    "satuan": "C",
    
                },
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
                {
                    "subTitle": "Skala Nyeri",
                    "model": "skalaNyeri",
                    "type": "textbox",
            
                },
            ],
        },
    ]
}
export function lukaOperasi(): any {
    return[
        {
            "title": "Periksa Kembali Luka Operasi",
            "value": [
                {
                    "subTitle": "Ada Rembesan",
                    "type": "checkBox",
                    "model": "lukaOperasi_",
                },
                {
                    "subTitle": "Tidak ada rembesan",
                    "type": "checkBox",
                    "model": "lukaOperasi_",
                },
            ]
        },    
    ]
}







