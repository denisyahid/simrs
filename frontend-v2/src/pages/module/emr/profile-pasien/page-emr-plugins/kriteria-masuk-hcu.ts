export function kriteria(): any {
  return [
    {
      label: "VITAL SIGN",
      detail: [
        { deskripsi: "Nilai pemantauan EWS oranye, merah dengan distres napas", model: "vitalSignDistresNapas" }
      ]
    },
    {
      label: "PEMERIKSAAN FISIK",
      detail: [
        { deskripsi: "Oliguria - Anuria", model: "pemeriksaanFisikOliguriaAnuria" },
        { deskripsi: "Klinis dehidrasi berat", model: "pemeriksaanFisikDehidrasi" },
        { deskripsi: "Kejang berulang atau status epileptikus", model: "pemeriksaanFisikKejang" },
        { deskripsi: "Tamponade jantung atau pneumothorax dengan gangguan hemodinamik", model: "pemeriksaanFisikTamponade" },
        { deskripsi: "Overload cairan", model: "pemeriksaanFisikOverloadCairan" }
      ]
    },
    {
      label: "ECG",
      detail: [
        { deskripsi: "Infark miokard dengan aritmia kompleks, gagal jantung kongestif yang berpotensi mengancam nyawa", model: "ecgInfarkMiokard" }
      ]
    },
    {
      label: "NILAI LABORATORIUM",
      detail: [
        { deskripsi: "Kadar natrium darah < 125 mmol/L, Hipernatremi > 155 mmol/L", model: "nilaiLabNatrium" },
        { deskripsi: "Kadar kalium serum < 2,5 mmol/L, Hiperkalemi > 5 mmol/L", model: "nilaiLabKalium" },
        { deskripsi: "PaO2 < 50 mmHg pada oksigen ruangan", model: "nilaiLabPaO2" },
        { deskripsi: "pH < 7,1 atau > 7,7 dengan oksigen ruangan", model: "nilaiLabPH" },
        { deskripsi: "Serum glukosa > 800 mg/dl", model: "nilaiLabGlukosa" },
        { deskripsi: "Serum kalsium > 15 mg/dl", model: "nilaiLabKalsium" },
        { deskripsi: "Kadar keton urin > +3", model: "nilaiLabKeton" },
        { deskripsi: "Kadar BUN/SC darah yang membutuhkan RRT segera", model: "nilaiLabBUN" },
        { deskripsi: "Kadar obat atau substansi kimia dalam darah telah melebihi dosis toksis yang mengganggu hemodinamik dan berpotensi mengancam nyawa (alergi/anafilaksis)", model: "nilaiLabToksin" }
      ]
    },
    {
      label: "NILAI RADIOLOGI",
      detail: [
        { deskripsi: "Gambaran CT Scan abnormal yang berpotensi mengancam nyawa (perdarahan cerebral, contusion atau perdarahan subarachnoid) dengan atau tanpa penurunan status mental", model: "nilaiRadiologiCTScan" },
        { deskripsi: "Ruptur viscera, blader, liver, vaskuler, esofagus, perdarahan dengan atau tanpa status mental menurun", model: "nilaiRadiologiRuptur" }
      ]
    },
    {
      label: "PASIEN DENGAN KEPERLUAN PERSIAPAN DAN PEMANTAUAN PRA INTRA PASKA OPERASI",
      detail:[
        {
          model: "pasienDenganKepeluaranPraIntraPaskaOperasi",
        }
      ]
    }
  ];
}




export function JenisKelamin():any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
