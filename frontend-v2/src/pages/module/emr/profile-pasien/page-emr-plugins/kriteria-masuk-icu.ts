export function kriteria() {
  return [
    {
      label: "VITAL SIGN",
      detail: [
        {
          deskripsi: "Nilai pemantauan EWS oranye merah dengan distres napas dan ancaman gagal napas",
          model: "vitalSign"
        }
      ]
    },
    {
      label: "PEMERIKSAAN FISIK",
      detail: [
        {
          deskripsi: "Oliguria - Anuria",
          model: "oliguriaAnuria"
        },
        {
          deskripsi: "Obstruksi saluran napas",
          model: "obstruksiSaluranNapas"
        },
        {
          deskripsi: "Kejang berulang atau status epileptikus",
          model: "kejangBerulang"
        },
        {
          deskripsi: "Tamponade jantung atau pneumothorax dengan gangguan hemodinamik",
          model: "tamponadeJantung"
        }
      ]
    },
    {
      label: "ECG",
      detail: [
        {
          deskripsi: "Infark miokard dengan aritmia kompleks, gagal jantung kongestif dengan gangguan hemodinamik",
          model: "infarkMiokard"
        },
        {
          deskripsi: "Ventrikular takikardi atau ventrikular fibrilasi dengan gangguan hemodinamik",
          model: "ventrikularTakikardi"
        },
        {
          deskripsi: "Blok jantung komplit dengan gangguan hemodinamik",
          model: "blokJantungKomplit"
        }
      ]
    },
    {
      label: "NILAI LABORATORIUM",
      detail: [
        {
          deskripsi: "Kadar natrium serum < 110 mmol/L atau > 170 mmol/L",
          model: "kadarNatrium"
        },
        {
          deskripsi: "Kadar kalium serum < 2,0 mmol/L atau > 7,0 mmol/L",
          model: "kadarKalium"
        },
        {
          deskripsi: "PaO₂ < 50 mmHg pada oksigen ruangan",
          model: "paO2"
        },
        {
          deskripsi: "pH < 7,1 atau > 7,7 dengan oksigen ruangan",
          model: "ph"
        },
        {
          deskripsi: "Serum glukosa > 800 mg/dl",
          model: "serumGlukosa"
        },
        {
          deskripsi: "Serum kalsium > 15 mg/dl",
          model: "serumKalsium"
        },
        {
          deskripsi: "Kadar obat atau substansi kimia dalam darah telah melebihi dosis toksis yang mengganggu hemodinamik dan status neurologis",
          model: "kadarObat"
        }
      ]
    },
    {
      label: "NILAI RADIOLOGI",
      detail: [
        {
          deskripsi: "Gambaran CT Scan abnormal (Perdarahan cerebral, contusion atau perdarahan subarachnoid) dengan penurunan status mental",
          model: "ctScanAbnormal"
        },
        {
          deskripsi: "Ruptur viscera, bladder, liver, varices esofagus, perdarahan saluran cerna dengan gangguan hemodinamik",
          model: "rupturViscera"
        },
        {
          deskripsi: "Diseksi aorta aneurisma",
          model: "diseksiAorta"
        }
      ]
    },
    {
      label: "KEBUTUHAN MONITORING INTESIF PASCA PEMBEDAHAN SEDANG ATAU PEMBEDAHAN MAYOR",
      detail: [
        {
          model: "kebutuhanMonitoringIntesif"
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
