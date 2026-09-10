export function areaOperasi(): any {
    return [
        {
            "title": "Area Operasi/Tindakan",
            "detail": [
                {
                    "label": "Kanan",
                    "model": "areaOperasi_",
                },
                {
                    "label": "Kiri",
                    "model": "areaOperasi_",
                },
            ]
        }
    ]
}
export function kondisi(): any {
    return [
        {
            "title": "Status Pasien",
            "detail": [
                {
                    "label": "Ada",
                    "model": "statusPasien_",
                },
                {
                    "label": "Tunda Medis",
                    "model": "statusPasien_",
                },
                {
                    "label": "Tunda Non Medis",
                    "model": "statusPasien_",
                },
            ]
        }, 
        {
            "title": "Identifikasi",
            "detail": [
                {
                    "label": "Terpasang",
                    "model": "identifikasi_",
                },
                {
                    "label": "Tidak Terpasang",
                    "model": "identifikasi_",
                },
                
            ]
        },
        {
            "title": "Klasifikasi Operasi",
            "detail": [
                {
                    "label": "Bersih",
                    "model": "klasifikasiOperasi_",
                },
                {
                    "label": "Kotor",
                    "model": "klasifikasiOperasi_",
                },
                {
                    "label": "Bersih Terkontaminasi",
                    "model": "klasifikasiOperasi_",
                },
                
            ]
        }  
    ]
}
export function DaftarInformasi(): any {
    return [
        {
            "no": 1.,
            "Kriteria": "Status Pasien (ruangan & poliklinik)",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "statusRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "statusOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "statusRR"
                },
            ],               
        },
        {
            "no": 2.,
            "Kriteria": "Informed consent (Bedah & Anestesi)",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "ic_RI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "ic_OK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "ic_RR"
                },
            ],               
        },
        {
            "no":3.,
            "Kriteria": "Gelang Identitas Terpasang",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "gelangRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "gelangOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "gelangRR"
                },
            ],               
        },
        {
            "no":4.,
            "Kriteria": "Konsul Penyakit Dalam",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "konsulRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "gelangOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "gelangRR"
                },
            ],               
        },
        {
            "no":5.,
            "Kriteria": "Konsul Paru",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "paruRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "paruOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "paruRR"
                },
            ],               
        },
        {
            "no":6.,
            "Kriteria": "Konsul Anak",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "anakRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "anakOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "anakRR"
                },
            ],               
        },
        {
            "no":7.,
            "Kriteria": "Konsul Anestesi",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "anestesiRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "anestesiOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "anestesiRR"
                },
            ],               
        },
        {
            "no":8.,
            "Kriteria": "Konsul Kardiologi",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "kardiologiRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "kardiologiOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "kardiologiRR"
                },
            ],               
        },
        {
            "no":9.,
            "Kriteria": "Konsul Syaraf",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "syarafRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "syarafOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "syarafRR"
                },
            ],               
        },
        {
            "no":10.,
            "Kriteria": "Konsul Spesialis lain :",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "specialisRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "specialisOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "specialisRR"
                },
            ],               
        },
        {
            "no":11.,
            "Kriteria": "Golongan Darah & Darah Tersedia :",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "golonganRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "golonganOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "golonganRR"
                },
            ],               
        },
        {
            "no":12.,
            "Kriteria": "Hasil Laboratorium",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "hasilLabRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "hasilLabOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "hasilLabRR"
                },
            ],               
        },
        {
            "no":13.,
            "Kriteria": "Hasil Radiologi, USG, CT Scan, MRI",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "hasilRadRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "hasilRadOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "hasilRadRR"
                },
            ],               
        },
        {
            "no":14.,
            "Kriteria": "Hasil ECHO",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "echoRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "echoOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "echoRR"
                },
            ],               
        },
        {
            "no":15.,
            "Kriteria": "Puasa",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "puasaRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "puasaOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "puasaRR"
                },
            ],               
        },
        {
            "no":16.,
            "Kriteria": "Huknah",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "huknahRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "huknahOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "huknahRR"
                },
            ],               
        },
        {
            "no":17.,
            "Kriteria": "Kebersihan pasien",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "kebersihanRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "kebersihanOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "kebersihanRR"
                },
            ],               
        },
        {
            "no":18.,
            "Kriteria": "Area operasi dicukur sesuai kebutuhan",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "areaOperasiRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "areaOperasiOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "areaOperasiRR"
                },
            ],               
        },
        {
            "no":19.,
            "Kriteria": "Accesories telah dilepas",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "accesoriesRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "accesoriesOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "accesoriesRR"
                },
            ],               
        },
        {
            "no":20.,
            "Kriteria": "Penandaan (Mark Side)",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "penandaanRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "penandaanOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "penandaanRR"
                },
            ],               
        },
        {
            "no":21.,
            "Kriteria": "Infuse",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "infuseRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "infuseOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "infuseRR"
                },
            ],               
        },
        {
            "no":22.,
            "Kriteria": "Kateter",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "kateterRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "kateterOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "kateterRR"
                },
            ],               
        },
        {
            "no":23.,
            "Kriteria": "Alat Khusus/Implant tersedia",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "alatKhususRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "alatKhususOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "alatKhususRR"
                },
            ],               
        },
        {
            "no":24.,
            "Kriteria": "Pesanan ICU tersedia",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "pesananICURI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "pesananICUOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "pesananICURR"
                },
            ],               
        },
    ]
}

export function PascaOperasi(): any {
    return [
        {
            "no": 1.,
            "Kriteria": "Alderete Score",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "aldeRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "aldeOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "aldeRR"
                },
            ],               
        },
        {
            "no": 2.,
            "Kriteria": "Status Pasien",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "statuspasienRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "statuspasienOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "statuspasienRR"
                },
            ],               
        },
        {
            "no": 3.,
            "Kriteria": "Laporan Operasi",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "laporanRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "laporanOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "laporanRR"
                },
            ],               
        },
        {
            "no": 4.,
            "Kriteria": "Laporan Anestesi",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "lapAnestesiRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "lapAnestesiOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "lapAnestesiRR"
                },
            ],               
        },
        {
            "no": 5.,
            "Kriteria": "Resep",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "resepRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "resepOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "resepRR"
                },
            ],               
        },
        {
            "no": 6.,
            "Kriteria": "Ringkasan Pulang",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "ringkasanRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "ringkasanOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "ringkasanRR"
                },
            ],               
        },
        {
            "no": 7.,
            "Kriteria": "Form. Pemeriksaan PA",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "pemeriksaanRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "pemeriksaanOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "pemeriksaanRR"
                },
            ],               
        },
        {
            "no": 8.,
            "Kriteria": "Bahan Spesimen : Kultur, PA",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "spesimenRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "spesimenOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "spesimenRR"
                },
            ],               
        },
        {
            "no": 9.,
            "Kriteria": "Hasil Radiologi, USG, CT Scan, MRI",
            "pilihanRI": [
                {
                    "label": "",
                    "model": "hasilRadRI"
                },
            ],     
            "pilihanOK": [
                {
                    "label": "",
                    "model": "hasilRadOK"
                },
            ],          
            "pilihanRR": [
                {
                    "label": "",
                    "model": "hasilRadRR"
                },
            ],               
        },
    ]
}