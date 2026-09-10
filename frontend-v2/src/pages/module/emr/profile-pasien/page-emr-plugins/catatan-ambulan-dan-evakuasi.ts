export function airway():any{
  return [
    {
      "label" : "Oropharyngeal Tube",
      "code" : "OT",
      "model" : "airway",
    },
    {
      "label" : "Endo Tracheal Tube",
      "model" : "airway",
      "code" : "ETT"
    },
    {
      "label" : "Nasoprangeal Tube",
      "model" : "airway",
      "code" : "NT"
    },
    {
      "label" : "Lain-lain",
      "model" : "airway",
      "code" : "LN"
    },
  ]
}

export function breathing():any{
  return [
    {
      "label" : "Nasal Canule",
      "model" : "breathing_1",
      "code" : "BNC",
      "optional" : "ketNasalCanule",
    },
    {
      "label" : "Simple Mask",
      "model" : "breathing_2",
      "code" : "BSM",
      "optional" : "ketSimpleMask",
    },
    {
      "label" : "Non Rebreathing Mask",
      "model" : "breathing_3",
      "code" : "BNRM",
      "optional" : "ketNRebMask",
    },
    {
      "label" : "Venturi Mask",
      "model" : "breathing_4",
      "code" : "BVM",
      "optional" : "ketVentMask",
    },
    {
      "label" : "Lain-lain",
      "model" : "breathing_5",
      "code" : "BLN",
    },
  ]
}

export function circulation():any{
  return [
    {
      "label" : "1.",
      "model" : "AVFD_1",
    },
    {
      "label" : "2.",
      "model" : "AVFD_2",
    },
    {
      "label" : "3.",
      "model" : "AVFD_3",
    },
  ]
}

export function exposure():any{
  return [
    {
      "label" : "Bebat tekan",
      "model" : "exBebatTekan",
      "code" : "BT",
    },
    {
      "label" : "Bidai",
      "model" : "exBidai",
      "code" : "BI",
    },
    {
      "label" : "Immobilasi Penuh",
      "model" : "exImmobilasi",
      "code" : "IP",
    },
    {
      "label" : "Cegah Hipotermia",
      "model" : "exCegahHipotermia",
      "code" : "CH",
    },
    {
      "label" : "Cervical collar",
      "model" : "exCervical",
      "code" : "CC",
    },
    {
      "label" : "Lain-lain",
      "model" : "exLain",
      "code" : "LN",
    },
  ]
}

export function peralatan():any{
  return [
    {
      "label" : "Monitor",
      "model" : "peralatanMon",
      "code" : "MR",
    },
    {
      "label" : "Infus Pump",
      "model" : "PeralatanInfus",
      "code" : "IP",
    },
    {
      "label" : "Syring Pump",
      "model" : "PeralatanSying",
      "code" : "SP",
    },
  ]
}
export function kulit():any{
  return [
    {
      "label" : "Dingin",
      "model" : "kondisiKulit",
    },
    {
      "label" : "Hangat",
      "model" : "kondisiKulit",
    },
    {
      "label" : "Kering",
      "model" : "kondisiKulit",
    },
    {
      "label" : "Basah",
      "model" : "kondisiKulit",
    },
  ]
}
