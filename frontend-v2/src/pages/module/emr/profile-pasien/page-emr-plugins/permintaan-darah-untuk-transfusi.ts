export function dataRS(): any {
    return [
        {
            "title": "1. Identitas Rumah Sakit",
            "value": [
                {
                    "subTitle": "Rumah Sakit",
                    "type": "textBox",
                    "model": "namaRumahSakit",
                },
                {
                    "subTitle": "Bagian",
                    "type": "textBox",
                    "model": "namaBagian",
                },
                {
                    "subTitle": "Ruangan",
                    "type": "textBox",
                    "model": "namaRuangan",
                },
                {
                    "subTitle": "Dokter Yang Meminta",
                    "type": "textBox",
                    "model": "namaDokter",
                },
                {
                    "subTitle": "Diagnosa sementara",
                    "type": "textBox",
                    "model": "diagnosa",
                },
                {
                    "subTitle": "Indikasi Tranfusi",
                    "type": "textBox",
                    "model": "indikasi",
                },
                {
                    "subTitle": "HB",
                    "type": "textBox",
                    "model": "HB",
                },
            ]
        },
    ]
}
export function dataPasien(): any {
    return [
        {
            "title": "2. Identitas Pasien",
            "value": [
                {
                    "subTitle": "Nama Penderita",
                    "type": "textBox",
                    "model": "namaPasien",
                },
                {
                    "subTitle": "Umur",
                    "type": "textBox",
                    "model": "umurPasien",
                },
                {
                    "subTitle": "Jenis Kelamin",
                    "type": "textBox",
                    "model": "jenisKelaminPasien",
                },
                {
                    "subTitle": "Nomor Registrasi",
                    "type": "textBox",
                    "model": "norm",
                },
                {
                    "subTitle": "Nomor KTP",
                    "type": "textBox",
                    "model": "noidentitas",
                },
                {
                    "subTitle": "Alamat Lengkap",
                    "type": "textBox",
                    "model": "alamatPasien",
                },
               
            ]
        },
    ]
}
export function jenis(): any {
    return [
        {
            "no": "A",
            "Kriteria": "Whole Blood",
            "keterangan": [
                {
                    "model" : "golWholeBlood",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlWholeBlood",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalWholeBlood",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "B" ,
            "Kriteria": "Packed Red Cells",
            "keterangan": [
                {
                    "model" : "PackedRedCells",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlPackedRedCells",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalPackedRedCells",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "" ,
            "Kriteria": "Packed Red Cells Buffty Coad Removed (PRC-BRC)",
            "keterangan": [
                {
                    "model" : "golPRC",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlPRC",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalPRC",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "" ,
            "Kriteria": "Packed Red Cells Leukodepleted (PRC-LD",
            "keterangan": [
                {
                    "model" : "golPRCLD",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlPRCLD",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalPRCLD",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "" ,
            "Kriteria": "Packed Red Cells Pediatric Leukodepleted",
            "keterangan": [
                {
                    "model" : "golPRCLDL",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlPRCLDL",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalPRCLDL",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "C" ,
            "Kriteria": "Washed Red Cells (WE)",
            "keterangan": [
                {
                    "model" : "golWE",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlWE",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalWE",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "D" ,
            "Kriteria": "Thrombocyt concentrate (TC)",
            "keterangan": [
                {
                    "model" : "golTC",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlTC",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalTC",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "" ,
            "Kriteria": "Thrombocyt concentrate Pooling Leukodepleted (TC-Aph-LD)",
            "keterangan": [
                {
                    "model" : "golTCLD",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlTCLD",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalTCLD",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "E" ,
            "Kriteria": "Fresh Frozen Plasma (FFP)",
            "keterangan": [
                {
                    "model" : "golFFP",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlFFP",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalFFP",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "F" ,
            "Kriteria": "Cryoprecipitate",
            "keterangan": [
                {
                    "model" : "golCR",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlCR",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalCR",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "G" ,
            "Kriteria": "Buffy Coat",
            "keterangan": [
                {
                    "model" : "golBC",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlBC",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalBC",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "H" ,
            "Kriteria": "Liquid Plasma",
            "keterangan": [
                {
                    "model" : "golLP",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlLP",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalLP",
                    "type"  : "textBox"
                }
            ],             
        },  
        {
            "no": "I" ,
            "Kriteria": "Lain Lain",
            "keterangan": [
                {
                    "model" : "golLL",
                    "type"  : "textBox"
                }
            ],
            "pilihan": [
                {
                    "model" : "jmlLL",
                    "type"  : "textBox"
                }
            ],   
            "waktu": [
                {
                    "model" : "tanggalLL",
                    "type"  : "textBox"
                }
            ],             
        },  
    ]
}
export function lainnya(): any {
    return [
        {
            "title": "4. Kebutuhan darah",
            "value": [
                {
                    "subTitle": "Biasa (dilakukan cross match, selama 60 menit)",
                    "type": "checkBox",
                    "model": "Kebutuhandarah_",
                },
                {
                    "subTitle": "Apheresis (proses pengerjaan 4 - 6  jam setelah ada donor)",
                    "type": "checkBox",
                    "model": "Kebutuhandarah_",
                },
                {
                    "subTitle": "Washed Red Cells (proses pengerjaan kurang atau lebih dari 4 jam)",
                    "type": "checkBox",
                    "model": "Kebutuhandarah_",
                },
                
            ]
        }, 
        {
            "title": "5. Riwayat transfusi darah sebelumnya (kapan, berapa banyak, jenis komponen darah dan reaksi transfusi darah yang terjadi) :",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketRiwayatTransfusi",
                },
           
                
            ]
        }, 
        {
            "title": "6. Riwayat kehamilan sebelumnya (abortus, prematur, kematian bayi, kelainan congenital, icterus, anemia) :",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketRiwayatHamil",
                },
           
                
            ]
        }, 
        {
            "title": "7. Isian data pasien harus dilengkapi",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketIsianData",
                },
           
                
            ]
        }, 
        {
            "title": "8. Keterangan lain :",
            "value": [
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketLainnya",
                },
           
                
            ]
        }, 
    ]
}