export function TandaVital(): any {
    return [
        {
            "title": "Tanda-tanda Vital",
            "type": "textBox",
            "value": [
                {
                    "subTitle": "NBP",
                    "model": "tekananDarah",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "RR",
                    "model": "pernapasan",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "HR",
                    "model": "nadi",
                    "satuan": "x/menit"
                },
                
                {
                    "subTitle": "Saturasi",
                    "model": "SPO2",
                    "satuan": ""
                },
            ]
        },
    ]
}
export function kesadaran(): any {
    return [
        {
            "title": "GSC Score",
            "type": "textBox",
            "value": [
                {
                    "subTitle": "E",
                    "model": "E",
                    "satuan": ""
                },
                {
                    "subTitle": "M",
                    "model": "M",
                    "satuan": ""
                },
                {
                    "subTitle": "V",
                    "model": "V",
                    "satuan": ""
                },
                
                {
                    "subTitle": "Total",
                    "model": "total",
                    "satuan": ""
                },
            ]
        },
        {
            "title": "",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Compose Mentis",
                    "model": "ComposeMentis",
                },
                {
                    "subTitle": "Somnolen",
                    "model": "Somnolen",
                },
                {
                    "subTitle": "Soporkoma",
                    "model": "Soporkoma",
                },
                {
                    "subTitle": "Koma",
                    "model": "Koma",
                },
            ]
        },
        {
            "title": "Antropometri",
            "type": "textBox",
            "value": [
                {
                    "subTitle": "Tinggi Badan",
                    "model": "tinggiBadan",
                    "satuan": "cm"
                },
                {
                    "subTitle": "Berat Badan",
                    "model": "beratBadan",
                    "satuan": "kg"
                },
          
            ]
        },
        {
            "title": "Allen Test (radial kanan)",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Positif (jika <= 7 detik)",
                    "model": "allentest_",
                },
                {
                    "subTitle": "Negatif (jika > 7)",
                    "model": "allentest_",
                },
          
            ]
        },



    ]
}
export function riwayatKesehatan(): any {
    return [
        {
            "title": "Riwayat Alergi",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Alergi Seafood",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "Obat",
                    "type": "checkBox",
                    "model": "riwayatAlergi_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketRiwayat",
                },
                
            ]
        }, 
        {
            "title": "Nyeri",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatNyeri_",
                },
                {
                    "subTitle": "Alergi Seafood",
                    "type": "checkBox",
                    "model": "riwayatNyeri_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketRiwayatNyeri_",
                },
                
            ]
        }, 
        {
            "title": "Lokasi Nyeri",
            "value": [
    
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketLokasiNyeri_",
                },
            ]
        }, 
        {
            "title": "Resiko Jatuh",
            "value": [
                {
                    "subTitle": "Skor",
                    "type": "textBox",
                    "model": "ketSkor_",
                },
                {
                    "subTitle": "Metoda",
                    "type": "textBox",
                    "model": "ketMetoda_",
                },
            ]
        }, 
        {
            "title": "Tingkat Resiko",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Ringan",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Sedang",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Berat",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },     
            ]
        }, 
        {
            "title": "Status Psikologis",
            "value": [
                {
                    "subTitle": "Tenang",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Gelisah",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Marah",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },
                {
                    "subTitle": "Ketakutan",
                    "type": "checkBox",
                    "model": "statusPsikologis_",
                },     
            ]
        }, 
        {
            "title": "Riwayat Penyakit",
            "value": [
                {
                    "subTitle": "Melena",
                    "type": "checkBox",
                    "model": "riwayatPenyakit_",
                },
                {
                    "subTitle": "Hemaptoe",
                    "type": "checkBox",
                    "model": "riwayatPenyakit_",
                },
                {
                    "subTitle": "Stroke",
                    "type": "checkBox",
                    "model": "riwayatPenyakit_",
                },
                {
                    "subTitle": "Gangguan Fungsi Ginjal",
                    "type": "checkBox",
                    "model": "riwayatPenyakit_",
                },     
            ]
        }, 
        {
            "title": "Faktor Risiko PJK",
            "value": [
                {
                    "subTitle": "Diabetes",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Merokok",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Kolestrol",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },
                {
                    "subTitle": "Kurang Olahraga",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },     
                {
                    "subTitle": "Riwayat Keluarga",
                    "type": "checkBox",
                    "model": "riwayatResiko_",
                },     
            ]
        }, 
        {
            "title": "Riwayat Kehamilan",
            "value": [
                {
                    "subTitle": "Tidak Hamil",
                    "type": "checkBox",
                    "model": "riwayatKehamilan_",
                },
                {
                    "subTitle": "Hamil, Usia Kehamilan",
                    "type": "checkBox",
                    "model": "riwayatKehamilan_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketRiwayatHamil_",
                },
                
            ]
        }, 
        {
            "title": "",
            "value": [
                {
                    "subTitle": "G",
                    "type": "textBox",
                    "model": "ketG_",
                },
                {
                    "subTitle": "P",
                    "type": "textBox",
                    "model": "ketP_",
                },
                {
                    "subTitle": "A",
                    "type": "textBox",
                    "model": "ketA_",
                },
            ]
        }, 
    ]
}
export function riwayatTindakan(): any {
    return [
        {
            "title": "Angiografi koroner",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },
                {
                    "subTitle": "Ada, tanggal",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "tanggalTindakan",
                },
                
            ]
        }, 
        {
            "title": "PCI",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },
                {
                    "subTitle": "Ada, tanggal",
                    "type": "checkBox",
                    "model": "riwayatTindakan_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "tanggalPCI",
                },
                
            ]
        }, 
    ]
}
export function lokasiStent(): any {
    return [
        {
            "title": "Lokasi dan Nama Stent (Diisi Jika Pernah PCI) ",
            "type": "textBox",
            "value": [
                {
                    "subTitle": "1. Lokasi",
                    "model": "lokasi_1",
                },
                {
                    "subTitle": "Stent",
                    "model": "stent_1",
                },
                {
                    "subTitle": "2. Lokasi",
                    "model": "lokasi_2",
                }, 
                {
                    "subTitle": "Stent",
                    "model": "stent_2",
                },
                {
                    "subTitle": "3. Lokasi",
                    "model": "lokasi_3",
                },
                {
                    "subTitle": "Stent",
                    "model": "stent_3",
                },
                {
                    "subTitle": "4. Lokasi",
                    "model": "lokasi_4",
                }, 
                {
                    "subTitle": "Stent",
                    "model": "stent_4",
                },
                {
                    "subTitle": "5. Lokasi",
                    "model": "lokasi_5",
                },
                {
                    "subTitle": "Stent",
                    "model": "stent_5",
                },
                {
                    "subTitle": "6. Lokasi",
                    "model": "lokasi_6",
                }, 
                {
                    "subTitle": "Stent",
                    "model": "stent_6",
                },
            ]
        },
    ]
}
export function riwayatCABG(): any {
    return[
        {
            "title": "Riwayat CABG",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatCABG_",
                },
                {
                    "subTitle": "Ada, tanggal",
                    "type": "checkBox",
                    "model": "riwayatCABG_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "tanggalCABG",
                },
                
            ]
        },   
    ]
}
export function riwayatEP(): any {
    return [
        {
            "title": "Riwayat EP study",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatEP_",
                },
                {
                    "subTitle": "Ada, tanggal",
                    "type": "checkBox",
                    "model": "riwayatEP_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "tanggalEP",
                },
                
            ]
        }, 
    ]
}
export function pemasanganAlatJantung(): any {
    return [
        {
            "title": "Pemasangan Alat Jantung",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "pemasanganAlatJantung_",
                },
                {
                    "subTitle": "PPM",
                    "type": "checkBox",
                    "model": "pemasanganAlatJantung_",
                },
                {
                    "subTitle": "IGC",
                    "type": "checkBox",
                    "model": "pemasanganAlatJantung_",
                },
                {
                    "subTitle": "CRT/CRTD",
                    "type": "checkBox",
                    "model": "pemasanganAlatJantung_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketpemasanganAlat",
                },
                
            ]
        },   
    ]
}
export function riwayatPengobatan(): any {
    return [
        {
            "title": "Anti Platelet",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "riwayatPengobatan_",
                },
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "riwayatPengobatan_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketriwayatPlatelet",
                },
                
            ]
        },  
        {
            "title": "Anti Koagulan",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "antiKoagulan_",
                },
                {
                    "subTitle": "Low Molecular Weight Heparin (LMWH)",
                    "type": "checkBox",
                    "model": "antiKoagulan_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketriwayatPlatelet",
                },
                
            ]
        }, 
    ]
}
export function Pilihan(): any {
    return[
        {
            "title": "Warfarin",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "waftarin_",
                },
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "waftarin_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketWaftarin",
                },
                {
                    "subTitle": "Stop, Tanggal",
                    "type": "textBox2",
                    "model": "tanggalWastarin",
                },
                
            ]
        },
        {
            "title": "Metformin",
            "value": [
                {
                    "subTitle": "Tidak Ada",
                    "type": "checkBox",
                    "model": "Metformin_",
                },
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "Metformin_",
                },
              
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketMetformin_",
                },
                {
                    "subTitle": "Stop Tanggal",
                    "type": "textBox2",
                    "model": "tanggalMetamorfin",
                },
                
            ]
        },
    ]
}
export function HB(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "HB",
                    "type": "checkBox",
                    "model": "HB_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketHB_",
                },
                {
                    "subTitle": "Leukosit",
                    "type": "textBox",
                    "model": "ketLeukosit_",
                },
                {
                    "subTitle": "HT",
                    "type": "textBox",
                    "model": "ketHT_",
                },
                {
                    "subTitle": "Trombosit",
                    "type": "textBox",
                    "model": "ketHT_",
                },       
                
            ]
        },
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Ureum",
                    "type": "checkBox",
                    "model": "Ureum_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketUreum_",
                },
                {
                    "subTitle": "Kreatinin",
                    "type": "textBox",
                    "model": "ketKreatinin",
                },
                
            ]
        },
        {
            "title": "",
            "value": [
                {
                    "subTitle": "GDS",
                    "type": "checkBox",
                    "model": "GDS_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketGDS",
                },
                {
                    "subTitle": "PT",
                    "type": "checkBox",
                    "model": "PT_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketPT_",
                },
                {
                    "subTitle": "APTT",
                    "type": "checkBox",
                    "model": "APTT_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketAPTT_",
                },

                
            ]
        },
        {
            "title": "",
            "value": [
                {
                    "subTitle": "HbsAg",
                    "type": "checkBox",
                    "model": "HbsAg_",
                },
                {
                    "subTitle": "Keterangan",
                    "type": "textBox",
                    "model": "ketHBsAg_",
                },
                
                
            ]
        },
    ]
}