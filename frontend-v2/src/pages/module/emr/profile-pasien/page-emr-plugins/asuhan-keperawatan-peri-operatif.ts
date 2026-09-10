export function Pengkajian(): any {
    return [
        {
            "title": "Tanggal",
            "value": [
                {
                    "subTitle": "",
                    "type": "datePicker",
                    "model": "tanggalDataUmum_",
                },
            ]
        },
        {
            "title": "WAKTU OPERASI | JAM",
            "value": [
                {
                    "subTitle": "Masuk ruang persiapan",
                    "type": "timePicker",
                    "model": "waktuOperasi1_",
                },
                {
                    "subTitle": "Masuk kamar operasi",
                    "type": "timePicker",
                    "model": "waktuOperasi2_",
                },
                {
                    "subTitle": "Anestesi Mulai",
                    "type": "timePicker",
                    "model": "waktuOperasi3_",
                },
                {
                    "subTitle": "Anestesi Selesai",
                    "type": "timePicker",
                    "model": "waktuOperasi4_",
                },
                {
                    "subTitle": "Operasi mulai",
                    "type": "timePicker",
                    "model": "waktuOperasi5_",
                },
                {
                    "subTitle": "Operasi selesai",
                    "type": "timePicker",
                    "model": "waktuOperasi6_",
                },
                {
                    "subTitle": "Keluar kamar operasi",
                    "type": "timePicker",
                    "model": "waktuOperasi7_",
                },
                {
                    "subTitle": "Masuk RR",
                    "type": "timePicker",
                    "model": "waktuOperasi8_",
                },
                {
                    "subTitle": "Keluar RR",
                    "type": "timePicker",
                    "model": "waktuOperasi9_",
                },
            ]
        },
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Kamar operasi",
                    "type": "textBox",
                    "model": "kamarOperasi_",
                },
                {
                    "subTitle": "No. Kamar operasi",
                    "type": "textBox",
                    "model": "noKamarOperasi_",
                },
            ]
        },
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Diagnosa pre operasi",
                    "type": "textArea",
                    "model": "diagnosaPreOperasi_",
                },
                {
                    "subTitle": "Diagnosa post operasi",
                    "type": "textArea",
                    "model": "diagnosaPostOperasi_",
                },
                {
                    "subTitle": "Tindakan",
                    "type": "textArea",
                    "model": "tindakanDataUmum_",
                },
            ]
        },
        {
            "title": "Alergi Obat :",
            "value": [
                {
                    "subTitle": "Ya, jenis obat :",
                    "type": "checkboxText",
                    "model": "alergiObatYa_",
                    "model2": "yaJenisObat_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkbox",
                    "model": "alergiObatTidak_",
                },
            ]
        },
        {
            "title": "Jenis operasi :",
            "value": [
                {
                    "subTitle": "Elektif",
                    "type": "checkbox",
                    "model": "jenisOperasiElektif_",
                },
                {
                    "subTitle": "Emergency",
                    "type": "checkbox",
                    "model": "jenisOperasiEmergency_",
                },
                {
                    "subTitle": "One day care",
                    "type": "checkbox",
                    "model": "jenisOperasiOneDayCare_",
                },
                {
                    "subTitle": "Re-operasi",
                    "type": "checkbox",
                    "model": "jenisOperasiRe-operasi_",
                },
            ]
        },
        {
            "title": "Jenis anestesi :",
            "value": [
                {
                    "subTitle": "GA",
                    "type": "checkboxText",
                    "model": "jenisAnestesiGA_",
                    "model2": "jenisAnestesiGAText_",
                },
                {
                    "subTitle": "RA",
                    "type": "checkboxText",
                    "model": "jenisAnestesiRA_",
                    "model2": "jenisAnestesiRAText_",
                },
                {
                    "subTitle": "LA",
                    "type": "checkbox",
                    "model": "jenisAnestesiLA_",
                },
            ]
        },
    ]
}
export function TimOperasi(): any {
    return [
        {
            "title": "TIM OPERASI",
            "value": [
                {
                    "subTitle": "Dokter bedah 1",
                    "type": "comboBoxDokter",
                    "model": "timOperasiDokterBedah1_",
                },
                {
                    "subTitle": "Dokter anestesi",
                    "type": "comboBoxDokter",
                    "model": "timOperasiDokterAnestesi_",
                },
                {
                    "subTitle": "Perawat sirkuler",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiPerawatSirkuler_",
                },
                {
                    "subTitle": "Dokter bedah 2",
                    "type": "comboBoxDokter",
                    "model": "timOperasiDokterBedah2_",
                },
                {
                    "subTitle": "Asisten anestesi",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiAsistenAnestesi_",
                },
                {
                    "subTitle": "Perawat instrumen",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiPerawatInstrumen_",
                },
                {
                    "subTitle": "Asisten bedah",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiAsistenBedah_",
                },
                {
                    "subTitle": "Perawat anestesi",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiPerawatAnestesi_",
                },
                {
                    "subTitle": "Petugas lain",
                    "type": "comboBoxPetugas",
                    "model": "timOperasiPetugasLain_",
                },
            ]
        },
    ]
}
export function DataFokus(): any {
    return [
        {
            "title": "DATA SUBYEKTIF",
            "value": [
                {
                    "subTitle": "Pasien mengeluh :",
                    "type": "text",
                },
                {
                    "subTitle": "Cemas",
                    "type": "checkboxText",
                    "model": "DSCemas_",
                    "model2": "DSCemasText_",
                },
                {
                    "subTitle": "Nyeri",
                    "type": "checkboxText",
                    "model": "DSNyeri_",
                    "model2": "DSNyeriText_",
                },
                {
                    "subTitle": "Pusing",
                    "type": "checkboxText",
                    "model": "DSPusing_",
                    "model2": "DSPusingText_",
                },
                {
                    "subTitle": "Haus",
                    "type": "checkboxText",
                    "model": "DSHaus_",
                    "model2": "DSHausText_",
                },
                {
                    "subTitle": "Mual",
                    "type": "checkboxText",
                    "model": "DSMual_",
                    "model2": "DSMualText_",
                },
            ]
        },
        {
            "title": "DATA OBYEKTIF",
            "value": [
                {
                    "subTitle": "TD :",
                    "nama": "mmHg",
                    "type": "textChoice",
                    "model": "DOTandaVital_",
                },
                {
                    "subTitle": "Nadi :",
                    "nama": "x/mnt",
                    "type": "textChoice",
                    "model": "DONadi_",
                },
                {
                    "subTitle": "RR :",
                    "nama": "x/mnt",
                    "type": "textChoice",
                    "model": "DORespirasi_",
                },
                {
                    "subTitle": "Suhu :",
                    "nama": "°C",
                    "type": "textChoice",
                    "model": "DOSuhu_",
                },
                {
                    "subTitle": "Sa o² :",
                    "nama": "%",
                    "type": "textChoice",
                    "model": "DOSaturasi_",
                },
                {
                    "subTitle": "BB :",
                    "nama": "kg",
                    "type": "textChoice",
                    "model": "DOBeratBadan_",
                },
                {
                    "subTitle": "TB :",
                    "nama": "cm",
                    "type": "textChoice",
                    "model": "DOTinggiBadan_",
                },
            ]
        },
    ]
}
export function DataObyektif(): any {
    return [
        {
            "title": "B1 (Breath)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB1Normal_",
                },
                {
                    "subTitle": "Batuk/Pilek",
                    "type": "checkbox",
                    "model": "DOB1BatukPilek_",
                },
                {
                    "subTitle": "Asma",
                    "type": "checkbox",
                    "model": "DOB1Asma_",
                },
                {
                    "subTitle": "Terintubasi",
                    "type": "checkbox",
                    "model": "DOB1Terintubasi_",
                },
                {
                    "subTitle": "Napas dibantu",
                    "type": "checkbox",
                    "model": "DOB1NapasDibantu_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB1Bebas1_",
                    "model2": "DOB1Bebas1Text_",
                },
            ]
        },
        {
            "title": "B2 (Blood)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB2Normal_",
                },
                {
                    "subTitle": "Kelainan jantung bawaan",
                    "type": "checkbox",
                    "model": "DOB2KelainanJantungBawaan_",
                },
                {
                    "subTitle": "Hipertensi",
                    "type": "checkbox",
                    "model": "DOB2Hipertensi_",
                },
                {
                    "subTitle": "Perdarahan",
                    "type": "checkbox",
                    "model": "DOB2Perdarahan_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB2Bebas1_",
                    "model2": "DOB2Bebas1Text_",
                },
            ]
        },
        {
            "title": "B3 (Brain)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB3Normal_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB3Bebas1_",
                    "model2": "DOB3Bebas1Text_",
                },
                {
                    "subTitle": "GCS :",
                    "nama": "E",
                    "nama2": "V",
                    "nama3": "M",
                    "type": "checkboxGCS",
                    "model": "DOB3GCS_",
                    "model2": "DOB3E_",
                    "model3": "DOB3V_",
                    "model4": "DOB3M_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB3Bebas2_",
                    "model2": "DOB3Bebas2Text_",
                },
            ]
        },
        {
            "title": "B4 (Bladder)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB4Normal_",
                },
                {
                    "subTitle": "Gagal ginjal",
                    "type": "checkbox",
                    "model": "DOB4GagalGinjal_",
                },
                {
                    "subTitle": "Kateter urine",
                    "type": "checkbox",
                    "model": "DOB4KateterUrine_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB4Bebas1_",
                    "model2": "DOB4Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB4Bebas2_",
                    "model2": "DOB4Bebas2Text_",
                },
            ]
        },
        {
            "title": "B5 (Bowel)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB5Normal_",
                },
                {
                    "subTitle": "Terpasang NGT",
                    "type": "checkbox",
                    "model": "DOB5TerpasangNGT_",
                },
                {
                    "subTitle": "Hepatitis",
                    "type": "checkbox",
                    "model": "DOB5Hepatitis_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB5Bebas1_",
                    "model2": "DOB5Bebas1Text_",
                },
                {
                    "subTitle": "Puasa",
                    "type": "checkbox",
                    "model": "DOB5Puasa_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB5Bebas2_",
                    "model2": "DOB5Bebas2Text_",
                },
            ]
        },
        {
            "title": "B6 (Bone)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "DOB6Normal_",
                },
                {
                    "subTitle": "Fraktur",
                    "type": "checkbox",
                    "model": "DOB6Fraktur_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "DOB6Bebas1_",
                    "model2": "DOB6Bebas1Text_",
                },
            ]
        },
    ]
}
export function diagnosaKeperawatan(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Cemas berhubungan dengan",
                    "type": "checkbox",
                    "model": "dKCemas_",
                },
                {
                    "subTitle": "Ancaman terhadap status kesehatan",
                    "type": "text",
                },
                {
                    "subTitle": "Kurangnya informasi tentang prosedur tindakan",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas1_",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas2_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Nyeri akut/kronis berhubungan dengan",
                    "type": "checkbox",
                    "model": "dKNyeri_",
                },
                {
                    "subTitle": "Nyeri",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas3_",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas4_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko cedera berhubungan",
                    "type": "checkbox",
                    "model": "dKRisikoCedera_",
                },
                {
                    "subTitle": "Efek obat anestesi",
                    "type": "text",
                },
                {
                    "subTitle": "Tindakan pembedahan",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas5_",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas6_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko gangguan keseimbangan cairan dan elektrolit berhubungan dengan",
                    "type": "checkbox",
                    "model": "dKRisikoGangguan_",
                },
                {
                    "subTitle": "Perdarahan intra operatif",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dKTextBebas7_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "dKcheckboxBebas1_",
                    "model2": "dKcheckboxBebas1Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "dKcheckboxBebas2_",
                    "model2": "dKcheckboxBebas2Text_",
                },
            ]
        },
    ]
}
export function rencanaKeperawatan(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Laksanakan protap interaksi sosial",
                    "type": "checkbox",
                    "model": "rKLaksanakanProtap_",
                },
                {
                    "subTitle": "Laksanakan orientasi pre operasi",
                    "type": "checkbox",
                    "model": "rKLaksanakanOrientasi_",
                },
                {
                    "subTitle": "H.E prosedur operasi",
                    "type": "checkbox",
                    "model": "rKHEProsedur_",
                },
                {
                    "subTitle": "Kolaborasi pemberian premedikasi",
                    "type": "checkbox",
                    "model": "rKKolaborasiPemberian_",
                },
                {
                    "subTitle": "Monitor efek pemberian premedikasi",
                    "type": "checkbox",
                    "model": "rKMonitorEfek_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kaji skala nyeri",
                    "type": "checkbox",
                    "model": "rKKajiSkala_",
                },
                {
                    "subTitle": "Memberikan posisi yang nyaman",
                    "type": "checkbox",
                    "model": "rKMemberikanPosisi_",
                },
                {
                    "subTitle": "Ajarkan teknik relaksasi dan distraksi",
                    "type": "checkbox",
                    "model": "rKAjarkanTeknik_",
                },
                {
                    "subTitle": "Kolaborasi dokter untuk pemberian obat analgetika",
                    "type": "checkbox",
                    "model": "rKKolaborasiDokter_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Cek kelengkapan dokumen pre operasi",
                    "type": "checkbox",
                    "model": "rKCekKelengkapan_",
                },
                {
                    "subTitle": "Menyiapkan mesin anestesi",
                    "type": "checkbox",
                    "model": "rKMenyiapkanMesin_",
                },
                {
                    "subTitle": "Menyiapkan alat dan obat anestesi",
                    "type": "checkbox",
                    "model": "rKMenyiapkanAlatAnestesi_",
                },
                {
                    "subTitle": "Menyiapkan alat dan obat sesuai pembedahan",
                    "type": "checkbox",
                    "model": "rKMenyiapkanAlatPembedahan_",
                },
                {
                    "subTitle": "Melakukan sign in",
                    "type": "checkbox",
                    "model": "rKMelakukanSignIn_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi vital sign dan keadaan umum pasien",
                    "type": "checkbox",
                    "model": "rKObservasiVitalSign_",
                },
                {
                    "subTitle": "Kolaborasi pemasangan cairan intra vena",
                    "type": "checkbox",
                    "model": "rKKolaborasiPemasangan_",
                },
                {
                    "subTitle": "Observasi intake out put",
                    "type": "checkbox",
                    "model": "rKObservasiIntakeOutput_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "textarea",
                    "model": "rKTextareaBebas1_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "textarea",
                    "model": "rKTextareaBebas2_",
                }
            ]
        },
    ]
}
export function tindakanKeperawatan(): any {
    return [
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Melakukan handover dan mengevaluasi kelengkapan dokumen pre operasi",
                    "type": "checkbox",
                    "model": "tKMelakukanHandover_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memperkenalkan diri petugas kamar operasi pada pasien",
                    "type": "checkbox",
                    "model": "tKMemperkenalkanDiri_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memberikan orientasi dan informasi lingkungan",
                    "type": "checkbox",
                    "model": "tKMemberikanOrientasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memberikan H.E tentang prosedur operasi",
                    "type": "checkbox",
                    "model": "tKMemberikanHE_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Mengobservasi vital sign (hasil ada pada catatan anestesi)",
                    "type": "checkbox",
                    "model": "tKMengobservasiVitalSign",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memasang/evaluasi akses intra vena",
                    "type": "checkbox",
                    "model": "tKMemasangIntraVena_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Mengatur posisi pasien sesuai dengan kebutuhan",
                    "type": "checkbox",
                    "model": "tKMengaturPosisiPasien_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Menyiapkan mesin anestesi",
                    "type": "checkbox",
                    "model": "tKMenyiapkanMesinAnestesi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Menyiapkan alat dan obat anestesi",
                    "type": "checkbox",
                    "model": "tKMenyiapkanAlatObatAnestesi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Membantu pemberian premedikasi",
                    "type": "checkbox",
                    "model": "tKMembantuPemberianPremedikasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memonitor efek pemberian premedikasi",
                    "type": "checkbox",
                    "model": "tKMemonitorEfekPremedikasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Menyiapkan alat dan obat sesuai pembedahan",
                    "type": "checkbox",
                    "model": "tKMenyiapkanAlatObatPembedahan_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Menyiapkan lingkungan kamar operasi",
                    "type": "checkbox",
                    "model": "tKMenyiapkanLingkunganKamarOperasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Melakukan sign in",
                    "type": "checkbox",
                    "model": "tKMelakukanSign_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 0,
                    "subTitle": "Memberikan antibiotika sesuai instruksi dokter",
                    "type": "checkbox",
                    "model": "tKMemberikanAntibiotika_",
                }
            ]
        },

        {
            "value": [
                {
                    "subTitle": "Observasi perdarahan pervagina",
                    "type": "checkbox",
                    "model": "tK3Observasiperdarahanpervagina2_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi kontraksi uterus",
                    "type": "checkbox",
                    "model": "tK3Observasikontraksiuterus2_",
                }
            ]
        },
    ]
}
export function evaluasiKeperawatan(): any {
    return [
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Lengkap",
                    "type": "checkbox",
                    "model": "eKLengkap_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak1_",
                    "model2": "eKTidak1Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkbox",
                    "model": "eKYa1_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak2_",
                    "model2": "eKTidak2Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Pasien mengerti",
                    "type": "checkbox",
                    "model": "eKPasienMengerti1_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak3_",
                    "model2": "eKTidak3Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Pasien mengerti",
                    "type": "checkbox",
                    "model": "eKPasienMengerti2_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak4_",
                    "model2": "eKTidak4Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkbox",
                    "model": "eKYa2_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak5_",
                    "model2": "eKTidak5Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Lancar",
                    "type": "checkboxLancar",
                    "model": "eKLancar_",
                    "model2": "eKLokasi_",
                    "model3": "eKUkuran_",
                    "model4": "eKNamaPemasang_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak6_",
                    "model2": "eKTidak6Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Supine",
                    "type": "checkboxText2",
                    "model": "eKSupine_",
                    "model2": "eKSupineText_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak7_",
                    "model2": "eKTidak7Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Siap",
                    "type": "checkbox",
                    "model": "eKSiap1_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak8_",
                    "model2": "eKTidak8Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Siap",
                    "type": "checkbox",
                    "model": "eKSiap2_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak9_",
                    "model2": "eKTidak9Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkbox",
                    "model": "eKYa3_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak10_",
                    "model2": "eKTidak10Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkbox",
                    "model": "eKYa4_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak11_",
                    "model2": "eKTidak11Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Siap",
                    "type": "checkbox",
                    "model": "eKSiap3_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak12_",
                    "model2": "eKTidak12Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Siap",
                    "type": "checkbox",
                    "model": "eKSiap4_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak13_",
                    "model2": "eKTidak13Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkbox",
                    "model": "eKYa5_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak14_",
                    "model2": "eKTidak14Text_",
                }
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Ya",
                    "type": "checkboxText3",
                    "model": "eKYa6_",
                    "model2": "eKJenis_",
                    "model3": "eKJam_",
                },
                {
                    "column": 2,
                    "subTitle": "Tidak,",
                    "type": "checkboxText",
                    "model": "eKTidak15_",
                    "model2": "eKTidak15Text_",
                }
            ]
        },

        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Tidak ada tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaTidakAdaTandaPerdarahanAktif2_",
                },
                {
                    "column": 1,
                    "subTitle": "Ditemukan tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaDitemukanTandaPerdarahanAktif2_",
                },
            ]
        },
        {
            "value": [
                {
                    "column": 1,
                    "subTitle": "Kontraksi ada dan kuat",
                    "type": "checkbox",
                    "model": "eK3Kontraksiada2_",
                },
                {
                    "column": 1,
                    "subTitle": "Kontraksi lemah atau tidak ada",
                    "type": "checkbox",
                    "model": "eK3Kontraksitidakada2_",
                },
            ]
        },
    ]
}
export function kondisiPasien(): any {
    return [
        {
            "title": "Kondisi pasien sebelum induksi anestesi :",
            "value": [
                {
                    "subTitle": "TD :",
                    "nama": "mmHg",
                    "type": "textBoxChoice",
                    "model": "kPTD_",
                },
                {
                    "subTitle": "Nadi :",
                    "nama": "x/mnt",
                    "type": "textBoxChoice",
                    "model": "kPNadi_",
                },
                {
                    "subTitle": "RR :",
                    "nama": "x/mnt",
                    "type": "textBoxChoice",
                    "model": "kPRR_",
                },
                {
                    "subTitle": "Suhu :",
                    "nama": "°C",
                    "type": "textBoxChoice",
                    "model": "kPSuhu_",
                },
                {
                    "subTitle": "Sa O2 :",
                    "nama": "%",
                    "type": "textBoxChoice",
                    "model": "kPSaturasiO2_",
                },
                {
                    "subTitle": "Skala nyeri :",
                    "type": "textBox",
                    "model": "kPSkalaNyeri_",
                },
            ]
        },
    ]
}
export function setInstrumen(): any {
    return [
        {
            "title": "Set instrumen steril yang disiapkan",
            "value": [
                {
                    "subTitle": "Set Dasar",
                    "type": "checkbox",
                    "model": "sISetDasar_",
                },
                {
                    "subTitle": "Set jas operasi",
                    "type": "checkbox",
                    "model": "sISetJasOperasi_",
                },
                {
                    "subTitle": "Set Khusus",
                    "type": "checkboxText",
                    "model": "sISetKhusus_",
                    "model2": "sISetKhususText_",
                },
                {
                    "subTitle": "Set drapping",
                    "type": "checkbox",
                    "model": "sISetDrapping_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "sIBebas1_",
                    "model2": "sIBebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "sIBebas2_",
                    "model2": "sIBebas2Text_",
                },
            ]
        },
    ]
}
export function alatLain(): any {
    return [
        {
            "title": "Alat lain yang disiapkan",
            "value": [
                {
                    "subTitle": "Mikroscope",
                    "type": "checkbox",
                    "model": "aLMikroscope_",
                },
                {
                    "subTitle": "C-arm",
                    "type": "checkbox",
                    "model": "aLCarm_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "aLBebas1_",
                    "model2": "aLBebas1Text_",
                }
            ]
        },
    ]
}
export function jenisAnestesi(): any {
    return [
        {
            "title": "Jenis anestesi yang diberikan",
            "value": [
                {
                    "subTitle": "GA,",
                    "type": "checkboxText",
                    "model": "jAGA_",
                    "model2": "jAGAText_",
                },
                {
                    "subTitle": "RA,",
                    "type": "checkboxText",
                    "model": "jARA_",
                    "model2": "jARAText_",
                },
                {
                    "subTitle": "LA",
                    "type": "checkbox",
                    "model": "jALA_",
                },
            ]
        },
    ]
}
export function iOBreath(): any {
    return [
        {
            "title": "B1 (Breath)",
            "value": [
                {
                    "subTitle": "Napas spontan",
                    "type": "checkbox",
                    "model": "iOB1NapasSpontan_",
                },
                {
                    "subTitle": "Napas dibantu",
                    "type": "checkbox",
                    "model": "iOB1NapasDibantu_",
                },
                {
                    "subTitle": "Terintubasi",
                    "type": "checkbox",
                    "model": "iOB1Terintubasi_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB1Bebas1_",
                    "model2": "iOB1Bebas1Text_",
                },
            ]
        },
        {
            "title": "B2 (Blood)",
            "value": [
                {
                    "subTitle": "Hemodinamik stabil",
                    "type": "checkbox",
                    "model": "iOB2HemodinamikStabil_",
                },
                {
                    "subTitle": "Hipotensi",
                    "type": "checkbox",
                    "model": "iOB2Hipotensi_",
                },
                {
                    "subTitle": "Hipertensi",
                    "type": "checkbox",
                    "model": "iOB2Hipertensi_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB2Bebas1_",
                    "model2": "iOB2Bebas1Text_",
                },
            ]
        },
        {
            "title": "B3 (Brain)",
            "value": [
                {
                    "subTitle": "DPO",
                    "type": "checkbox",
                    "model": "iOB3DPO_",
                },
                {
                    "subTitle": "Compose mentis",
                    "type": "checkbox",
                    "model": "iOB3ComposeMentis_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB3Bebas1_",
                    "model2": "iOB3Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB3Bebas2_",
                    "model2": "iOB3Bebas2Text_",
                },
            ]
        },
        {
            "title": "B4 (Bleder)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "iOB4Normal_",
                },
                {
                    "subTitle": "Kateter urine",
                    "type": "checkbox",
                    "model": "iOB4KateterUrine_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB4Bebas1_",
                    "model2": "iOB4Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB4Bebas2_",
                    "model2": "iOB4Bebas2Text_",
                },
            ]
        },
        {
            "title": "B5 (Bowel)",
            "value": [
                {
                    "subTitle": "Puasa",
                    "type": "checkbox",
                    "model": "iOB5Puasa_",
                },
                {
                    "subTitle": "Terpasang NGT",
                    "type": "checkbox",
                    "model": "iOB5TerpasangNGT_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB5Bebas1_",
                    "model2": "iOB5Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB5Bebas2_",
                    "model2": "iOB5Bebas2Text_",
                },
            ]
        },
        {
            "title": "B6 (Bone)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "iOB6Normal_",
                },
                {
                    "subTitle": "Pasang gips",
                    "type": "checkbox",
                    "model": "iOB6PasangGips_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB6Bebas1_",
                    "model2": "iOB6Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOB6Bebas2_",
                    "model2": "iOB6Bebas2Text_",
                },
            ]
        },
        {
            "title": "Posisi Pasien",
            "value": [
                {
                    "subTitle": "Supinasi",
                    "subTitle2": "Litotomi",
                    "type": "DBcheckbox",
                    "model": "iOPPSupinasi_",
                    "model2": "iOPPLitotomi_",
                },
                {
                    "subTitle": "Lateral kiri",
                    "subTitle2": "Pronasi",
                    "type": "DBcheckbox",
                    "model": "iOPPLateralKiri_",
                    "model2": "iOPPPronasi_",
                },
                {
                    "subTitle": "Lateral Kanan",
                    "subTitle2": "",
                    "type": "DBcheckboxBebas",
                    "model": "iOPPLateralKanan_",
                    "model2": "iOPPBebasi_",
                    "model3": "iOPPBeabsiText_",
                },
                {
                    "subTitle": "Trendelenberg",
                    "subTitle2": "",
                    "type": "DBcheckboxBebas",
                    "model": "iOPPTrendelenberg_",
                    "model2": "iOPPBebas2_",
                    "model3": "iOPPBeabs2Text_",
                },
            ]
        },
        {
            "title": "Kontrol suhu",
            "value": [
                {
                    "subTitle": "Selimut/matras penghangat",
                    "type": "checkbox",
                    "model": "iOKSSelimut_",
                },
                {
                    "subTitle": "Cairan hangat",
                    "type": "checkbox",
                    "model": "iOKSCairanHangat_",
                },
                {
                    "subTitle": "Infus warmer",
                    "type": "checkbox",
                    "model": "iOKSInfusWarmer_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "iOKSBebas1_",
                    "model2": "iOKSBebas1Text_",
                },
            ]
        },
    ]
}
export function diagnosaKeperawatan2(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Kebersihan jalan napas tidak efektif berhubungan dengan :",
                    "type": "checkbox",
                    "model": "dK2KebersihanJalanNapasTidakEfektifBerhubunganDengan_",
                },
                {
                    "subTitle": "Sekresi trakeo bronkial",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas1_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko hypothermy berhubungan dengan :",
                    "type": "checkbox",
                    "model": "dK2RisikoHypothermy_",
                },
                {
                    "subTitle": "Pemajanan pada lingkungan yang dingin",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas2_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko gangguan integritas kulit berhubungan dengan :",
                    "type": "checkbox",
                    "model": "dK2RisikoGangguan_",
                },
                {
                    "subTitle": "Imobilisasi fisik",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas3_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko injury berhubungan dengan :",
                    "type": "checkbox",
                    "model": "dK2RisikoInjury_",
                },
                {
                    "subTitle": "Penggunaan diathermy",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas4_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko kurang volume cairan berhubungan dengan :",
                    "type": "checkbox",
                    "model": "dK2RisikoKurang_",
                },
                {
                    "subTitle": "Kehilangan volume cairan aktif",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas5_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko infeksi berhubungan :",
                    "type": "checkbox",
                    "model": "dK2RisikoInfeksi_",
                },
                {
                    "subTitle": "Daya tahan tubuh primer tidak adekuat",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK2TextBebas6_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Nama perawat pengkaji",
                    "type": "combo",
                    "model": "dK2PerawatPengkaji_",
                },
            ]
        },
    ]
}
export function rencanaKeperawatan2(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Siapkan peralatan resusitasi",
                    "type": "checkbox",
                    "model": "rK2SiapkanPeralatan_",
                },
                {
                    "subTitle": "Bebaskan jalan napas",
                    "type": "checkbox",
                    "model": "rK2BebaskanJalanNapas_",
                },
                {
                    "subTitle": "Berikan oxygen sesuai kebutuhan",
                    "type": "checkbox",
                    "model": "rK2BerikanOxygen_",
                },
                {
                    "subTitle": "Observasi pemasangan packing tenggorokan",
                    "type": "checkbox",
                    "model": "rK2ObservasiPemasangan_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "rK2CheckboxBebas_",
                    "model2": "rK2CheckboxBebasText_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Sesuaikan suhu kamar operasi dengan kondisi pasien",
                    "type": "checkbox",
                    "model": "rK2SesuaikanSuhuKamar_",
                },
                {
                    "subTitle": "Berikan selimut hangat pada pasien",
                    "type": "checkbox",
                    "model": "rK2BerikanSelimutHangat_",
                },
                {
                    "subTitle": "Observasi vital sign",
                    "type": "checkbox",
                    "model": "rK2ObservasiVitalSign_",
                },
                {
                    "subTitle": "Gunakan pencucian luka dengan cairan hangat",
                    "type": "checkbox",
                    "model": "rK2GunakanPencucianLuka_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Posisikan pasien dengan tepat sesuai kebutuhan pembedahan",
                    "type": "checkbox",
                    "model": "rK2PosisikanPasienPembedahan_",
                },
                {
                    "subTitle": "Pasang pengalas lembut didaerah kulit yang tertekan",
                    "type": "checkbox",
                    "model": "rK2PasangPengelasLembut_",
                },
                {
                    "subTitle": "Lakukan pengikatan, perhatikan risiko kerusakan kulit & saraf",
                    "type": "checkbox",
                    "model": "rK2LakukanPengikatan_",
                },
                {
                    "subTitle": "Monitor keutuhan kulit yang tertekan",
                    "type": "checkbox",
                    "model": "rK2MonitorKeutuhanKulit_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "rK2CheckboxBebas2_",
                    "model2": "rK2CheckboxBebas2Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Periksa kesiapan diatermi plat",
                    "type": "checkbox",
                    "model": "rK2PeriksaKesiapanDiatermiPlat_",
                },
                {
                    "subTitle": "Periksa keutuhan kulit yang dipasang plat diatermi",
                    "type": "checkbox",
                    "model": "rK2PeriksaKeutuhanKuliatPlatDiatermi_",
                },
                {
                    "subTitle": "Tempatkan plat diatermi di tempat yang berotot dan kering",
                    "type": "checkbox",
                    "model": "rK2TempatkanPlatDiatermi_",
                },
                {
                    "subTitle": "Evaluasi tempat plat diatermi pasca operasi",
                    "type": "checkbox",
                    "model": "rK2EvaluasiTempatPlatDiatermiPascaOperasi_",
                },
                {
                    "subTitle": "Lakukan penghitungan intra-operatif",
                    "type": "checkbox",
                    "model": "rK2LakukanPerhitunganIntraOperatif_",
                },
                {
                    "subTitle": "Lakukan time out - sign out",
                    "type": "checkbox",
                    "model": "rK2LakukanTimeOutSignOut_",
                },
                {
                    "subTitle": "Monitor pemasangan torniquet",
                    "type": "checkbox",
                    "model": "rK2MonitorPemasanganTorniquet_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi intake dan output",
                    "type": "checkbox",
                    "model": "rK2ObservasiIntakeOutput_",
                },
                {
                    "subTitle": "Catat jumlah perdarahan",
                    "type": "checkbox",
                    "model": "rK2CatatJumlahPerdarahan_",
                },
                {
                    "subTitle": "",
                    "type": "textarea",
                    "model": "rK2TextareaBebas1_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Lakukan general precaution",
                    "type": "checkbox",
                    "model": "rK2LakukanGeneralPrecaution_",
                },
                {
                    "subTitle": "Siapkan alat operasi secara steril",
                    "type": "checkbox",
                    "model": "rK2SiapkanAlatOperasiSteril_",
                },
                {
                    "subTitle": "Lakukan desinfeksi area operasi",
                    "type": "checkbox",
                    "model": "rK2LakukanDesinfeksiAreaOperasi_",
                },
                {
                    "subTitle": "Kolaborasi pemberian antibiotik",
                    "type": "checkbox",
                    "model": "rK2KolaborasiPemberianAntibiotik_",
                },
                {
                    "subTitle": "Lakukan penutupan lapangan operasi dengan steril",
                    "type": "checkbox",
                    "model": "rK2LakukanPenutupanLapanganOperasiSteril_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Tanda tangan perawat pengkaji",
                    "type": "ttd",
                    "model": "rK2TTDPerawat_",
                }
            ]
        },
    ]
}
export function tindakanKeperawatan2(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Mendampingi dan mengantar pasien pindah ke meja operasi",
                    "type": "checkbox",
                    "model": "tK2MendampingiDanMengantarPasienPindahKeMejaOperasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memberi dukungan psikologis, mengkomunikasikan setiap tindakan yang akan dilakukan dan menjaga privacy pasien",
                    "type": "checkbox",
                    "model": "tK2MemberiDukunganPsikologis_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memasang bed side monitor & melakukan observasi vital sign",
                    "type": "checkbox",
                    "model": "tK2MemasangBedSideMonitor_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kolaborasi dalam pemberian anestesi",
                    "type": "checkbox",
                    "model": "tK2KolaborasiDalamPemberianAnestesi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengatur posisi pasien untuk pembedahan serta mencegah terjadinya cidera akibat posisi pembedahan",
                    "type": "checkbox",
                    "model": "tK2MengaturPosisiPasienPembedahanMencegahCideraAkibatPosisiPembedahan_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan pencegahan kerusakan integritas kulit yang tertekan dengan memberi alas lembut, mengikat dengan baik dan mengobservasi keutuhan kulit yang tertekan",
                    "type": "checkbox",
                    "model": "tK2MelakukanPencegahanKerusakanIntegritasKulit_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mencukur daerah operasi",
                    "type": "checkbox",
                    "model": "tK2MencukurDaerahOperasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan prosedur septik aseptik pembedahan (scrubing, gowning, gloving, penataan instrumen bedah)",
                    "type": "checkbox",
                    "model": "tK2MelakukanProsedurSeptikAseptikPembedahan_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan penghitungan intra operatif (sesuai ceklist alat)",
                    "type": "checkbox",
                    "model": "tK2MelakukanPerhitunganIntraOperatif_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan/memfasilitasi tindakan skin preparation dan draping",
                    "type": "checkbox",
                    "model": "tK2MelakukanMemfasilitasiTindakanSkinPreparationDraping_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan/memfasilitasi tindakan menggunakan ESU (diatermi)",
                    "type": "checkbox",
                    "model": "tK2MelakukanMemfasilitasiTindakanMengunakanESU_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memasang dan memonitor penggunaan torniquet",
                    "type": "checkbox",
                    "model": "tK2MemasangDanMemonitorPenggunaanTorniquet_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan time out",
                    "type": "checkbox",
                    "model": "tK2MelakukanTimeOut_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi perdarahan pervagina",
                    "type": "checkbox",
                    "model": "tK3Observasiperdarahanpervagina_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi kontraksi uterus",
                    "type": "checkbox",
                    "model": "tK3Observasikontraksiuterus_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memfasilitasi penggunaan anestesi lokal",
                    "type": "checkbox",
                    "model": "tK2MemfasilitasiPenggunaanAnestesiLokal_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan instrumentasi teknik dan kolaborasi pembedahan",
                    "type": "checkbox",
                    "model": "tK2MelakukanInstrumentalTeknikDanKolaborasiPembedahan_",
                }
            ]
        },
        {
            "value": [
                {
                    "type": "manual1",
                }
            ]
        },
        {
            "value": [
                {
                    "type": "manual3",
                }
            ]
        },
        // {
        //     "value": [
        //         {
        //             "subTitle": "Melakukan penutupan luka",
        //             "type": "checkbox",
        //             "model": "tK2MelakukanPenutupanLuka_",
        //         }
        //     ]
        // },
        // {
        //     "value": [
        //         {
        //             "subTitle": "Melakukan perawatan drain",
        //             "type": "checkbox",
        //             "model": "tK2MelakukanPerawatanDrain_",
        //         }
        //     ]
        // },
        {
            "value": [
                {
                    "type": "manual2",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengobservasi keutuhan kulit daerah pemasangan plate diatermi",
                    "type": "checkbox",
                    "model": "tK2MengobservasiKeutuhanKulitDaerahPemasanganPlateDiatermi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Menyiapkan bahan pemeriksaan jaringan patologi anatomi",
                    "type": "checkbox",
                    "model": "tK2MenyiapkanBahanPemeriksaanJaringanPatologiAnatomi_",
                }
            ]
        },
        {
          "value": [
              {
                  "subTitle": "Menyiapkan bahan pemeriksaan jaringan kultur",
                  "type": "checkbox",
                  "model": "tK2MenyiapkanBahanPemeriksaanJaringanKultur_",
              }
          ]
      },
        {
            "value": [
                {
                    "subTitle": "Melakukan sign out",
                    "type": "checkbox",
                    "model": "tK2MelakukanSignOut_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kolaborasi dalam pengakhiran anestesi",
                    "type": "checkbox",
                    "model": "tK2KolaborasiDalamPengakhiranAnestesi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Evaluasi kondisi pasien sebelum meninggalkan kamar operasi",
                    "type": "checkbox",
                    "model": "tK2EvaluasiKondisiPasienSebelumMeninggalkanKamarOperasi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengantar pasien pindah ke RR",
                    "type": "checkbox",
                    "model": "tK2MengantarPasienPindahKeRR_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "ITEM YANG SENGAJA DITINGGAL DI DALAM TUBUH PASIEN (SEMENTARA)",
                    "type": "text2",
                }
            ]
        },
        {
            "value": [
                {
                    "model": "tK2ItemYangSengajaDitinggalDiDalamTubuhPasien_",
                    "type": "textarea",
                }
            ]
        },

    ]
}
export function evaluasiKeperawatan2(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Pasien di meja operasi didampingi tim operasi",
                    "type": "checkbox",
                    "model": "eK2PasienDiMejaOperasiDidampingiTimOperasi_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Pasien menyatakan siap menjalani operasi",
                    "type": "checkbox",
                    "model": "eK2PasienMenyatakanSiapMenjalaniOperasi_",
                },
                {
                    "subTitle": "Pasien masih cemas",
                    "type": "checkbox",
                    "model": "eK2PasienMasihCemas_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Bed side monitor terpasang dan berfungsi baik",
                    "type": "checkbox",
                    "model": "eK2BedSiteMonitorTerpasangDanBerfungsiBaik_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Pemberian anestesi berjalan lancar",
                    "type": "checkbox",
                    "model": "eK2PemberianAnestesiBerjalanLancar_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Posisi diatur, cedera tidak terjadi",
                    "type": "checkbox",
                    "model": "eK2PosisiDiaturCederaTidakTerjadi_",
                },
                {
                    "subTitle": "Ada cidera akibat posisi pembedahan",
                    "type": "checkbox",
                    "model": "eK2AdaCideraAkibatPosisiPembedahan_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kerusakan integritas kulit tidak terjadi",
                    "type": "checkbox",
                    "model": "eK2KerusakanIntegritasKulitTidakTerjadi_",
                },
                {
                    "subTitle": "Ada kerusakan integritas kulit akibat posisi pembedahan",
                    "type": "checkbox",
                    "model": "eK2AdaKerusakanIntegritasKulitAkibatPosisiPembedahan_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Daerah operasi bersih",
                    "type": "checkbox",
                    "model": "eK2DaerahOperasiBersih_",
                },
                {
                    "subTitle": "Tindakan diperlukan pencukuran daerah operasi",
                    "type": "checkbox",
                    "model": "eK2TindakanDiperlukanPencukuranDaerahOperasi_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Prosedur terlaksana sesuai standar yang berlaku",
                    "type": "checkbox",
                    "model": "eK2ProsedurTerlaksanaSesuaiStandarYangBerlaku_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Penghitungan benar sesuai ceklist",
                    "type": "checkbox",
                    "model": "eK2PenghitunganBenarSesuaiCeklist_",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual1",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual2",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual3",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Terlaksana dengan baik sesuai ceklist",
                    "type": "checkbox",
                    "model": "eK2TerlaksanaDenganBaikSesuaiCeklist_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Tidak ada tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaTidakAdaTandaPerdarahanAktif_",
                },
                {
                    "subTitle": "Ditemukan tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaDitemukanTandaPerdarahanAktif_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kontraksi ada dan kuat",
                    "type": "checkbox",
                    "model": "eK3Kontraksiada_",
                },
                {
                    "subTitle": "Kontraksi lemah atau tidak ada",
                    "type": "checkbox",
                    "model": "eK3Kontraksitidakada_",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual4",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Instrumentasi berjalan lancar",
                    "type": "checkbox",
                    "model": "eK2InstrumentalBerjalanLancar_",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual5",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual6",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual7",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual8",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual9",
                },
            ]
        },
        {
          "value": [
              {
                  "type": "manual100",
              },
          ]
      },
        {
            "value": [
                {
                    "subTitle": "Terlaksana dengan baik sesuai ceklist time out",
                    "type": "checkbox",
                    "model": "eK2TerlaksanaDenganBaikSesuaiCeklistTimeOut_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Pengakhiran anestesi berjalan baik",
                    "type": "checkbox",
                    "model": "eK2PengakhiranAnestesiBerjalanBaik_",
                },
            ]
        },
        {
            "value": [
                {
                    "type": "manual10",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Pasien sudah di RR, dilakukan handover dengan petugas RR",
                    "type": "checkbox",
                    "model": "eK2PasienSudahDiRRHandover_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "ITEM YANG HARUS DISERAHKAN KE PASIEN / KELUARGA / PETUGAS LAIN",
                    "type": "text2",
                }
            ]
        },
        {
            "value": [
                {
                    "model": "eK2ItemYangHarusDiserahkanKePasienKeluargaPetugasLain_",
                    "type": "textarea",
                }
            ]
        },
    ]
}
export function pasienMengeluh(): any {
    return [
        {
            "title": "Pasien mengeluh :",
            "value": [
                {
                    "subTitle": "Mual",
                    "subTitle2": "",
                    "type": "checkbox2",
                    "model": "pMMual_",
                    "model2": "pMcheckboxBebas1_",
                    "model3": "pMcheckboxBebas1Text_",
                },
                {
                    "subTitle": "Nyeri",
                    "subTitle2": "",
                    "type": "checkbox2",
                    "model": "pMNyeri_",
                    "model2": "pMcheckboxBebas2_",
                    "model3": "pMcheckboxBebas2Text_",
                },
                {
                    "subTitle": "Pusing",
                    "subTitle2": "",
                    "type": "checkbox2",
                    "model": "pMPusing_",
                    "model2": "pMcheckboxBebas3_",
                    "model3": "pMcheckboxBebas3Text_",
                },
                {
                    "subTitle": "Haus",
                    "subTitle2": "",
                    "type": "checkbox2",
                    "model": "pMHaus_",
                    "model2": "pMcheckboxBebas4_",
                    "model3": "pMcheckboxBebas4Text_",
                },
                {
                    "subTitle": "Kedinginan",
                    "subTitle2": "",
                    "type": "checkbox2",
                    "model": "pMKedinginan_",
                    "model2": "pMcheckboxBebas5_",
                    "model3": "pMcheckboxBebas5Text_",
                },
            ]
        },
    ]
}
export function pOVitalSign(): any {
    return [
        {
            "title": "Vital sign :",
            "value": [
                {
                    "subTitle": "TD :",
                    "nama": "mmHg",
                    "type": "textBoxChoice",
                    "model": "vSTD_",
                },
                {
                    "subTitle": "Nadi :",
                    "nama": "x/mnt",
                    "type": "textBoxChoice",
                    "model": "vSNadi_",
                },
                {
                    "subTitle": "RR :",
                    "nama": "x/mnt",
                    "type": "textBoxChoice",
                    "model": "vSRR_",
                },
                {
                    "subTitle": "Suhu :",
                    "nama": "°C",
                    "type": "textBoxChoice",
                    "model": "vSSuhu_",
                },
                {
                    "subTitle": "Sa o² :",
                    "nama": "%",
                    "type": "textBoxChoice",
                    "model": "vSSao_",
                },
                {
                    "subTitle": "Skala nyeri :",
                    "type": "textBox",
                    "model": "vSSkalaNyeri_",
                },
            ]
        },
    ]
}
export function pOBreath(): any {
    return [
        {
            "title": "B1 (Breath)",
            "value": [
                {
                    "subTitle": "Napas spontan",
                    "type": "checkbox",
                    "model": "pOB1NapasSpontan_",
                },
                {
                    "subTitle": "Terpasang OTT",
                    "type": "checkbox",
                    "model": "pOB1TerpasangOTT_",
                },
                {
                    "subTitle": "Napas bantu",
                    "type": "checkbox",
                    "model": "pOB1NapasBantu_",
                },
                {
                    "subTitle": "Mendapat therapi",
                    "type": "checkbox",
                    "model": "pOB1MendapatTherapi_",
                },
                {
                    "subTitle": "Oksigen",
                    "type": "checkbox",
                    "model": "pOB1Oksigen_",
                },
            ]
        },
        {
            "title": "B2 (Blood)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "pOB2Normal_",
                },
                {
                    "subTitle": "Hipotensi",
                    "type": "checkbox",
                    "model": "pOB2Hipotensi_",
                },
                {
                    "subTitle": "Hipertensi",
                    "type": "checkbox",
                    "model": "pOB2Hipertensi_",
                },
                {
                    "subTitle": "Perdarahan",
                    "type": "checkbox",
                    "model": "pOB2Perdarahan_",
                },
            ]
        },
        {
            "title": "B3 (Brain)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "pOB3Normal_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB3Bebas1_",
                    "model2": "pOB3Bebas1Text_",
                },
                {
                    "subTitle": "DPO",
                    "type": "checkbox",
                    "model": "pOB3DPO_",
                },
            ]
        },
        {
            "title": "B4 (Bladder)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "pOB4Normal_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB4Bebas1_",
                    "model2": "pOB4Bebas1Text_",
                },
                {
                    "subTitle": "Kateter urine",
                    "type": "checkbox",
                    "model": "pOB4KateterUrine_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB4Bebas2_",
                    "model2": "pOB4Bebas2Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB4Bebas3_",
                    "model2": "pOB4Bebas3Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB4Bebas4_",
                    "model2": "pOB4Bebas4Text_",
                },
            ]
        },
        {
            "title": "B5 (Bowel)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "pOB5Normal_",
                },
                {
                    "subTitle": "Terpasang NGT",
                    "type": "checkbox",
                    "model": "pOB5TerpasangNGT_",
                },
                {
                    "subTitle": "Puasa",
                    "type": "checkbox",
                    "model": "pOB5Puasa_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB5Bebas1_",
                    "model2": "pOB5Bebas1Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB5Bebas2_",
                    "model2": "pOB5Bebas2Text_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB5Bebas3_",
                    "model2": "pOB5Bebas3Text_",
                },
            ]
        },
        {
            "title": "B6 (Bone)",
            "value": [
                {
                    "subTitle": "Normal",
                    "type": "checkbox",
                    "model": "pOB6Normal_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB6Bebas1_",
                    "model2": "pOB6Bebas1Text_",
                },
                {
                    "subTitle": "Fraktur",
                    "type": "checkbox",
                    "model": "pOB6Fraktur_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "pOB6Bebas2_",
                    "model2": "pOB6Bebas2Text_",
                },
            ]
        },
    ]
}
export function pOKeterangan(): any {
    return [
        {
            "title": "DATA PENUNJANG LABORATORIUM",
            "value": [
                {
                    "subTitle": "Data Penunjang Laboratorium",
                    "type": "textarea",
                    "model": "pODataPenungjangLaboratoriumText_",
                }
            ]
        },
        {
            "title": "RADIOLOGI",
            "value": [
                {
                    "subTitle": "Radiologi",
                    "type": "textarea",
                    "model": "pORadiologiText_",
                }
            ]
        },
        {
            "title": "DATA PENUNJANG LAINNYA",
            "value": [
                {
                    "subTitle": "Data Penunjang Lainnya",
                    "type": "textarea",
                    "model": "pODataPenunjangLainnyaText_",
                }
            ]
        },
    ]
}
export function diagnosaKeperawatan3(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Kebersihan jalan nafas tidak efektif berhubungan dengan",
                    "type": "checkbox",
                    "model": "dK3KebersihanJalanNafasTidakEfektif_",
                },
                {
                    "subTitle": "Sekresi trakheobronchial",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK3TextBebas1_",
                },
                {
                    "type": "textBox",
                    "model": "dK3TextBebas2_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Nyeri akut/kronis berhubungan dengan",
                    "type": "checkbox",
                    "model": "dK3NyeriAkutKronis_",
                },
                {
                    "subTitle": "Cedera",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK3TextBebas3_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko cedera/kecelakaan berhubungan dengan",
                    "type": "checkbox",
                    "model": "dK3RisikoCedera_",
                },
                {
                    "subTitle": "Efek obat anestesi",
                    "type": "text",
                },
                {
                    "subTitle": "Tindakan pembedahan",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK3TextBebas4_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Risiko gangguan keseimbangan cairan elektrolit berhubungan dengan",
                    "type": "checkbox",
                    "model": "dK3RisikoGangguan_",
                },
                {
                    "subTitle": "Perdarahan post operatif",
                    "type": "text",
                },
                {
                    "type": "textBox",
                    "model": "dK3TextBebas5_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "textarea",
                    "model": "rK3TextareaBebas1_",
                },
            ]
        },
    ]
}
export function rencanaKeperawatan3(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Siapkan peralatan resusitasi",
                    "type": "checkbox",
                    "model": "rK3SiapkanPeralatanResusitasi_",
                },
                {
                    "subTitle": "Bebaskan jalan napas",
                    "type": "checkbox",
                    "model": "rK3BebaskanJalanNapas_",
                },
                {
                    "subTitle": "Berikan oxygen sesuai kebutuhan",
                    "type": "checkbox",
                    "model": "rK3BerikanOxygenSesuaiKebutuhan_",
                },
                {
                    "subTitle": "Bersihkan sekret pada jalan napas",
                    "type": "checkbox",
                    "model": "rK3BersihkanSekretPadaJalanNapas_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kaji skala nyeri",
                    "type": "checkbox",
                    "model": "rK3KajiSkala_",
                },
                {
                    "subTitle": "Memberikan posisi yang nyaman",
                    "type": "checkbox",
                    "model": "rK3MemberikanPosisi_",
                },
                {
                    "subTitle": "Ajarkan teknik relaksasi dan distraksi",
                    "type": "checkbox",
                    "model": "rK3AjarkanTeknik_",
                },
                {
                    "subTitle": "Kolaborasi dengan dokter",
                    "type": "checkbox",
                    "model": "rK3KolaborasiDenganDokter_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kaji resiko jatuh",
                    "type": "checkbox",
                    "model": "rK3KajiResikoJatuh_",
                },
                {
                    "subTitle": "Laksanakan protap resiko jatuh",
                    "type": "checkbox",
                    "model": "rK3LaksakananProtapResikoJatuh_",
                },
                {
                    "subTitle": "Pantau efek penggunaan obat anestesi",
                    "type": "checkbox",
                    "model": "rK3PantauEfekPenggunaanObatAnestesi_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Observasi vital sign dan keadaan umum pasien",
                    "type": "checkbox",
                    "model": "rK3ObservasiVitalSign_",
                },
                {
                    "subTitle": "Kolaborasi pemberian cairan intra vena",
                    "type": "checkbox",
                    "model": "rK3KolaborasiPemberian_",
                },
                {
                    "subTitle": "Observasi intake out put",
                    "type": "checkbox",
                    "model": "rK3ObservasiIntakeOutput_",
                },
                {
                    "subTitle": "Observasi tanda-tanda perdarahan",
                    "type": "checkbox",
                    "model": "rK3ObservasiTandaPerdarahan_",
                },
                {
                    "subTitle": "",
                    "type": "checkboxBebas",
                    "model": "rK3CheckboxBebas1_",
                    "model2": "rK3CheckboxBebas1Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "",
                    "type": "textarea",
                    "model": "rK3TextareaBebas2_",
                },
            ]
        },
    ]
}
export function tindakanKeperawatan3(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Melakukan handover pasien",
                    "type": "checkbox",
                    "model": "tK3MelakukanHandoverPasien_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengatur posisi pasien sesuai dengan kebutuhan",
                    "type": "checkbox",
                    "model": "tK3MengaturPosisiPasienSesuaiDenganKebutuhan_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memberikan therapi oksigen",
                    "type": "checkbox",
                    "model": "tK3MemberikanTerapiOksigen_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengobservasi vital sign",
                    "type": "checkbox",
                    "model": "tK3MengobservasiVitalSign_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengobservasi intake dan out put",
                    "type": "checkbox",
                    "model": "tK3MengobservasiIntakeDanOutput_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Mengobservasi kondisi luka operasi dan drain",
                    "type": "checkbox",
                    "model": "tK3MengobservasiKondisiLukaOperasiDanDrain_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan kolaborasi dalam pemberian analgetik",
                    "type": "checkbox",
                    "model": "tK3MelakukanKolaborasiDalamPemberianAnalgetik_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan kolaborasi manajemen mual muntah",
                    "type": "checkbox",
                    "model": "tK3MelakukanKolaborasiManajemenMualMuntah_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan pencegahan/penanganan pasien hipothermi/mengigil",
                    "type": "checkbox",
                    "model": "tK3MelakukanPencegahanPenangananPasienHipothermi_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan penilaian Bromage Score",
                    "type": "checkbox",
                    "model": "tK3MelakukanPenilaianBromageScore_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Melakukan penilaian Aldrete Score",
                    "type": "checkbox",
                    "model": "tK3MelakukanPenilaianAldreteScore_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Memfasilitasi pemenuhan kebutuhan ADLselama proses recovery",
                    "type": "checkbox",
                    "model": "tK3MemfasilitasiPemenuhanKebutuhanADLSelamaProsesRecovery_",
                }
            ]
        },
    ]
}
export function evaluasiKeperawatan3(): any {
    return [
        {
            "value": [
                {
                    "subTitle": "Terlaksana dengan baik",
                    "type": "checkbox",
                    "model": "eK3TerlaksanaDenganBaik_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Posisi pasien",
                    "type": "checkboxText",
                    "model": "eK3PosisiPasien_",
                    "model2": "eK3PosisiPasienText_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "nama": "lpm",
                    "type": "checkboxText2",
                    "model": "eK3Ya_",
                    "model2": "eK3YaText1_",
                    "model3": "eK3YaText2_",
                }
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Pasien terobservasi (terdokumentasi pada catatan anestesi)",
                    "type": "checkbox",
                    "model": "eK3PasienTerobservasi_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "subTitle2": "Tidak",
                    "type": "checkbox2",
                    "model": "eK3Ya1_",
                    "model2": "eK3Tidak1_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Tidak ada tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3TidakAdaTandaPerdarahanAktif_",
                },
                {
                    "subTitle": "Ditemukan tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3DitemukanTandaPerdarahanAktif_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Tidak ada tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaTidakAdaTandaPerdarahanAktif_",
                },
                {
                    "subTitle": "Ditemukan tanda perdarahan aktif",
                    "type": "checkbox",
                    "model": "eK3PervaginaDitemukanTandaPerdarahanAktif_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Kontraksi ada dan kuat",
                    "type": "checkbox",
                    "model": "eK3Kontraksiada_",
                },
                {
                    "subTitle": "Kontraksi lemah atau tidak ada",
                    "type": "checkbox",
                    "model": "eK3Kontraksitidakada_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "subTitle2": "Tidak",
                    "subTitle3": "",
                    "type": "checkbox3",
                    "model": "eK3Ya2_",
                    "model2": "eK3Tidak2_",
                    "model3": "eK3CheckboxBebas1_",
                    "model4": "eK3CheckboxBebas1Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "subTitle2": "Tidak",
                    "subTitle3": "",
                    "type": "checkbox3",
                    "model": "eK3Ya3_",
                    "model2": "eK3Tidak3_",
                    "model3": "eK3CheckboxBebas2_",
                    "model4": "eK3CheckboxBebas2Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "subTitle2": "Tidak",
                    "subTitle3": "",
                    "type": "checkbox3",
                    "model": "eK3Ya4_",
                    "model2": "eK3Tidak4_",
                    "model3": "eK3CheckboxBebas3_",
                    "model4": "eK3CheckboxBebas3Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Nilai :",
                    "type": "checkboxBebas",
                    "model": "eK3Nilai1_",
                    "model2": "eK3Nilai1Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Nilai :",
                    "type": "checkboxBebas",
                    "model": "eK3Nilai2_",
                    "model2": "eK3Nilai2Text_",
                },
            ]
        },
        {
            "value": [
                {
                    "subTitle": "Ya",
                    "subTitle2": "Tidak",
                    "type": "checkbox2",
                    "model": "eK3Ya5_",
                    "model2": "eK3Tidak5_",
                },
            ]
        },
    ]
}
