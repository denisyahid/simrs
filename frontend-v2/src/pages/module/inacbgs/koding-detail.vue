<template>
    <section>
        <ConfirmDialog group="positionDialog"></ConfirmDialog>
        <div class="form-layout is-stacked ">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>KODING</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="dark" raised icon="feather:file"
                                    :loading="isLoading" @click="resumeMedis()"> Resume Medis
                                </VButton>
                                <!-- <VButton type="button" rounded outlined color="warning" raised icon="feather:flag"
                                  @click="finalClaim()"> Final Klaim
                              </VButton>
                              <VButton type="button" rounded outlined color="info" raised icon="feather:printer"
                                  @click="claim_print()"> Cetak
                              </VButton> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <HeadPasien :registrasi="item.registrasi" :pasien="pasien" class="m-3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <VCard class="mt-2">
                <div class="columns is-multiline">
                    <!-- <div class="column is-3">
                      <VCardCustom :style="'padding:5px 25px'">
                          <div class="label-status primary">
                              <i aria-hidden="true" class="fas fa-circle"></i>
                              <span class="ml-1"> TAGIHAN</span>
                          </div>
                          <small class="text-bold-custom h-100">{{ H.formatRp(item.TOTAL, 'Rp.') }}</small>
                      </VCardCustom>
                  </div> -->
                    <div class="column is-12">
                        <form @submit.prevent="validateStep">
                            <div class="mobile-steps is-active">
                                <ul class="steps has-content-centered is-thin is-horizontal is-short">
                                    <li :class="[currentStep === 0 && 'is-active']" class="steps-segment">
                                        <span class="steps-marker"></span>
                                        <a href="#" class="steps-content" @click.prevent="
                                            currentStep >= 0 && scrollTo('#form-step-0', 800, { offset: -150 })
                                            ">
                                            <p class="step-number">Step 1</p>
                                        </a>
                                    </li>

                                    <li :class="[currentStep === 1 && 'is-active']" class="steps-segment">
                                        <span class="steps-marker"></span>
                                        <a href="#" class="steps-content" @click.prevent="
                                            currentStep >= 1 && scrollTo('#form-step-1', 800, { offset: -150 })
                                            ">
                                            <p class="step-number">Step 2</p>
                                        </a>
                                    </li>

                                    <li :class="[currentStep === 2 && 'is-active']" class="steps-segment">
                                        <span class="steps-marker"></span>
                                        <a href="#" class="steps-content" @click.prevent="
                                            currentStep >= 2 && scrollTo('#form-step-2', 800, { offset: -150 })
                                            ">
                                            <p class="step-number">Step 3</p>
                                        </a>
                                    </li>

                                    <li :class="[currentStep === 3 && 'is-active']" class="steps-segment">
                                        <span class="steps-marker"></span>
                                        <a href="#" class="steps-content" @click.prevent="
                                            currentStep >= 3 && scrollTo('#form-step-3', 800, { offset: -150 })
                                            ">
                                            <p class="step-number">Step 4</p>
                                        </a>
                                    </li>

                                    <li :class="[currentStep === 4 && 'is-active']" class="steps-segment">
                                        <span class="steps-marker"></span>
                                        <a href="#" class="steps-content" @click.prevent="
                                            currentStep >= 4 && scrollTo('#form-step-4', 800, { offset: -150 })
                                            ">
                                            <p class="step-number">Step 5</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="stepper-form">
                                <div class="form-sections-2">
                                    <VMessage color="primary" style="display: none !important">
                                        <div>
                                            <strong class="pr-1">Status:</strong>
                                            <span>{{ inacbg.inacbg_status }}</span>
                                        </div>

                                    </VMessage>
                                    <div v-if="currentStep >= 0" id="form-step-0" class="form-section is-active"
                                        style="display: none !important">
                                        <h3 class="form-section-title">
                                            <span> Info Peserta</span>
                                            <button type="button" class="help-button" tabindex="0"
                                                @keydown.space.prevent="
                                                    currentHelp === 0 ? (currentHelp = -1) : (currentHelp = 0)
                                                    "
                                                @click="currentHelp === 0 ? (currentHelp = -1) : (currentHelp = 0)">
                                                <i aria-hidden="true" class="iconify"
                                                    data-icon="feather:help-circle"></i>
                                            </button>
                                        </h3>

                                        <div class="form-section-inner">
                                            <div class="columns is-multiline  mb-5  ">
                                                <div class="column is-3 mb-5-min">
                                                    <VField>
                                                        <VLabel>Jaminan/Cara Bayar</VLabel>
                                                        <VControl icon="fas fa-hospital" fullwidth
                                                            class="prime-auto-select" expanded>
                                                            <Dropdown v-model="inacbg.payor_cd" :options="d_Jaminan"
                                                                :optionLabel="'nama'" placeholder="Jaminan/Cara Bayar"
                                                                style="width: 100%;" :filter="true" showClear />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3 mb-5-min">
                                                    <VField>
                                                        <VLabel>No SEP</VLabel>
                                                        <VControl>
                                                            <VInput type="text" placeholder="No SEP"
                                                                v-model="inacbg.nomor_sep" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3 mb-5-min">
                                                    <VField label="No Peserta">
                                                        <VControl>
                                                            <VInput type="text" placeholder="No Peserta"
                                                                v-model="inacbg.nomor_kartu" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3  mb-5-min ">
                                                    <VField>
                                                        <VLabel>COB</VLabel>
                                                        <VControl icon="fas fa-archway" fullwidth
                                                            class="prime-auto-select" expanded>
                                                            <Dropdown v-model="inacbg.cob" :options="d_COB"
                                                                :optionLabel="'nama'" placeholder="COB"
                                                                style="width: 100%;" :filter="true" showClear />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                            <VField>
                                                <VControl>
                                                    <button type="button" class="input-button">
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:plus"></i>
                                                        <span>Profile peserta</span>
                                                    </button>
                                                </VControl>
                                            </VField>

                                            <div class="fieldset">
                                                <div class="columns is-multiline">
                                                    <div class="column is-2 mt-5">
                                                        <VField>
                                                            <VLabel>Jenis Rawat</VLabel>
                                                            <VControl>
                                                                <VSwitchBlock v-model="inacbg.jenis_rawat"
                                                                    label="Rawat Inap" color="danger" />
                                                                <!-- <VSwitchSegment color="primary" label-true="Rawat Inap"
                                                                  v-model="inacbg.jenis_rawat" label-false="Rawat Jalan" /> -->
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4 mt-5">
                                                        <VField>
                                                            <VLabel> </VLabel>
                                                            <VControl>
                                                                <VCheckbox v-model="inacbg.eksekutif">Kelas Eksekutif
                                                                </VCheckbox>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6  mt-5 ">
                                                        <VField>
                                                            <VLabel>Kelas Hak</VLabel>
                                                            <VControl icon="fas fa-arrow-up" class="prime-auto-select"
                                                                expanded>
                                                                <Dropdown v-model="inacbg.kelas_rawat"
                                                                    :options="d_KelasRawat" :optionLabel="'nama'"
                                                                    placeholder="Kelas Hak" style="width: 100%;"
                                                                    :filter="true" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3 mb-5-min">

                                                        <VDatePicker v-model="inacbg.tgl_masuk" mode="dateTime"
                                                            style="width: 100%;">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VLabel>Tanggal Masuk</VLabel>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            placeholder="Tanggal Masuk"
                                                                            v-on="inputEvents"
                                                                            :disabled="inacbg.DETAIL" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-3 mb-5-min">
                                                        <VDatePicker v-model="inacbg.tgl_pulang" mode="dateTime"
                                                            style="width: 100%;">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VLabel>Tanggal Pulang</VLabel>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            placeholder="Tanggal Pulang"
                                                                            v-on="inputEvents"
                                                                            :disabled="inacbg.DETAIL" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="Umur">
                                                            <VControl>
                                                                <VInput type="text" placeholder="Umur"
                                                                    v-model="inacbg.umur_tahun" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6  mt-5">
                                                        <div class="form-section-inner">
                                                            <VField>
                                                                <VControl>
                                                                    <button type="button" class="input-button"
                                                                        @click="showModalpersalinan()">
                                                                        <div v-if="inacbg.usia_kehamilan > 0">
                                                                            <i class="lnil lnil-checkmark"
                                                                                aria-hidden="true"></i>
                                                                        </div>
                                                                        <div v-else>
                                                                            <i aria-hidden="true" class="iconify"
                                                                                data-icon="feather:plus"></i>
                                                                        </div>



                                                                        <span>Persalinan </span>
                                                                    </button>
                                                                </VControl>
                                                            </VField>

                                                        </div>
                                                    </div>

                                                    <div class="column is-6 ">
                                                        <VField label="Berat Lahir (gram)">
                                                            <VControl>
                                                                <VInput type="number" placeholder="Berat Lahir"
                                                                    v-model="inacbg.birth_weight" />
                                                            </VControl>
                                                        </VField>
                                                    </div>

                                                </div>
                                                <!-- <div class="fieldset-separator">Persalinan</div> -->


                                                <div class="fieldset-separator">Data Klinis</div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-6 ">
                                                        <VField>
                                                            <VLabel>Tekanan Darah (mmHg)</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Sistole "
                                                                    v-model="inacbg.sistole" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VField>
                                                            <VLabel>.</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Diastole "
                                                                    v-model="inacbg.diastole" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>

                                                <div class="columns is-multiline">
                                                    <div class="column is-2 ">
                                                        <div style="margin-top:35px">
                                                            <span>Dializer</span>
                                                        </div>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel> </VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Dializer "
                                                                    v-model="inacbg.dializer_single_use" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VButton type="button" style="margin-top:30px" rounded outlined
                                                            color="danger" raised icon="feather:save"
                                                            @click="simpanDializer()" :loading="isLoading"> Simpan
                                                        </VButton>
                                                    </div>
                                                </div>

                                                <div class="fieldset-separator">Apgar Score</div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-2 ">
                                                        <div style="margin-top:20px">
                                                            <span>Menit 1</span>
                                                        </div>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Appearance</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Appearance "
                                                                    v-model="inacbg.appearance" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Pulse</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Pulse "
                                                                    v-model="inacbg.pulse" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Grimace</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Grimace "
                                                                    v-model="inacbg.grimace" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Activity</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Activity "
                                                                    v-model="inacbg.activity" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Respiration</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Respiration "
                                                                    v-model="inacbg.respiration" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-2 ">
                                                        <div style="margin-top:20px">
                                                            <span>Menit 5</span>
                                                        </div>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Appearance</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Appearance "
                                                                    v-model="inacbg.appearance5" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Pulse</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Pulse "
                                                                    v-model="inacbg.pulse5" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Grimace</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Grimace "
                                                                    v-model="inacbg.grimace5" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Activity</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Activity "
                                                                    v-model="inacbg.activity5" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel>Respiration</VLabel>
                                                            <VControl>
                                                                <VInput type="number" placeholder="Respiration "
                                                                    v-model="inacbg.respiration5" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-10">

                                                    </div>
                                                    <div class="column is-2">
                                                        <VButton type="button" rounded outlined color="danger" raised
                                                            icon="feather:save" @click="simpanApgar()"
                                                            :loading="isLoading"> Simpan
                                                        </VButton>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="form-section-output">
                                          <div class="output">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:archive"></i>
                                              <span>15 i9 Laptops - b2</span>
                                              <div class="action">
                                                  <VIconButton icon="feather:trash-2" />
                                              </div>
                                          </div>
                                      </div> -->
                                    </div>

                                    <Transition name="fade-slow" style="display: none !important">
                                        <div v-if="currentStep >= 1" id="form-step-1" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Data Pendaftaran </span>
                                                <button type="button" class="help-button" @keydown.space.prevent="
                                                    currentHelp === 1 ? (currentHelp = -1) : (currentHelp = 1)
                                                    "
                                                    @click="currentHelp === 1 ? (currentHelp = -1) : (currentHelp = 1)">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:help-circle"></i>
                                                </button>
                                            </h3>
                                            <div class="form-section-inner">
                                                <div class="columns is-multiline">

                                                    <div class="column is-6 ">
                                                        <VField label="Cara Masuk">
                                                            <VControl icon="fas fa-arrow-left" class="prime-auto-select"
                                                                expanded>
                                                                <Dropdown v-model="inacbg.cara_masuk"
                                                                    :options="d_CaraMasuk" :optionLabel="'nama'"
                                                                    placeholder="Cara Masuk" style="width: 100%;"
                                                                    :filter="true" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="Cara Pulang">
                                                            <VControl icon="fas fa-arrow-right"
                                                                class="prime-auto-select" expanded>
                                                                <Dropdown v-model="inacbg.discharge_status"
                                                                    :options="d_CaraPulang" :optionLabel="'nama'"
                                                                    placeholder="Cara Pulang" style="width: 100%;"
                                                                    :filter="true" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="LOS">
                                                            <VControl>
                                                                <VInput type="text" placeholder="LOS"
                                                                    v-model="inacbg.los" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="ADL Score Sub Acute">
                                                            <VControl>
                                                                <VInput type="text" placeholder="ADL Score Sub Acute"
                                                                    v-model="inacbg.adl_sub_acute" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="ADL Score Chronic ">
                                                            <VControl>
                                                                <VInput type="text" placeholder="ADL Score Chronic"
                                                                    v-model="inacbg.adl_chronic" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="DPJP"
                                                            class="is-rounded-select_Z  is-autocomplete-select"
                                                            v-slot="{ id }">
                                                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                                                <AutoComplete v-model="inacbg.dokter"
                                                                    :suggestions="d_Dokter"
                                                                    @complete="fetchDokter($event)"
                                                                    :optionLabel="'namalengkap'" :dropdown="true"
                                                                    :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'"
                                                                    :field="'namalengkap'"
                                                                    placeholder="ketik nama Dokter" />


                                                                <!-- <Dropdown v-model="inacbg.dpjp" :options="d_DPJP"
                                                                  :optionLabel="'name'" placeholder="DPJP"
                                                                  style="width: 100%;" :filter="true" showClear /> -->
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6 ">
                                                        <VField label="Jenis Tarif">
                                                            <VControl icon="fas fa-calculator">
                                                                <Dropdown v-model="inacbg.kodetarif"
                                                                    :options="d_JenisTarifAsal" :optionLabel="'nama'"
                                                                    placeholder="Jenis Tarif" style="width: 100%;"
                                                                    :filter="true" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 class="form-section-title mt-4 is-flex">
                                                <span>Pasien TB </span>
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="inacbg.chkpasientb" label="Ya"
                                                            color="primary" class="p-0 pl-5" />
                                                    </VControl>
                                                </VField>
                                            </h3>
                                            <div class="form-section-inner" v-if="inacbg.chkpasientb">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6">
                                                        <VField label="Nomor Register SITB">
                                                            <VControl>
                                                                <VInput type="text" placeholder="Nomor Register SITB"
                                                                    v-model="inacbg.nomorsitb" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VButtons>
                                                            <VButton color="primary"
                                                                style="margin-top: 1.7rem !important"
                                                                @click="validasisitb" :loading="isSITB"> Validasi
                                                            </VButton>
                                                        </VButtons>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow" style="display: none !important">
                                        <div v-if="currentStep >= 2" id="form-step-2" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Tarif Rumah Sakit : {{ H.formatRp(inacbg.totalmappingtarif, 'Rp.')
                                                    }}</span>
                                                <button type="button" class="help-button" @keydown.space.prevent="
                                                    currentHelp === 2 ? (currentHelp = -1) : (currentHelp = 2)
                                                    "
                                                    @click="currentHelp === 2 ? (currentHelp = -1) : (currentHelp = 2)">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:help-circle"></i>
                                                </button>
                                            </h3>
                                            <div class="form-section-inner">
                                                <VField>
                                                    <VControl>
                                                        <button type="button" class="input-button">
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:plus"></i>
                                                            <span>Input Tarif </span>
                                                        </button>
                                                    </VControl>
                                                </VField>

                                                <div class="fieldset  is-horizontal">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VLabel>Prosedur Non Bedah </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber
                                                                        v-model="inacbg.tarif_rs.prosedur_non_bedah"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Tenaga Ahli</VLabel>

                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.tenaga_ahli"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Radiologi </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.radiologi"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Rehabilitasi </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.rehabilitasi"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Obat </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.obat"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2"
                                                                        @keydown="changeTarif($event)" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Alkes </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.alkes"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VLabel>Prosedur Bedah </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber
                                                                        v-model="inacbg.tarif_rs.prosedur_bedah"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Keperawatan </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.keperawatan"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Laboratorium </VLabel>
                                                                <VControl icon="fas fa-calculator">

                                                                    <InputNumber v-model="inacbg.tarif_rs.laboratorium"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Kamar / Akomodasi </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.kamar"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Obat Kronis </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.obat_kronis"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>BMHP </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.bmhp"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VLabel>Konsultasi </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.konsultasi"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Penunjang </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.penunjang"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Pelayanan Darah </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber
                                                                        v-model="inacbg.tarif_rs.pelayanan_darah"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Rawat Intensif </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber
                                                                        v-model="inacbg.tarif_rs.rawat_intensif"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Obat Kemoterapi </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber
                                                                        v-model="inacbg.tarif_rs.obat_kemoterapi"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />

                                                                </VControl>
                                                            </VField>
                                                            <VField>
                                                                <VLabel>Sewa Alat </VLabel>
                                                                <VControl icon="fas fa-calculator">
                                                                    <InputNumber v-model="inacbg.tarif_rs.sewa_alat"
                                                                        locale="id-ID" inputId="minmaxfraction"
                                                                        :minFractionDigits="2" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Diagnosa Dokter</span>
                                                <button type="button" class="help-button" @keydown.space.prevent="
                                                    currentHelp === 3 ? (currentHelp = -1) : (currentHelp = 3)
                                                    "
                                                    @click="currentHelp === 3 ? (currentHelp = -1) : (currentHelp = 3)">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:help-circle"></i>
                                                </button>
                                            </h3>

                                            <div class="column is-12">
                                                <VField>
                                                    <h3 class="form-section-title">
                                                        <span>Diagnosa Masuk</span>
                                                    </h3>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="2" v-model="input.TADiagnosa" disabled></VTextarea>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <h3 class="form-section-title">
                                                        <span>Diagnosa Keluar</span>
                                                    </h3>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="2" v-model="input.TADiagnosaKeluar" disabled></VTextarea>
                                                </VField>
                                            </div>

                                            <!-- <div class="form-section-output" v-if="dataSourceXDokter.length > 0">
                                                <div class="output c-title" v-for="(items, index) in dataSourceXDokter"
                                                    :key="index" style="height: 40px;">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:archive"></i>
                                                    <span v-if="items.keterangan">Diagnosa dokter: {{ items.keterangan
                                                        }}</span>
                                                    <span> &nbsp ICD10: {{ items.kddiagnosa + " - " + items.namadiagnosa
                                                        }}</span>
                                                    <span class="txt-ina">{{ items.jenisdiagnosa }}</span>
                                                </div>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceXDokter.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-10 masih kosong
                                                    </div>
                                                </VCard>
                                            </div>

                                            <div class="form-section-output" v-if="dataSourceIXDokter.length > 0"
                                                style="margin-top: 50px;">
                                                <div class="output c-title" v-for="(items, index) in dataSourceIXDokter"
                                                    :key="index" style="height: 40px;">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:archive"></i>
                                                    <span v-if="items.keterangantindakan">Diagnosa dokter: {{
                                                        items.keterangantindakan }} </span>
                                                    <span>&nbsp ICD 9: {{ items.kddiagnosatindakan + " - " +
                                                        items.namadiagnosatindakan
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceIXDokter.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-9-CM masih kosong
                                                    </div>
                                                </VCard>
                                            </div> -->
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Tindakan</span>
                                            </h3>

                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="12" v-model="input.tindakan" disabled></VTextarea>
                                                </VField>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Laboratorium</span>
                                            </h3>

                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="2" v-model="input.laboratoriumAja" disabled>
                                                    </VTextarea>
                                                </VField>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Radiologi</span>
                                            </h3>

                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="2" v-model="input.radiologiAja" disabled></VTextarea>
                                                </VField>
                                            </div>
                                        </div>
                                    </Transition>

                                    <!-- <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Resep</span>
                                            </h3>

                                            <div class="column is-12">
                                                <VField>
                                                    <VTextarea rows="2" v-model="input.resepObat" disabled></VTextarea>
                                                </VField>
                                            </div>
                                        </div>
                                    </Transition> -->

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Coding Rekam Medis</span>
                                                <button type="button" class="help-button" @keydown.space.prevent="
                                                    currentHelp === 3 ? (currentHelp = -1) : (currentHelp = 3)
                                                    "
                                                    @click="currentHelp === 3 ? (currentHelp = -1) : (currentHelp = 3)">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:help-circle"></i>
                                                </button>
                                            </h3>

                                            <div class="form-section-inner" v-if="pasien?.iddepartemen === 16">
                                                <VField style="display: none !important">
                                                    <VControl>
                                                        <button type="button" class="input-button" @click="showMOI()">
                                                            <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                                            <span>Diagnosa (ICD-10): </span>
                                                        </button>
                                                    </VControl>
                                                </VField>
                                                <VField label="Diagnosis Masuk" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                                        <AutoComplete v-model="item.diagnosaMasuk" :suggestions="d_Diagnosa"
                                                            @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="ketik untuk mencari " />

                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceXMasuk.length > 0">
                                                <div class="output c-title" v-for="(items, index) in dataSourceXMasuk" :key="index">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:archive"></i>
                                                    <span v-if="items.keterangan">Diagnosa dokter: {{ items.keterangan }}</span>
                                                    <span> &nbsp ICD10: {{ items.kddiagnosa + " - " + items.namadiagnosa }}</span>
                                                    <!-- <span class="txt-ina">{{ items.jenisdiagnosa }}</span> -->
                                                    <div class="action is-pulled-right" v-if="!item.DETAIL && items.jenisdiagnosa == 'Secondary'">
                                                        <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                        <!-- <VButton color="success" light rounded outlined @click="setPrimary2(item)" style="display: none !important">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                    </div>
                                                    <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                        <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                        <!-- <VButton color="info" light rounded outlined @click="rencanaMutasi(item)">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                    </div>
                                                    <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                        <VIconButton icon="feather:trash-2" @click="hapusItems(items)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceXMasuk.length == 0 && pasien?.iddepartemen === 16">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa Masuk masih kosong
                                                    </div>
                                                </VCard>
                                            </div>

                                            <div class="form-section-inner" style="margin-top: 10px;">
                                                <VField style="display: none !important">
                                                <VControl>
                                                    <button type="button" class="input-button" @click="showModal()">
                                                        <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                                        <span>Diagnosa (ICD-10): </span>
                                                    </button>
                                                </VControl>
                                            </VField>
                                            <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                                    <AutoComplete v-model="item.diagnosa10" :suggestions="d_Diagnosa"
                                                        @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari " />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="form-section-output" v-if="dataSourceX.length > 0">
                                            <div class="output c-title" v-for="(items, index) in dataSourceX" :key="index">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:archive"></i>
                                                <span v-if="items.keterangan">Diagnosa dokter: {{ items.keterangan }}</span>
                                                <span> &nbsp ICD10: {{ items.kddiagnosa + " - " + items.namadiagnosa }}</span>
                                                <span class="txt-ina">{{ items.jenisdiagnosa }}</span>
                                                <div class="action is-pulled-right" v-if="!item.DETAIL && items.jenisdiagnosa == 'Secondary'">
                                                    <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                    <VButton color="success" light rounded outlined @click="setPrimary(item)" style="display: none !important">Set Primary&emsp;&emsp;&emsp;</VButton>
                                                </div>
                                                <div class="action is-pulled-right" style="margin-left:-0;" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:edit-2" @click="editItems(items)" />
                                                    <!-- <VButton color="info" light rounded outlined @click="rencanaMutasi(item)">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                </div>
                                                <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:trash-2" @click="hapusItems(items)" />
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-section-output" v-if="dataSourceX.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-10 masih kosong
                                                    </div>
                                                </VCard>
                                            </div>
                                            <div class="form-section-inner mt-5">
                                                <VField label="Diagnosis ICD 9 " class="is-rounded-select  is-autocomplete-select"
                                                    v-slot="{ id }">
                                                    <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                                        <AutoComplete v-model="item.diagnosa9" :suggestions="d_Diagnosa9"
                                                            @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="ketik untuk mencari " />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceIX.length > 0">
                                                <div class="output c-title" v-for="(items, index) in dataSourceIX"
                                                    :key="index">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:archive"></i>
                                                    <span v-if="items.keterangantindakan">Diagnosa dokter: {{
                                                        items.keterangantindakan }} </span>
                                                    <span>&nbsp ICD 9: {{ items.kddiagnosatindakan + " - " +
                                                        items.namadiagnosatindakan
                                                        }}</span>

                                                    <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                        <VIconButton icon="feather:edit-2"
                                                            @click="editItemsIX(items)" />
                                                    </div>
                                                    <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                        <VIconButton icon="feather:trash-2"
                                                            @click="hapusItemsIX(items)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceIX.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-9-CM masih kosong
                                                    </div>
                                                </VCard>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>MOI</span>
                                            </h3>
                                            <div class="form-section is-active">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea class="textarea is-rounded" v-model="item.moi"
                                                            rows="10" placeholder="Kronologi Kecelakaan"
                                                            autocomplete="off" autocapitalize="off" spellcheck="true"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <VButton type="button" rounded outlined color="primary" raised
                                                icon="feather:save" style="margin-top: 10px; display: none !important"
                                                :loading="isLoading" @click="saveMOI()"> Simpan
                                            </VButton>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div class="form-section is-active">
                                        <div class="form-section-inner">
                                                <VField style="display: none !important">
                                                    <VControl>
                                                        <button type="button" class="input-button" @click="showMOI()">
                                                            <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                                            <span>Diagnosa (ICD-10): </span>
                                                        </button>
                                                    </VControl>
                                                </VField>
                                            <VField label="Diagnosis ICD 10 MOI" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                                    <AutoComplete v-model="item.diagnosa10MOI" :suggestions="d_Diagnosa"
                                                        @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari " />

                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="form-section-output" v-if="dataSourceXMOI.length > 0">
                                            <div class="output c-title" v-for="(items, index) in dataSourceXMOI" :key="index">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:archive"></i>
                                                <span v-if="items.keterangan">Diagnosa dokter: {{ items.keterangan }}</span>
                                                <span> &nbsp ICD10: {{ items.kddiagnosa + " - " + items.namadiagnosa }}</span>
                                                <!-- <span class="txt-ina">{{ items.jenisdiagnosa }}</span> -->
                                                <div class="action is-pulled-right" v-if="!item.DETAIL && items.jenisdiagnosa == 'Secondary'">
                                                    <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                    <!-- <VButton color="success" light rounded outlined @click="setPrimary2(item)" style="display: none !important">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                </div>
                                                <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                    <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                    <!-- <VButton color="info" light rounded outlined @click="rencanaMutasi(item)">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                </div>
                                                <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:trash-2" @click="hapusItems(items)" />
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-section-output" v-if="dataSourceXMOI.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-10 MOI masih kosong
                                                    </div>
                                                </VCard>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div class="form-section is-active">
                                        <div class="form-section-inner">
                                                <VField style="display: none !important">
                                                    <VControl>
                                                        <button type="button" class="input-button" @click="showModalKematian()">
                                                            <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                                            <span>Diagnosa (ICD-10): </span>
                                                        </button>
                                                    </VControl>
                                                </VField>
                                            <VField label="Diagnosis ICD 10 Sebab Kematian" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                                    <AutoComplete v-model="item.diagnosa10Kematian" :suggestions="d_Diagnosa"
                                                        @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari " />

                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="form-section-output" v-if="dataSourceXKematian.length > 0">
                                            <div class="output c-title" v-for="(items, index) in dataSourceXKematian" :key="index">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:archive"></i>
                                                <span v-if="items.keterangan">Diagnosa dokter: {{ items.keterangan }}</span>
                                                <span> &nbsp ICD10: {{ items.kddiagnosa + " - " + items.namadiagnosa }}</span>
                                                <!-- <span class="txt-ina">{{ items.jenisdiagnosa }}</span> -->
                                                <div class="action is-pulled-right" v-if="!item.DETAIL && items.jenisdiagnosa == 'Secondary'">
                                                    <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                    <!-- <VButton color="success" light rounded outlined @click="setPrimary2(item)" style="display: none !important">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                </div>
                                                <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                    <!-- <VIconButton icon="feather:edit-2" @click="editItems(items)" /> -->
                                                    <!-- <VButton color="info" light rounded outlined @click="rencanaMutasi(item)">Set Primary&emsp;&emsp;&emsp;</VButton> -->
                                                </div>
                                                <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                    <VIconButton icon="feather:trash-2" @click="hapusItems(items)" />
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-section-output" v-if="dataSourceXKematian.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-10 Sebab Kematian masih kosong
                                                    </div>
                                                </VCard>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow">
                                        <div v-if="currentStep >= 3" id="form-step-3" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Coding ICD-O</span>
                                            </h3>
                                            <div class="form-section-inner mt-5">
                                                <VField>
                                                    <VControl>
                                                        <button type="button" class="input-button"
                                                            @click="showModalO()">
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:plus"></i>
                                                            <span>Diagnosa (ICD-O):</span>
                                                        </button>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceO.length > 0">
                                                <div class="output c-title" v-for="(items, index) in dataSourceO"
                                                    :key="index">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:archive"></i>
                                                    <span>&nbsp ICD-O: {{ items.kddiagnosamorfologi + " - " +
                                                        items.diagnosamorfologi
                                                        }}</span>

                                                    <div class="action is-pulled-right" v-if="!item.DETAIL">
                                                        <VIconButton icon="feather:edit-2" @click="editItemsO(items)" />
                                                    </div>
                                                    <div class="action" style="margin-left:-0;" v-if="!item.DETAIL">
                                                        <VIconButton icon="feather:trash-2"
                                                            @click="hapusItemsO(items)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section-output" v-if="dataSourceO.length == 0">
                                                <VCard>
                                                    <div class="text-center">
                                                        Diagnosa ICD-O masih kosong
                                                    </div>
                                                </VCard>
                                            </div>
                                        </div>
                                    </Transition>

                                    <Transition name="fade-slow" style="display: none !important">
                                        <div v-if="currentStep >= 4" id="form-step-4" class="form-section is-active">
                                            <h3 class="form-section-title">
                                                <span>Hasil Grouper E-Klaim </span>
                                                <button type="button" class="help-button" @keydown.space.prevent="
                                                    currentHelp === 4 ? (currentHelp = -1) : (currentHelp = 4)
                                                    "
                                                    @click="currentHelp === 4 ? (currentHelp = -1) : (currentHelp = 4)">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:help-circle"></i>
                                                </button>
                                            </h3>
                                            <div class="form-section-inner">
                                                <div class="validation-box" style="display:block">

                                                    <div class="columns is-multiline"
                                                        v-if="resGrouper && resGrouper.response">
                                                        <div class="column is-6">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status "
                                                                    :class="resGrouper.response
                                                                        && resGrouper.response.cbg.tariff ? 'info' : 'danger'">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">DESKRIPSI</span>
                                                                </div>
                                                                <small class="text-bold-custom text-elipsis">{{
                                                                    resGrouper.response ?
                                                                        resGrouper.response.cbg.description : '-' }}</small>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">GROUP</span>
                                                                </div>
                                                                <small class="text-bold-custom">{{ resGrouper.response ?
                                                                    H.formatRp(resGrouper.response.cbg.base_tariff
                                                                        , 'Rp.') : 'Rp. 0'
                                                                    }}</small>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">SUB ACCUTE</span>
                                                                </div>
                                                                <small class="text-bold-custom">{{ resGrouper.response ?
                                                                    H.formatRp((resGrouper.response.sub_acute ?
                                                                        resGrouper.response.sub_acute.tariff : 0), 'Rp.') :
                                                                    'Rp. 0' }}</small>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">CHRONIC</span>
                                                                </div>
                                                                <small class="text-bold-custom">{{ resGrouper.response ?
                                                                    H.formatRp((resGrouper.response.chronic ?
                                                                        resGrouper.response.chronic.tariff : 0), 'Rp.') :
                                                                    'Rp.0' }}</small>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">SPECIAL PROCEDURE</span>
                                                                </div>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-6">
                                                                        <VPlaceloadText lines="1"
                                                                            v-show="inacbg.specialprocedureLoad" />
                                                                        <small class="text-bold-custom"
                                                                            v-show="!inacbg.specialprocedureLoad">
                                                                            {{ H.formatRp(inacbg.specialprocedureValue,
                                                                                'Rp.') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="column is-6 mt-4-min">
                                                                        <VField
                                                                            class="is-rounded-select is-autocomplete-select h-100"
                                                                            v-slot="{ id }">
                                                                            <VControl icon="fas fa-list" fullwidth
                                                                                class="prime-auto-select">
                                                                                <Dropdown
                                                                                    v-model="inacbg.specialprocedure"
                                                                                    :options="d_specialprocedure"
                                                                                    :disabled="d_specialprocedure.length == 0"
                                                                                    :optionLabel="'description'"
                                                                                    @change="hitungSpecialCMG($event, 'specialprocedure')"
                                                                                    class="is-rounded"
                                                                                    placeholder="Special Procedure "
                                                                                    style="width: 100%;" :filter="true"
                                                                                    showClear />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">SPECIAL PROSTHESIS</span>
                                                                </div>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-6">
                                                                        <VPlaceloadText lines="1"
                                                                            v-show="inacbg.specialprosthesisLoad" />
                                                                        <small class="text-bold-custom"
                                                                            v-show="!inacbg.specialprosthesisLoad">
                                                                            {{ H.formatRp(inacbg.specialprosthesisValue,
                                                                                'Rp.') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="column is-6 mt-4-min">
                                                                        <VField
                                                                            class="is-rounded-select is-autocomplete-select h-100"
                                                                            v-slot="{ id }">
                                                                            <VControl icon="fas fa-list" fullwidth
                                                                                class="prime-auto-select">
                                                                                <Dropdown
                                                                                    v-model="inacbg.specialprosthesis"
                                                                                    :options="d_specialprosthesis"
                                                                                    :disabled="d_specialprosthesis.length == 0"
                                                                                    :optionLabel="'description'"
                                                                                    @change="hitungSpecialCMG($event, 'specialprosthesis')"
                                                                                    class="is-rounded"
                                                                                    placeholder="Special Prosthesis "
                                                                                    style="width: 100%;" :filter="true"
                                                                                    showClear />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">SPECIAL INVESTIGATION </span>
                                                                </div>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-6">
                                                                        <VPlaceloadText lines="1"
                                                                            v-show="inacbg.specialinvestigationLoad" />
                                                                        <small class="text-bold-custom"
                                                                            v-show="!inacbg.specialinvestigationLoad">
                                                                            {{
                                                                                H.formatRp(inacbg.specialinvestigationLValue,
                                                                                    'Rp.') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="column is-6 mt-4-min">
                                                                        <VField
                                                                            class="is-rounded-select is-autocomplete-select h-100"
                                                                            v-slot="{ id }">
                                                                            <VControl icon="fas fa-list" fullwidth
                                                                                class="prime-auto-select">
                                                                                <Dropdown
                                                                                    v-model="inacbg.specialinvestigation"
                                                                                    :options="d_specialinvestigation"
                                                                                    :disabled="d_specialinvestigation.length == 0"
                                                                                    :optionLabel="'description'"
                                                                                    @change="hitungSpecialCMG($event, 'specialinvestigation')"
                                                                                    class="is-rounded"
                                                                                    placeholder="Special Investigation "
                                                                                    style="width: 100%;" :filter="true"
                                                                                    showClear />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VCardCustom :style="'padding:5px 25px'">
                                                                <div class="label-status info">
                                                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                                                    <span class="ml-1">SPECIAL DRUG</span>
                                                                </div>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-6">
                                                                        <VPlaceloadText lines="1"
                                                                            v-show="inacbg.specialdrugLoad" />
                                                                        <small class="text-bold-custom"
                                                                            v-show="!inacbg.specialdrugLoad">
                                                                            {{ H.formatRp(inacbg.specialdrugValue,
                                                                                'Rp.') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="column is-6 mt-4-min">
                                                                        <VField
                                                                            class="is-rounded-select is-autocomplete-select h-100"
                                                                            v-slot="{ id }">
                                                                            <VControl icon="fas fa-list" fullwidth
                                                                                class="prime-auto-select">
                                                                                <Dropdown v-model="inacbg.specialdrug"
                                                                                    :options="d_specialdrug"
                                                                                    :disabled="d_specialdrug.length == 0"
                                                                                    :optionLabel="'description'"
                                                                                    @change="hitungSpecialCMG($event, 'specialdrug')"
                                                                                    class="is-rounded"
                                                                                    placeholder="Special Drug "
                                                                                    style="width: 100%;" :filter="true"
                                                                                    showClear />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </VCardCustom>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VButton type="button" rounded outlined color="danger"
                                                                raised icon="feather:arrow-down" class="is-pulled-right"
                                                                :loading="isLoadingBatal" @click="batalTopUp()"> Batal
                                                                Top Up
                                                            </VButton>
                                                        </div>
                                                        <div class="column is-12">
                                                            <TStatusPojokKanan :title="'Status DC Kemkes'"
                                                                color="is-danger"
                                                                :subtitle="inacbg.kemkes_dc_status == null ? 'Klaim belum terkirim ke Pusat Data Kementerian Kesehatan' : 'Klaim sudah terkirim ke Pusat Data Kementerian Kesehatan'"
                                                                class="inbox-widget-2" />
                                                        </div>
                                                        <div class="column is-12">
                                                            <VCardCustom custom="card-green">
                                                                <span class="fee">
                                                                    TOTAL : {{
                                                                        H.formatRp(isNaN(inacbg.TOTALKLAIM) ? 0 :
                                                                            inacbg.TOTALKLAIM,
                                                                            'Rp.') }}
                                                                </span>
                                                            </VCardCustom>
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        <VCard>
                                                            <div class="text-center">
                                                                Belum di GROUPER
                                                            </div>
                                                        </VCard>
                                                    </div>
                                                    <div class="box-illustration"
                                                        style="position:absolute;right:10%;bottom: 3%;">
                                                        <img src="/@src/assets/illustrations/plants/1.svg" alt="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>

                                    <div class="navigation-buttons">
                                        <div class="buttons is-right">
                                            <VButton v-if="resGrouper && resGrouper.response" icon="feather:printer"
                                                @click="claim_print()" type="button" color="info" bold outlined
                                                :loading="isCetak" tabindex="0">
                                                Cetak
                                            </VButton>

                                            <VButton v-if="resGrouper && resGrouper.response" icon="feather:flag"
                                                @click="finalClaim()" type="button" color="primary" bold
                                                :loading="isFinal" tabindex="0">
                                                Final Klaim
                                            </VButton>
                                            <VButton v-if="resGrouper && resGrouper.response" icon="feather:edit"
                                                @click="reeditClaim()" type="button" color="warning" bold
                                                :loading="isReedit" tabindex="0">
                                                Edit Ulang Klaim
                                            </VButton>
                                            <VButton v-if="resGrouper && resGrouper.response" icon="feather:send"
                                                @click="kirimClaimDataCenter()" type="button" color="info" bold outlined
                                                :loading="isDC" tabindex="0">
                                                Kirim Klaim Online
                                            </VButton>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-stepper" style="display: none !important">
                                    <ul v-if="currentHelp === -1" class="steps is-vertical is-thin is-short">
                                        <li id="step-segment-0" :class="[currentStep === 0 && 'is-active']"
                                            class="steps-segment" tabindex="0" @keydown.space.prevent="
                                                currentStep >= 0 && scrollTo('#form-step-0', 800, { offset: -20 })
                                                " @click.prevent="
                                                    currentStep >= 0 && scrollTo('#form-step-0', 800, { offset: -20 })
                                                    ">
                                            <a href="#" class="steps-marker"></a>
                                            <div class="steps-content">
                                                <p class="step-number">STEP 1</p>
                                                <p class="step-info">Info Peserta</p>
                                            </div>
                                        </li>
                                        <li id="step-segment-1" :class="[currentStep === 1 && 'is-active']"
                                            class="steps-segment" tabindex="0" @keydown.space.prevent="
                                                currentStep >= 1 && scrollTo('#form-step-1', 800, { offset: -20 })
                                                " @click.prevent="
                                                    currentStep >= 1 && scrollTo('#form-step-1', 800, { offset: -20 })
                                                    ">
                                            <a href="#" class="steps-marker"></a>
                                            <div class="steps-content">
                                                <p class="step-number">STEP 2</p>
                                                <p class="step-info">Pendaftaran</p>
                                            </div>
                                        </li>
                                        <li id="step-segment-2" :class="[currentStep === 2 && 'is-active']"
                                            class="steps-segment" tabindex="0" @keydown.space.prevent="
                                                currentStep >= 2 && scrollTo('#form-step-2', 800, { offset: -20 })
                                                " @click.prevent="
                                                    currentStep >= 2 && scrollTo('#form-step-2', 800, { offset: -20 })
                                                    ">
                                            <a href="#" class="steps-marker"></a>
                                            <div class="steps-content">
                                                <p class="step-number">STEP 3</p>
                                                <p class="step-info">Tarif RS </p>
                                            </div>
                                        </li>
                                        <li id="step-segment-3" :class="[currentStep === 3 && 'is-active']"
                                            class="steps-segment" tabindex="0" @keydown.space.prevent="
                                                currentStep >= 3 && scrollTo('#form-step-3', 800, { offset: -20 })
                                                " @click.prevent="
                                                    currentStep >= 3 && scrollTo('#form-step-3', 800, { offset: -20 })
                                                    ">
                                            <a href="#" class="steps-marker"></a>
                                            <div class="steps-content">
                                                <p class="step-number">STEP 4</p>
                                                <p class="step-info">Coding</p>
                                            </div>
                                        </li>
                                        <li id="step-segment-4" :class="[currentStep === 4 && 'is-active']"
                                            class="steps-segment" tabindex="0" @keydown.space.prevent="
                                                currentStep >= 4 && scrollTo('#form-step-4', 800, { offset: -20 })
                                                " @click.prevent="
                                                    currentStep >= 4 && scrollTo('#form-step-4', 800, { offset: -20 })
                                                    ">
                                            <a href="#" class="steps-marker"></a>
                                            <div class="steps-content">
                                                <p class="step-number">STEP 5</p>
                                                <p class="step-info">Hasil Grouper</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div v-else class="form-help">
                                        <div v-if="currentHelp === 0" id="help-section-0"
                                            class="form-help-inner is-active">
                                            <button class="close-help-button" tabindex="0"
                                                @keydown.space.prevent="currentHelp = -1" @click="currentHelp = -1">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                            </button>
                                            <h3>Info Peserta</h3>
                                            <p>
                                                Merupakan data identitas peserta INACBGS
                                            </p>

                                        </div>
                                        <div v-if="currentHelp === 1" id="help-section-1"
                                            class="form-help-inner is-active">
                                            <button class="close-help-button" tabindex="0"
                                                @keydown.space.prevent="currentHelp = -1" @click="currentHelp = -1">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                            </button>
                                            <h3>Pendaftaran</h3>
                                            <p>
                                                Data Registrasi pasien INACBS
                                            </p>

                                        </div>
                                        <div v-if="currentHelp === 2" id="help-section-2"
                                            class="form-help-inner is-active">
                                            <button class="close-help-button" tabindex="0"
                                                @keydown.space.prevent="currentHelp = -1" @click="currentHelp = -1">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                            </button>
                                            <h3>TARIF RS</h3>
                                            <p>
                                                Info tarif RS yang sudah dikelompokan ke dalam 18 Variable INACBGS
                                            </p>

                                        </div>
                                        <div v-if="currentHelp === 3" id="help-section-3"
                                            class="form-help-inner is-active">
                                            <button class="close-help-button" tabindex="0"
                                                @keydown.space.prevent="currentHelp = -1" @click="currentHelp = -1">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                            </button>
                                            <h3>Coding</h3>
                                            <p>
                                                Diagnosa ICD 10 & 9
                                            </p>

                                        </div>
                                        <div v-if="currentHelp === 4" id="help-section-4"
                                            class="form-help-inner is-active">
                                            <button class="close-help-button" tabindex="0"
                                                @keydown.space.prevent="currentHelp = -1" @click="currentHelp = -1">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                            </button>
                                            <h3>Hasil Grouping</h3>
                                            <p>
                                                Info total klaim yang dikeluarkan INACBGS
                                            </p>
                                            <div class="list-wrap">
                                                <ul>
                                                    <li>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:check"></i>
                                                        <span>Some nice list item</span>
                                                    </li>
                                                    <li>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:check"></i>
                                                        <span>Some nice list item</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </VCard>

            <Dialog v-model:visible="modalInput" modal header="Diagnosis ICD 10" :style="{ width: '30vw' }">
                <div class="columns is-multiline p-1">
                    <div class="column is-12">
                        <VField label="Jenis " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fas fa-list" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisDiagnosis10" :options="d_JenisDiagnosa"
                                    :optionLabel="'jenisdiagnosa'" class="is-rounded" placeholder="Jenis "
                                    style="width: 100%;" :filter="true" showClear />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VRadio v-model="item.isKasusBaru" :value="baru" :label="'Baru'" name="isKasusBaru" circle
                            color="primary" style="font-size: 14px; color: black;" />
                    </div>
                    <div class="column is-6">
                        <VRadio v-model="item.isKasusBaru" :value="lama" :label="'Lama'" name="isKasusBaru" circle
                            color="primary" style="font-size: 14px; color: black;" />
                    </div>
                    <div class="column is-12">
                        <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.diagnosa10" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari " />

                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanICD10()" :loading="isLoading"> {{ item.NOREC_DIAGNOSA10 != undefined ? 'Ubah' :
                            'Simpan' }}
                    </VButton>
                </template>
            </Dialog>

            <Dialog v-model:visible="modalMOI" modal header="Diagnosis ICD 10 MOI" :style="{ width: '30vw' }">
                <div class="columns is-multiline p-1">
                    <div class="column is-12">
                        <VField label="Jenis " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fas fa-list" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisDiagnosis10MOI" :options="d_JenisDiagnosa"
                                    :optionLabel="'jenisdiagnosa'" class="is-rounded" placeholder="Jenis "
                                    style="width: 100%;" :filter="true" showClear />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.diagnosa10MOI" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari " />

                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalMOI = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanICD10MOI()" :loading="isLoading"> {{ item.NOREC_DIAGNOSA10 != undefined ?
                            'Ubah' : 'Simpan' }}
                    </VButton>
                </template>
            </Dialog>

            <Dialog v-model:visible="modalInputKematian" modal header="Diagnosis ICD 10 Sebab Kematian"
                :style="{ width: '30vw' }">
                <div class="columns is-multiline p-1">
                    <div class="column is-12">
                        <VField label="Jenis " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fas fa-list" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisDiagnosis10" :options="d_JenisDiagnosa"
                                    :optionLabel="'jenisdiagnosa'" class="is-rounded" placeholder="Jenis "
                                    style="width: 100%;" :filter="true" showClear />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.diagnosa10" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa10($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari " />

                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanICD10Kematian()" :loading="isLoading"> {{ item.NOREC_DIAGNOSA10 != undefined ?
                            'Ubah' : 'Simpan' }}
                    </VButton>
                </template>
            </Dialog>

            <Dialog v-model:visible="modalInput9" modal header="Diagnosis ICD 9" :style="{ width: '20vw' }">
                <div class="columns is-multiline p-1">
                    <div class="column is-12">
                        <VField label="Diagnosis ICD 9 " class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.diagnosa9" :suggestions="d_Diagnosa9"
                                    @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari " />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanICD9()" :loading="isLoading"> {{ item.NOREC_DIAGNOSA9 != undefined ? 'Ubah' :
                            'Simpan' }}
                    </VButton>
                </template>
            </Dialog>

            <Dialog v-model:visible="modalInputO" modal header="Diagnosis ICD-O" :style="{ width: '20vw' }">
                <div class="columns is-multiline p-1">
                    <div class="column is-12">
                        <VField label="Diagnosis ICD-O " class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.diagnosaO" :suggestions="d_DiagnosaO"
                                    @complete="fetchDiagnosaO($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari" />

                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInputO = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanICDO()" :loading="isLoading"> {{ item.NOREC_DIAGNOSAO != undefined ? 'Ubah' :
                            'Simpan' }}
                    </VButton>
                </template>
            </Dialog>





            <Dialog v-model:visible="modalInputpersalinan" modal header="Persalinan">
                <div class="columns is-multiline">
                    <div class="column is-2 ">
                        <VField label="Usia Kehamilan">
                            <VControl>
                                <VInput type="number" placeholder="Usia Kehamilan" v-model="inacbg.usia_kehamilan" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 ">
                        <VField label="Gravida">
                            <VControl>
                                <VInput type="number" placeholder="Gravida" v-model="inacbg.gravida" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 ">
                        <VField label="Partus">
                            <VControl>
                                <VInput type="number" placeholder="Partus" v-model="inacbg.partus" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 ">
                        <VField label="Abortus">
                            <VControl>
                                <VInput type="number" placeholder="Abortus" v-model="inacbg.abortus" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-2 ">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Onset Kontraksi</VLabel>
                            <VControl icon="feather:search">
                                <Dropdown v-model="inacbg.onset_kontraksi" :options="d_onsetkontraksi"
                                    :optionLabel="'nama'" placeholder="Pilih data" style="width: 100%;" showClear
                                    :filter="true" />
                            </VControl>
                        </VField>

                    </div>
                    <div class="column is-12 ">
                        <hr class="dropdown-divider" />
                    </div>
                    <div class="columns is-12 is-multiline" style="margin-left:2px" v-for="(item, index) in delivery"
                        :key="index">
                        <div class="column is-1">
                            <VField label="Delivery Sequence">
                                <VControl>
                                    <VInput type="number" placeholder="Delivery Sequence"
                                        v-model="item.delivery_sequence" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Delivery Method</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.delivery_method" :options="d_deliverymethod"
                                        :optionLabel="'nama'" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VDatePicker v-model="item.delivery_dttm" mode="dateTime" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel>waktu kelahiran</VLabel>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal Masuk"
                                                v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Letak janin</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.letak_janin" :options="d_letakjanin" :optionLabel="'nama'"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Kondisi</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.kondisi" :options="d_kondisi" :optionLabel="'nama'"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-1">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>use_manual</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.use_manual" :options="d_yatidak" :optionLabel="'nama'"
                                        style="width: 100%;" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel> use_forcep</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.use_forcep" :options="d_yatidak" :optionLabel="'nama'"
                                        style="width: 100%;" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel> use_vacuum</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.use_vacuum" :options="d_yatidak" :optionLabel="'nama'"
                                        style="width: 100%;" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel> Spesimen </VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.shk_spesimen_ambil" :options="d_yatidak"
                                        :optionLabel="'nama'" style="width: 100%;" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VField label="Lokaasi">
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.lokasi" placeholder="Lokasi" />
                                    </VControl>
                                </VField>
                            </VField>
                        </div>

                        <div class="column is-2">
                            <VDatePicker v-model="item.shk_spesimen_dttm" mode="dateTime" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel>Tanggal</VLabel>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1" style="margin-top:25px">
                            <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                                icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                        </div>
                        <div class="column is-1" style="margin-top:25px">
                            <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                @click="addNewItem(index)"> Tambah
                            </VButton>
                        </div>
                    </div>
                </div>
                <template #footer>
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
                        Tutup
                    </VButton>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                        @click="simpanPersalinan()" :loading="isLoading"> {{ item.NOREC_DIAGNOSA9 != undefined ? 'Ubah'
                            : 'Simpan' }}
                    </VButton>

                </template>
            </Dialog>

        </div>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import VueScrollTo from 'vue-scrollto'
import { nextTick, h, reactive, ref, computed, defineComponent, watch, watchEffect } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import sleep from '/@src/utils/sleep'
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import InputNumber from 'primevue/inputnumber';
import TStatusPojokKanan from '../emr/profile-pasien/t-status-pojok-kanan.vue'
import { onMounted } from 'vue';
import moment from 'moment'
useHead({
    title: 'INACBGs - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
// let NOREC_APD = useRoute().query.norec_apd as string
let NO_REGIS = useRoute().query.noregistrasi as string

const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    tglpelayanan: new Date(),
})
const router = useRouter()
const confirm = useConfirm()
const { scrollTo } = VueScrollTo
const currentStep = ref(5)
const currentHelp = ref(-1)
const isGrouping = ref(false)
const isFinal = ref(false)
const isCetak = ref(false)
const isHapus = ref(false)
const isReedit = ref(false)
const isDC = ref(false)
const isSITB = ref(false)
const isLoadingBatal = ref(false)
const dataSourceXMasuk: any = ref([])
const dataSourceX: any = ref([])
const dataSourceXKematian: any = ref([])
const dataSourceXMOI: any = ref([])
const dataSourceIX: any = ref([])
const dataSourceO: any = ref([])
const dataSourceXDokter: any = ref([])
const dataSourceIXDokter: any = ref([])
const d_Diagnosa9: any = ref([])
const d_DiagnosaO: any = ref([])
const d_Diagnosa: any = ref([])
const d_JenisDiagnosa: any = ref([])
const d_Dokter: any = ref([])
const d_specialdrug: any = ref([])
const d_specialprocedure: any = ref([])
const d_specialprosthesis: any = ref([])
const d_specialinvestigation: any = ref([])
const d_Jaminan: any = ref([
    { kode: 'JKN', nama: 'JKN' },
    { kode: '00071', nama: 'JAMINAN COVID-19' },
    { kode: '00072', nama: 'JAMINAN KIPI' },
    { kode: '00073', nama: 'JAMINAN BAYI BARU LAHIR' },
    { kode: '00074', nama: 'JAMINAN PERPANJANGAN MASA RAWAT' },
    { kode: '00075', nama: 'JAMINAN CO-INSIDENSE' },
    { kode: '00076', nama: 'JAMPERSAL' }
])
const d_COB: any = ref([
    { kode: '#', nama: '-' },
    { kode: '0001', nama: 'MANDIRI INHEALTH' },
    { kode: '0042', nama: 'BNI LIFE' },
])
const d_KelasRawat: any = ref([
    { kode: 1, nama: 'Kelas 1' },
    { kode: 2, nama: 'Kelas 2' },
    { kode: 3, nama: 'Kelas 3' },
])
const d_CaraMasuk: any = ref([
    { kode: 'gp', nama: 'Rujukan FKTP' },
    { kode: 'hosp-trans', nama: 'Rujukan FKRTL' },
    { kode: 'mp', nama: 'Rujukan Spesialis ' },
    { kode: 'outp', nama: 'Dari Rawat Jalan ' },
    { kode: 'inp', nama: 'Dari Rawat Inap ' },
    { kode: 'emd', nama: 'Dari Rawat Darurat' },
    { kode: 'born', nama: 'Lahir di RS' },
    { kode: 'nursing', nama: 'Rujukan Panti Jompo' },
    { kode: 'psych', nama: 'Rujukan dari RS Jiwa' },
    { kode: 'rehab', nama: 'Rujukan Fasilitas Rehab' },
    { kode: 'other', nama: 'Lain-lain' },
])
const d_CaraPulang: any = ref([
    { kode: 1, nama: 'Atas persetujuan dokter' },
    { kode: 2, nama: 'Dirujuk' },
    { kode: 3, nama: 'Atas permintaan sendiri' },
    { kode: 4, nama: 'Meninggal' },
    { kode: 5, nama: 'Lain-lain' },
])
const d_JenisTarif: any = ref([])
const d_JenisTarifAsal: any = ref([
    { kode: 'AP', nama: 'TARIF RS KELAS A PEMERINTAH' },
    { kode: 'AS', nama: 'TARIF RS KELAS A SWASTA' },
    { kode: 'BP', nama: 'TARIF RS KELAS B PEMERINTAH' },
    { kode: 'BS', nama: 'TARIF RS KELAS B SWASTA' },
    { kode: 'CP', nama: 'TARIF RS KELAS C PEMERINTAH' },
    { kode: 'CS', nama: 'TARIF RS KELAS C SWASTA' },
    { kode: 'DP', nama: 'TARIF RS KELAS D PEMERINTAH' },
    { kode: 'DS', nama: 'TARIF RS KELAS D SWASTA' },
    { kode: 'RSCM', nama: 'TARIF RSUPN CIPTO MANGUNKUSUMO' },
    { kode: 'RSJP', nama: 'TARIF RSJPD HARAPAN KITA' },
    { kode: 'RSD', nama: 'TARIF RS KANKER DHARMAIS' },
    { kode: 'RSAB', nama: 'TARIF RSAB HARAPAN KITA' },

])
const d_onsetkontraksi: any = ref([
    { kode: 'spontan', nama: 'Spontan' },
    { kode: 'induksi', nama: 'Induksi' },
    { kode: 'non_spontan_non_induksi', nama: 'Non Spontan - Non Induksi' },
])
const d_deliverymethod: any = ref([
    { kode: 'vaginal', nama: 'Vaginal' },
    { kode: 'sc', nama: 'SC' }
])
const d_letakjanin: any = ref([
    { kode: 'kepala', nama: 'Kepala' },
    { kode: 'sungsang', nama: 'Sungsang' },
    { kode: 'lintag', nama: 'Lintang' }
])
const d_kondisi: any = ref([
    { kode: 'livebirth', nama: 'Livebirth' },
    { kode: 'stillbirth', nama: 'Stillbirth' }
])

const d_yatidak: any = ref([
    { kode: '0', nama: 'Tidak' },
    { kode: '1', nama: 'Ya' }
])
const d_DPJP: any = ref([])
const modalInput: any = ref(false)
const modalInputO: any = ref(false)
const modalInputKematian: any = ref(false)
const modalMOI: any = ref(false)
const modalInputpersalinan: any = ref(false)
const modalInput9: any = ref(false)
const pasien: any = ref({})
const resGrouper: any = ref({})
const inacbg: any = reactive({
    "tarif_rs": {
        "prosedur_non_bedah": 0,
        "prosedur_bedah": 0,
        "konsultasi": 0,
        "tenaga_ahli": 0,
        "keperawatan": 0,
        "penunjang": 0,
        "radiologi": 0,
        "laboratorium": 0,
        "pelayanan_darah": 0,
        "rehabilitasi": 0,
        "kamar": 0,
        "rawat_intensif": 0,
        "obat": 0,
        "obat_kronis": 0,
        "obat_kemoterapi": 0,
        "alkes": 0,
        "bmhp": 0,
        "sewa_alat": 0
    },
    "specialprocedureValue": 0,
    "specialprosthesisValue": 0,
    "specialinvestigationLValue": 0,
    "specialdrugValue": 0,
})
const { y } = useWindowScroll()
const listColor: any = ref(Object.keys(useThemeColors()))
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const data2: any = ref([])
const isDetail: any = ref([true])
const listItem: any = ref([
    {
        pegawai: [],
        jenisPelaksana: null,
    }
])
const delivery: any = ref([
    {
        skelaminfk: [],
        rangemin: [],
        rangemax: [],
        agemin: [],
        agemax: [],
        ageunit: null,
    }
])

const value = ref(true)
const pasienByID = async (id: any) => { 
    isLoadingPasien.value = true
    await useApi().get(
        `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then((response: any) => {
            pasien.value = response.pasien
            item.NOREC_APD = response.last_registrasi.norec_apd
            item.RUANGAN_LAST = response.last_registrasi.objectruanganfk
            item.registrasi = response.last_registrasi
            item.nocm = response.pasien.nocm
            item.namapasien = response.pasien.namapasien
            item.noregistrasi = response.registrasi[0].noregistrasi
            item.tgl_masuk = response.registrasi[0].tglregistrasi
            isLoadingPasien.value = false

        })
}
const dropdownList = () => {
    useApi().get(
        `/diagnosa/list-dropdown`).then((response: any) => {
            d_JenisDiagnosa.value = response.jenisdiagnosa
            item.jenisDiagnosis10 = d_JenisDiagnosa.value[0]
            item.jenisDiagnosis10MOI = d_JenisDiagnosa.value[1]
            item.jenisDiagnosis10Kematian = d_JenisDiagnosa.value[2]
            item.jenisDiagnosis10Masuk = d_JenisDiagnosa.value[3]
        })
}

const inacbgDetail = async (NOREC_PD: any) => {
    isLoading.value = true
    await useApi().get(
        `/bridging/inacbgs?norec_pd=${NOREC_PD}`).then((response: any) => {
            isLoading.value = false
            let res = response.data[0]
            item.NOREC_APD = res.norec_apd
            item.noregistrasi = res.noregistrasi
            item.namapasien = res.nama_pasien
            item.nocm = res.nocm
            item.kpid = res.kpid
            item.tgl_masuk = res.tgl_masuk

            for (let x = 0; x < d_Jaminan.value.length; x++) {
                const element = d_Jaminan.value[x];
                if (element.kode == res.set_claim_data.data.payor_cd) {
                    inacbg.payor_cd = element
                    break
                }
            }
            for (let x = 0; x < d_KelasRawat.value.length; x++) {
                const element = d_KelasRawat.value[x];
                if (element.kode == res.kelas_dijamin) {
                    inacbg.kelas_rawat = element
                    break
                }
            }
            for (let x = 0; x < d_CaraMasuk.value.length; x++) {
                const element = d_CaraMasuk.value[x];
                if (element.kode == res.set_claim_data.data.cara_masuk) {
                    inacbg.cara_masuk = element
                    break
                }
            }
            for (let x = 0; x < d_CaraPulang.value.length; x++) {
                const element = d_CaraPulang.value[x];
                if (element.kode == res.set_claim_data.data.discharge_status) {
                    inacbg.discharge_status = element
                    break
                }
            }
            for (let x = 0; x < d_JenisTarifAsal.value.length; x++) {
                const element = d_JenisTarifAsal.value[x];
                if (element.kode == res.kodetarif) {
                    inacbg.kodetarif = element
                    break
                }
            }

            inacbg.nomor_kartu = res.nomor_kartu
            inacbg.jenis_rawat = res.jenis_rawat == 1 ? true : false
            inacbg.nomor_sep = res.nomor_sep
            inacbg.nomor_rm = res.nomor_rm
            inacbg.berat_lahir = res.set_claim_data.data.berat_lahir
            inacbg.sistole = res.set_claim_data.data.sistole
            inacbg.diastole = res.set_claim_data.data.diastole
            inacbg.tgl_masuk = new Date(res.tgl_masuk)
            inacbg.tgl_pulang = new Date(res.tgl_pulang)
            inacbg.los = res.set_claim_data.data.los
            inacbg.adl_sub_acute = res.set_claim_data.data.adl_sub_acute
            inacbg.adl_chronic = res.set_claim_data.data.adl_chronic
            // inacbg.kodetarif = res.kodetarif
            inacbg.tarif_rs = res.tarif_rs
            inacbg.totalmappingtarif = res.totalmappingtarif
            inacbg.dokter = { id: res.pgid, namalengkap: res.nama_dokter }
            inacbg.inacbg_status = res.inacbg_status
            inacbg.nama_pasien = res.nama_pasien
            inacbg.tgl_lahir = res.tgl_lahir
            inacbg.gender = res.gender
            inacbg.umur_tahun = res.umur_tahun
            inacbg.usia_kehamilan = res.usia_kehamilan
            inacbg.gravida = res.gravida
            inacbg.partus = res.partus
            inacbg.abortus = res.abortus
            inacbg.coder_nik = res.codernik
            inacbg.onset_kontraksi = { kode: res.kodeonsetkontraksi, nama: res.onsetkontraksi }

            inacbg.appearance = res.appearance
            inacbg.pulse = res.pulse
            inacbg.grimace = res.grimace
            inacbg.activity = res.activity
            inacbg.respiration = res.respiration
            inacbg.appearance5 = res.appearance5
            inacbg.pulse5 = res.pulse5
            inacbg.grimace5 = res.grimace5
            inacbg.activity5 = res.activity5
            inacbg.respiration5 = res.respiration5
            if (res.delivery != null) {
                delivery.value = [];
                for (let x = 0; x < res.delivery.length; x++) {
                    const e = res.delivery[x];
                    delivery.value.push({
                        delivery_sequence: e.delivery_sequence,
                        delivery_method: { kode: e.kodedelivery_method, nama: e.delivery_method },
                        delivery_dttm: e.delivery_dttm,
                        letak_janin: { kode: e.kodeletak_janin, nama: e.letak_janin },
                        kondisi: { kode: e.kodekondisi, nama: e.kondisi },
                        use_manual: { kode: e.use_manual, nama: e.use_manual == 1 ? 'Ya' : 'Tidak' },
                        use_forcep: { kode: e.use_forcep, nama: e.use_forcep == 1 ? 'Ya' : 'Tidak' },
                        use_vacuum: { kode: e.use_vacuum, nama: e.use_vacuum == 1 ? 'Ya' : 'Tidak' },
                        shk_spesimen_ambil: { kode: e.kodeshk_spesimen_ambil, nama: e.shk_spesimen_ambil },
                        lokasi: e.lokasi,
                        shk_spesimen_dttm: e.shk_spesimen_dttm

                    });
                }

            }

            if (res.sitb_id) {
                inacbg.chkpasientb = true
                inacbg.nomorsitb = res.sitb_id
            }
            inacbg.eksekutif = res.eksekutif
            inacbg.kemkes_dc_status = res.kemkes_dc_status
        })

}
const fetchDokter = async (filter: any) => {
    if (!filter.query) {

    }

    const response = await useApi().get(
        `/bridging/inacbgs/dokter-paging?name=${filter.query}&limit=10`)
    d_Dokter.value = response.dokter
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}
const validateStep = async () => {
    if (currentStep.value === 4) {
        if (isLoading.value) {
            return
        }

        isLoading.value = true

        return
    }

    isLoading.value = true
    await sleep(400)
    currentStep.value += 1

    nextTick(() => {
        scrollTo(`#form-step-${currentStep.value}`, 1000)
        isLoading.value = false
    })
}
const simpan = () => {

}
const route = useRoute()
const kembaliKeun = () => {
    // window.history.back()
    router.push({
        name: 'module-inacbgs-koding',
        query: {
            page: route.query.page,
        }
    });
};


const showModal = () => {
    clearInput()
    item.tglpelayanann = new Date()
    item.jenisDiagnosis10 = d_JenisDiagnosa.value[0]
    // item.jenisDiagnosis10MOI = d_JenisDiagnosa.value[1]
    modalInput.value = true
}

// const showModalM = () => {
//     clearInput()
//     item.tglpelayanann = new Date()
//     item.jenisDiagnosis10 = d_JenisDiagnosa.value[0]
//     modalInput.value = true
// }

const showModalO = () => {
    clearInput()
    modalInputO.value = true
}

const showModalKematian = () => {
    clearInput()
    item.tglpelayanann = new Date()
    item.jenisDiagnosis10Kematian = d_JenisDiagnosa.value[0]
    modalInputKematian.value = true
}

const showMOI = () => {
    clearInput()
    item.tglpelayanann = new Date()
    item.jenisDiagnosis10MOI = d_JenisDiagnosa.value[0]
    modalMOI.value = true
}
const showModalpersalinan = () => {
    clearInput()
    modalInputpersalinan.value = true
}
const showModal9 = () => {
    clearInput()
    item.tglpelayanann = new Date()
    modalInput9.value = true
}
const clearInput = () => {
    delete item.NOREC_DIAGNOSA9
    delete item.NOREC_DIAGNOSA10
    delete item.keterangan9
    delete item.diagnosa9
    delete item.keterangan10
    delete item.diagnosa10
    delete item.diagnosa10MOI
    delete item.jenisDiagnosis10
    delete item.jenisDiagnosis10MOI
    delete item.jenisDiagnosis10Kematian
    delete item.jenisDiagnosis10Masuk

    modalInput.value = false
    modalInput9.value = false
}
const fetchDiagnosa10 = async (filter: any) => {
    let query = ''
    if (filter) {
        query = filter.query.toLowerCase()
    }

    const response = await useApi().get(
        `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)

    for (let x = 0; x < response.diagnosa.length; x++) {
        const element = response.diagnosa[x];
        element.label = element.kddiagnosa + ' - ' + element.namadiagnosa
    }
    d_Diagnosa.value = response.diagnosa

}
const fetchDiagnosa9 = async (filter: any) => {
    let query = ''
    if (filter) {
        query = filter.query.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)

    for (let x = 0; x < response.diagnosatindakan.length; x++) {
        const element = response.diagnosatindakan[x];
        element.label = element.kddiagnosatindakan + ' - ' + element.namadiagnosatindakan
    }
    d_Diagnosa9.value = response.diagnosatindakan
    // return response.diagnosatindakan.map((item: any) => {
    //     return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
    // })
}

const fetchDiagnosaO = async (filter: any) => {
    let query = ''
    if (filter) {
        query = filter.query.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-morfologi-paging?name=${query}&limit=10`)

    for (let x = 0; x < response.diagnosamorfologi.length; x++) {
        const element = response.diagnosamorfologi[x];
        element.label = element.kddiagnosamorfologi + ' - ' + element.diagnosamorfologi
    }
    d_DiagnosaO.value = response.diagnosamorfologi
}

const simpanPersalinan = async () => {
    for (var x = 0; x < delivery.value.length; x++) {
        if (delivery.value[x].delivery_sequence < 1) {
            H.alert('error', 'Ada Sequence yang belum di isi!')
            return
        }
    }

    let json = {
        'norecpd': item.NOREC_PD,
        'head': inacbg,
        'detail': delivery.value


    }
    isLoading.value = true
    await useApi().post(
        `/bridging/persalinan`, json).then((response: any) => {
            isLoading.value = false
            modalInputpersalinan.value = false

        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanApgar = async () => {

    let json = {
        'norecpd': item.NOREC_PD,
        'head': inacbg
    }
    isLoading.value = true
    await useApi().post(
        `/bridging/apgar`, json).then((response: any) => {
            isLoading.value = false

        }).catch((e: any) => {
            isLoading.value = false
        })
}
const simpanDializer = async () => {

    let json = {
        'norecpd': item.NOREC_PD,
        'head': inacbg
    }
    isLoading.value = true
    await useApi().post(
        `/bridging/dializer`, json).then((response: any) => {
            isLoading.value = false

        }).catch((e: any) => {
            isLoading.value = false
        })
}
const simpanICD9 = async () => {
    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }

    if (!item.diagnosa9.id) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    const isDuplicate = dataSourceIX.value.some(diagnosa => diagnosa.kddiagnosatindakan === item.diagnosa9.kddiagnosatindakan);
    if (isDuplicate) {
        H.alert('error', 'Diagnosis tidak boleh sama dengan diagnosis yang sudah ada');
        return;
    }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA9 ? item.NOREC_DIAGNOSA9 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
            'keterangantindakan': item.keterangan9 ? item.keterangan9 : null,

        },
        'detaildiagnosapasien': {
            'objectdiagnosatindakanrmfk': item.diagnosa9.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'noregistrasi': item.noregistrasi,
        },

    }
    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa-ix-rm`, json).then((response: any) => {
            isLoading.value = false
            modalInput9.value = false
            item.diagnosa9 = undefined
            riwayatDiagnosa9()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanICDO = async () => {

    if (!item.diagnosaO.id) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSAO ? item.NOREC_DIAGNOSAO : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
        },
        'detaildiagnosapasien': {
            'objectdiagnosamorfologifk': item.diagnosaO.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'noregistrasi': item.noregistrasi,
        },

    }
    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa-o-rm`, json).then((response: any) => {
            isLoading.value = false
            modalInputO.value = false
            riwayatDiagnosaO()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanICD10Masuk = async () => {
    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }
    // if (!item.jenisDiagnosis10MOI) {
    //     H.alert('error', 'Jenis Diagnosis harus di isi')
    //     return
    // }
    if (!item.diagnosaMasuk) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    let jenisDiagnosis10Masuk = 1
      if(dataSourceXMasuk.value.length != 0){
        jenisDiagnosis10Masuk = 2
      }
   

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
            'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
            'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
            'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
        },
        'detaildiagnosapasien': {
            'objectdiagnosafk': item.diagnosaMasuk.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'objectjenisdiagnosarmfk': jenisDiagnosis10Masuk,
            'noregistrasi': item.noregistrasi,
            'iskematian': null,
            'ismoi': null,
            'ismasuk': true
        }, 'pasien': {
            'nocm': item.nocm,
            'namapasien': item.namapasien,
            'noregistrasi': item.noregistrasi,
        }
    }


    isLoading.value = true
    await useApi().post(`/diagnosa/save-diagnosa-rm`, json).then((response: any) => {
        isLoading.value = false
        modalInput.value = false
        riwayatDiagnosa10()
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpanICD10 = async () => {
    console.log(item.isKasusBaru)
    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }
    // if (!item.jenisDiagnosis10) {
    //     H.alert('error', 'Jenis Diagnosis harus di isi')
    //     return
    // }
    if (!item.diagnosa10) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    const isDuplicate = dataSourceX.value.some(diagnosa => diagnosa.kddiagnosa === item.diagnosa10.kddiagnosa);
    if (isDuplicate) {
        H.alert('error', 'Diagnosis tidak boleh sama dengan diagnosis yang sudah ada');
        return;
    }

    let jenisDiagnosis10 = item.jenisDiagnosis10 ? item.jenisDiagnosis10.id : 1;
    if (!item.NOREC_DIAGNOSA10 && dataSourceX.value.length !== 0) {
        jenisDiagnosis10 = 2;
    }


    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
            'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
            'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
            'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
        },
        'detaildiagnosapasien': {
            'objectdiagnosafk': item.diagnosa10.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'objectjenisdiagnosarmfk': jenisDiagnosis10,
            // 'objectjenisdiagnosarmfk': item.jenisDiagnosis10MOI.id,
            'noregistrasi': item.noregistrasi,
            'iskematian': null,
            'ismoi': null,
            'ismasuk': null
        }, 'pasien': {
            'nocm': item.nocm,
            'namapasien': item.namapasien,
            'noregistrasi': item.noregistrasi,
        }
    }
    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa-rm`, json).then((response: any) => {
            isLoading.value = false
            modalInput.value = false
            item.diagnosa10 = undefined
            item.NOREC_DIAGNOSA10 = undefined
            riwayatDiagnosa10()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const simpanICD10Kematian = async () => {
    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }
    // if (!item.jenisDiagnosis10) {
    //     H.alert('error', 'Jenis Diagnosis harus di isi')
    //     return
    // }
    if (!item.diagnosa10Kematian) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    let jenisDiagnosis10Kematian = 1
      if(dataSourceXKematian.value.length != 0){
        jenisDiagnosis10Kematian = 2
      }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
            'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
            'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
            'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
        },
        'detaildiagnosapasien': {
            'objectdiagnosafk': item.diagnosa10Kematian.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'objectjenisdiagnosarmfk': jenisDiagnosis10Kematian,
            'noregistrasi': item.noregistrasi,
            'iskematian': true,
            'ismoi': null,
            'ismasuk': null
        }, 'pasien': {
            'nocm': item.nocm,
            'namapasien': item.namapasien,
            'noregistrasi': item.noregistrasi,
        }
    }


    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-diagnosa-rm`, json).then((response: any) => {
            isLoading.value = false
            modalInput.value = false
            riwayatDiagnosa10()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const simpanICD10MOI = async () => {
    if (!item.tglpelayanan) {
        H.alert('error', 'Tgl  harus di isi')
        return
    }
    // if (!item.jenisDiagnosis10MOI) {
    //     H.alert('error', 'Jenis Diagnosis harus di isi')
    //     return
    // }
    if (!item.diagnosa10MOI) {
        H.alert('error', 'Diagnosis harus di isi')
        return
    }

    let jenisDiagnosis10MOI = 1
      if(dataSourceXMOI.value.length != 0){
        jenisDiagnosis10MOI = 2
      }

    let json = {
        'diagnosapasien': {
            'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
            'noregistrasifk': item.NOREC_APD,
            'tglregistrasi': H.formatDate(item.tgl_masuk, 'YYYY-MM-DD HH:mm:ss'),
            'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
            'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
            'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
        },
        'detaildiagnosapasien': {
            'objectdiagnosafk': item.diagnosa10MOI.id,
            'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
            'objectjenisdiagnosarmfk': jenisDiagnosis10MOI,
            'noregistrasi': item.noregistrasi,
            'iskematian': null,
            'ismoi': true,
            'ismasuk': null
        }, 'pasien': {
            'nocm': item.nocm,
            'namapasien': item.namapasien,
            'noregistrasi': item.noregistrasi,
        }
    }


    isLoading.value = true
    await useApi().post(`/diagnosa/save-diagnosa-rm`, json).then((response: any) => {
        isLoading.value = false
        modalMOI.value = false
        riwayatDiagnosa10()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const saveMOI = async () => {
    if (!item.moi) {
        H.alert('error', 'MOI  harus di isi')
        return
    }

    let json = {
        'diagnosapasien': {
            'norec': NOREC_PD,
            'moi': item.moi,
        }
    }
    isLoading.value = true
    await useApi().post(
        `/diagnosa/save-moi`, json).then((response: any) => {
            isLoading.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const riwayatDiagnosa10 = async () => {
    // dataSourceX.value = []

    dataSourceXDokter.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceXDokter.value.loading = false
            dataSourceXDokter.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXDokter.value.loading = false
        })

    dataSourceXKematian.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x-rm?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}&iskematian=true`).then((response: any) => {
            dataSourceXKematian.value.loading = false
            dataSourceXKematian.value = response.data.sort(compare)
        }).catch((e: any) => {
            dataSourceXKematian.value.loading = false
        })

    dataSourceXMOI.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x-rm?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}&ismoi=true`).then((response: any) => {
            dataSourceXMOI.value.loading = false
            dataSourceXMOI.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXMOI.value.loading = false
        })

    dataSourceX.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x-rm?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceX.value.loading = false
            dataSourceX.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceX.value.loading = false
        })

    dataSourceXMasuk.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-x-rm?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}&ismasuk=true`).then((response: any) => {
            dataSourceXMasuk.value.loading = false
            dataSourceXMasuk.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXMasuk.value.loading = false
        })

}

const compare = (a: any, b: any) => {
    if (a.objectjenisdiagnosafk > b.objectjenisdiagnosafk) {
        return 1;
    }
    if (a.objectjenisdiagnosafk < b.objectjenisdiagnosafk) {
        return -1;
    }
    return 0;
}
const riwayatDiagnosa9 = async () => {
    // dataSourceIX.value = []

    dataSourceIXDokter.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-ix?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceIXDokter.value.loading = false
            dataSourceIXDokter.value = response.data
        }).catch((e: any) => {
            dataSourceIXDokter.value.loading = false
        })

    dataSourceIX.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-ix-rm?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceIX.value.loading = false
            dataSourceIX.value = response.data
        }).catch((e: any) => {
            dataSourceIX.value.loading = false
        })

}
const riwayatDiagnosaO = async () => {
    // dataSourceIX.value = []

    dataSourceO.value.loading = true
    await useApi().get(
        `/diagnosa/riwayat-diagnosa-o?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((response: any) => {
            dataSourceO.value.loading = false
            dataSourceO.value = response.data
        }).catch((e: any) => {
            dataSourceO.value.loading = false
        })

}
const editItems = (e: any) => {
    clearInput()
    console.log(e)
    item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    // item.keterangan10 = e.keterangan
    item.diagnosa10 = {
        id: e.objectdiagnosarmfk,
        label: e.kddiagnosa + ' - ' + e.namadiagnosa,
        kddiagnosa: e.kddiagnosa,
        namadiagnosa: e.namadiagnosa
    }
    // item.jenisDiagnosis10 = { id: e.objectjenisdiagnosarmfk, jenisdiagnosa: e.jenisdiagnosa }
    item.jenisDiagnosis10 = e.objectjenisdiagnosarmfk
    ? { id: e.objectjenisdiagnosarmfk, jenisdiagnosa: e.jenisdiagnosa }
    : { id: 1, jenisdiagnosa: 'Primary / utama' };

    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInput.value = true
}
const editItemsKematian = (e: any) => {
    clearInput()
    console.log(e)
    item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    // item.keterangan10 = e.keterangan
    item.diagnosa10 = {
        id: e.objectdiagnosarmfk,
        label: e.kddiagnosa + ' - ' + e.namadiagnosa,
        kddiagnosa: e.kddiagnosa,
        namadiagnosa: e.namadiagnosa
    }
    item.jenisDiagnosis10 = { id: e.objectjenisdiagnosarmfk, jenisdiagnosa: e.jenisdiagnosa }
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInputKematian.value = true
}

const editMOI = (e: any) => {
    clearInput()
    console.log(e)
    item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    // item.keterangan10 = e.keterangan
    item.diagnosa10MOI = {
        id: e.objectdiagnosarmfk,
        label: e.kddiagnosa + ' - ' + e.namadiagnosa,
        kddiagnosa: e.kddiagnosa,
        namadiagnosa: e.namadiagnosa
    }
    item.jenisDiagnosis10MOI = { id: e.objectjenisdiagnosarmfk, jenisdiagnosa: e.jenisdiagnosa }
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalMOI.value = true
}
const editMasuk = (e: any) => {
    clearInput()
    console.log(e)
    item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    // item.keterangan10 = e.keterangan
    item.diagnosaMasuk = {
        id: e.objectdiagnosarmfk,
        label: e.kddiagnosa + ' - ' + e.namadiagnosa,
        kddiagnosa: e.kddiagnosa,
        namadiagnosa: e.namadiagnosa
    }
    item.jenisDiagnosisMasuk = { id: e.objectjenisdiagnosarmfk, jenisdiagnosa: e.jenisdiagnosa }
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalMasuk.value = true
}
const setPrimary = (e: any) => {
    console.log(e)
    // item.NOREC_DIAGNOSA10 = e.norec_diagnosapasien
    // item.isKasusBaru = e.iskasusbaru == true ? 'baru' : (e.iskasuslama == true ? 'lama' : null)
    // // item.keterangan10 = e.keterangan
    // item.diagnosa10 = {
    //     id: e.objectdiagnosaklaimfk,
    //     label: e.kddiagnosa + ' - ' + e.namadiagnosa,
    //     kddiagnosa: e.kddiagnosa,
    //     namadiagnosa: e.namadiagnosa
    // }


    // item.jenisDiagnosis10 = { id: e.objectjenisdiagnosaklaimfk, jenisdiagnosa: e.jenisdiagnosa }
    // item.tglpelayanan = new Date(e.tglinputdiagnosa)
    // modalInput.value = true
}

const editItemsIX = (e: any) => {
    clearInput()
    item.NOREC_DIAGNOSA9 = e.norec_diagnosapasien
    item.keterangan9 = e.keterangantindakan

    item.diagnosa9 = {
        id: e.objectdiagnosatindakanrmfk,
        label: e.kddiagnosatindakan + ' - ' + e.namadiagnosatindakan,
        kddiagnosatindakan: e.kddiagnosatindakan,
        namadiagnosatindakan: e.namadiagnosatindakan
    }
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInput9.value = true
}

const editItemsO = (e: any) => {
    clearInput()
    item.NOREC_DIAGNOSAO = e.norec_diagnosapasien

    item.diagnosaO = {
        id: e.objectdiagnosamorfologifk,
        label: e.kddiagnosamorfologi + ' - ' + e.diagnosamorfologi,
        kddiagnosamorfologi: e.kddiagnosamorfologi,
        diagnosamorfologi: e.diagnosamorfologi
    }
    item.tglpelayanan = new Date(e.tglinputdiagnosa)
    modalInputO.value = true
}

const hapusItems = (e: any) => {

    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            useApi().post(
                `/diagnosa/delete-diagnosa-x`, { norec: e.norec_diagnosapasien }).then((response: any) => {
                    isLoading.value = false
                    riwayatDiagnosa10()
                }).catch((xx: any) => {
                    isLoading.value = false
                })
        },
        reject: () => { },
    })

}

const hapusItemss = (e: any) => {

    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            useApi().post(
                `/diagnosa/delete-diagnosa-x`, { norec: e.norec_diagnosapasien }).then((response: any) => {
                    isLoading.value = false
                    riwayatDiagnosa10()
                }).catch((xx: any) => {
                    isLoading.value = false
                })
        },
        reject: () => { },
    })

}
const hapusItemsO = (e: any) => {

    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            useApi().post(
                `/diagnosa/delete-diagnosa-o`, { norec: e.norec_diagnosapasien }).then((response: any) => {
                    isLoading.value = false
                    riwayatDiagnosaO()
                }).catch((xx: any) => {
                    isLoading.value = false
                })
        },
        reject: () => { },
    })

}
const hapusItemsIX = (e: any) => {
    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            useApi().post(
                `/diagnosa/delete-diagnosa-ix`, { norec: e.norec_diagnosapasien }).then((response: any) => {
                    isLoading.value = false
                    riwayatDiagnosa9()
                }).catch((xx: any) => {
                    isLoading.value = false
                })
        },
        reject: () => { },
    })


}
const newKlaim = async () => {
    let json = []
    if (inacbg.inacbg_status == null
        || inacbg.inacbg_status == 'Hapus Klaim') {
        json.push({
            "metadata": {
                "method": "new_claim"
            },
            "data": {
                "nomor_kartu": inacbg.nomor_kartu,
                "nomor_sep": inacbg.nomor_sep,
                "nomor_rm": inacbg.nomor_rm,
                "nama_pasien": inacbg.nama_pasien,
                "tgl_lahir": inacbg.tgl_lahir,
                "gender": inacbg.gender,
            }
        })
    }


    let diagnsosa10 = ''
    for (let z = 0; z < dataSourceX.value.length; z++) {
        const element = dataSourceX.value[z];
        diagnsosa10 = diagnsosa10 + '#' + element.kddiagnosa
    }
    diagnsosa10 = diagnsosa10.substr(1, diagnsosa10.length)


    let diagnsosa9 = ''
    for (let z = 0; z < dataSourceIX.value.length; z++) {
        const element = dataSourceIX.value[z];
        diagnsosa9 = diagnsosa9 + '#' + element.kddiagnosatindakan
    }
    diagnsosa9 = diagnsosa9.substr(1, diagnsosa9.length)

    let kelasrawat = ''
    if (inacbg.jenis_rawat != true) {
        if (inacbg.eksekutif) { kelasrawat = '1' }
        else { kelasrawat = '3' }
    } else {
        kelasrawat = inacbg.kelas_rawat ? inacbg.kelas_rawat.kode : ""
    }
    let tempdevlivery = ''
    if (delivery.value.length > 0) {
        tempdevlivery = [];
        for (var x = 0; x < delivery.value.length; x++) {
            var y = {
                delivery_sequence: delivery.value[x].delivery_sequence,
                delivery_method: delivery.value[x].delivery_method.kode,
                delivery_dttm: moment(delivery.value[x].delivery_dttm).format('YYYY-MM-DD HH:mm:ss'),
                shk_lokasi: delivery.value[x].lokasi,
                shk_spesimen_dttm: moment(delivery.value[x].shk_spesimen_dttm).format('YYYY-MM-DD HH:mm:ss'),
                letak_janin: delivery.value[x].letak_janin,
                kondisi: delivery.value[x].kondisi.kode,
                use_manual: delivery.value[x].use_manual.kode,
                use_forcep: delivery.value[x].use_forcep.kode,
                use_vacuum: delivery.value[x].use_vacuum.kode,
                shk_spesimen_ambil: delivery.value[x].shk_spesimen_ambil.kode
            }
            tempdevlivery.push(y);
        }
        var persalinanfix = {
            usia_kehamilan: inacbg.usia_kehamilan,
            gravida: inacbg.gravida,
            partus: inacbg.partus,
            abortus: inacbg.abortus,
            onset_kontraksi: inacbg.onset_kontraksi.kode,
            delivery: tempdevlivery
        }
    }
    json.push({
        "metadata": {
            "method": "set_claim_data",
            "nomor_sep": inacbg.nomor_sep,
        },
        "data": {
            "nomor_sep": inacbg.nomor_sep,
            "nomor_kartu": inacbg.nomor_kartu,
            "tgl_masuk": H.formatDate(inacbg.tgl_masuk, 'YYYY-MM-DD HH:mm'),
            "tgl_pulang": H.formatDate(inacbg.tgl_pulang, 'YYYY-MM-DD HH:mm'),
            "cara_masuk": inacbg.cara_masuk.kode,
            "jenis_rawat": inacbg.jenis_rawat == true ? 1 : 2,
            "kelas_rawat": kelasrawat,
            "adl_sub_acute": inacbg.adl_sub_acute ? inacbg.adl_sub_acute : "",
            "adl_chronic": inacbg.adl_chronic ? inacbg.adl_chronic : "",
            "icu_indikator": inacbg.icu_indikator ? inacbg.icu_indikator : "",
            "icu_los": inacbg.icu_los ? inacbg.icu_los : "",
            "ventilator_hour": inacbg.ventilator_hour ? inacbg.ventilator_hour : "",
            "ventilator": {
                "use_ind": inacbg.use_ind ? inacbg.use_ind : "",
                "start_dttm": inacbg.start_dttm ? inacbg.start_dttm : "",
                "stop_dttm": inacbg.stop_dttm ? inacbg.stop_dttm : "",
            },
            "upgrade_class_ind": inacbg.upgrade_class_ind ? inacbg.upgrade_class_ind : "",
            "upgrade_class_class": inacbg.upgrade_class_class ? inacbg.upgrade_class_class : "",
            "upgrade_class_los": inacbg.upgrade_class_los ? inacbg.upgrade_class_los : "",
            "upgrade_class_payor": inacbg.upgrade_class_payor ? inacbg.upgrade_class_payor : "",
            "add_payment_pct": inacbg.add_payment_pct ? inacbg.add_payment_pct : "",
            "birth_weight": inacbg.birth_weight ? inacbg.birth_weight : "",
            "sistole": inacbg.sistole ? inacbg.sistole : "",
            "diastole": inacbg.diastole ? inacbg.diastole : "",
            "discharge_status": inacbg.discharge_status ? inacbg.discharge_status.kode : "",
            "diagnosa": diagnsosa10,
            "procedure": diagnsosa9,
            "diagnosa_inagrouper": diagnsosa10,
            "procedure_inagrouper": diagnsosa9,
            "tarif_rs": {
                "prosedur_non_bedah": inacbg.tarif_rs.prosedur_non_bedah ? inacbg.tarif_rs.prosedur_non_bedah : 0,
                "prosedur_bedah": inacbg.tarif_rs.prosedur_bedah ? inacbg.tarif_rs.prosedur_bedah : 0,
                "konsultasi": inacbg.tarif_rs.konsultasi ? inacbg.tarif_rs.konsultasi : 0,
                "tenaga_ahli": inacbg.tarif_rs.tenaga_ahli ? inacbg.tarif_rs.tenaga_ahli : 0,
                "keperawatan": inacbg.tarif_rs.keperawatan ? inacbg.tarif_rs.keperawatan : 0,
                "penunjang": inacbg.tarif_rs.penunjang ? inacbg.tarif_rs.penunjang : 0,
                "radiologi": inacbg.tarif_rs.radiologi ? inacbg.tarif_rs.radiologi : 0,
                "laboratorium": inacbg.tarif_rs.laboratorium ? inacbg.tarif_rs.laboratorium : 0,
                "pelayanan_darah": inacbg.tarif_rs.pelayanan_darah ? inacbg.tarif_rs.pelayanan_darah : 0,
                "rehabilitasi": inacbg.tarif_rs.rehabilitasi ? inacbg.tarif_rs.rehabilitasi : 0,
                "kamar": inacbg.tarif_rs.kamar ? inacbg.tarif_rs.kamar : 0,
                "rawat_intensif": inacbg.tarif_rs.rawat_intensif ? inacbg.tarif_rs.rawat_intensif : 0,
                "obat": inacbg.tarif_rs.obat ? inacbg.tarif_rs.obat : 0,
                "obat_kronis": inacbg.tarif_rs.obat_kronis ? inacbg.tarif_rs.obat_kronis : 0,
                "obat_kemoterapi": inacbg.tarif_rs.obat_kemoterapi ? inacbg.tarif_rs.obat_kemoterapi : 0,
                "alkes": inacbg.tarif_rs.alkes ? inacbg.tarif_rs.alkes : 0,
                "bmhp": inacbg.tarif_rs.bmhp ? inacbg.tarif_rs.bmhp : 0,
                "sewa_alat": inacbg.tarif_rs.sewa_alat ? inacbg.tarif_rs.sewa_alat : 0,
            },
            "pemulasaraan_jenazah": inacbg.pemulasaraan_jenazah ? inacbg.pemulasaraan_jenazah : "",
            "kantong_jenazah": inacbg.kantong_jenazah ? inacbg.kantong_jenazah : "",
            "peti_jenazah": inacbg.peti_jenazah ? inacbg.peti_jenazah : "",
            "plastik_erat": inacbg.plastik_erat ? inacbg.plastik_erat : "",
            "desinfektan_jenazah": inacbg.desinfektan_jenazah ? inacbg.desinfektan_jenazah : "",
            "mobil_jenazah": inacbg.mobil_jenazah ? inacbg.mobil_jenazah : "",
            "desinfektan_mobil_jenazah": inacbg.desinfektan_mobil_jenazah ? inacbg.desinfektan_mobil_jenazah : "",
            "covid19_status_cd": inacbg.covid19_status_cd ? inacbg.covid19_status_cd : "",
            "nomor_kartu_t": "kartu_jkn",
            "episodes": inacbg.episodes ? inacbg.episodes : "",
            "covid19_cc_ind": inacbg.covid19_cc_ind ? inacbg.covid19_cc_ind : "",
            "covid19_rs_darurat_ind": inacbg.covid19_rs_darurat_ind ? inacbg.covid19_rs_darurat_ind : "",
            "covid19_co_insidense_ind": inacbg.covid19_co_insidense_ind ? inacbg.covid19_co_insidense_ind : "",
            "covid19_penunjang_pengurang": {
                "lab_asam_laktat": inacbg.lab_asam_laktat ? inacbg.lab_asam_laktat : "",
                "lab_procalcitonin": inacbg.lab_procalcitonin ? inacbg.lab_procalcitonin : "",
                "lab_crp": inacbg.lab_crp ? inacbg.lab_crp : "",
                "lab_kultur": inacbg.lab_kultur ? inacbg.lab_kultur : "",
                "lab_d_dimer": inacbg.lab_d_dimer ? inacbg.lab_d_dimer : "",
                "lab_pt": inacbg.lab_pt ? inacbg.lab_pt : "",
                "lab_aptt": inacbg.lab_aptt ? inacbg.lab_aptt : "",
                "lab_waktu_pendarahan": inacbg.lab_waktu_pendarahan ? inacbg.lab_waktu_pendarahan : "",
                "lab_anti_hiv": inacbg.lab_anti_hiv ? inacbg.lab_anti_hiv : "",
                "lab_analisa_gas": inacbg.lab_analisa_gas ? inacbg.lab_analisa_gas : "",
                "lab_albumin": inacbg.lab_albumin ? inacbg.lab_albumin : "",
                "rad_thorax_ap_pa": inacbg.rad_thorax_ap_pa ? inacbg.rad_thorax_ap_pa : "",
            },
            "terapi_konvalesen": inacbg.terapi_konvalesen ? inacbg.terapi_konvalesen : "",
            "akses_naat": inacbg.akses_naat ? inacbg.akses_naat : "",
            "isoman_ind": inacbg.isoman_ind ? inacbg.isoman_ind : "",
            "bayi_lahir_status_cd": inacbg.bayi_lahir_status_cd ? inacbg.bayi_lahir_status_cd : "",
            "dializer_single_use": inacbg.dializer_single_use ? inacbg.dializer_single_use : "",
            "kantong_darah": inacbg.kantong_darah ? inacbg.kantong_darah : "",
            "apgar": {
                "menit_1": {
                    "appearance": inacbg.appearance ? inacbg.appearance : "",
                    "pulse": inacbg.pulse ? inacbg.pulse : "",
                    "grimace": inacbg.grimace ? inacbg.grimace : "",
                    "activity": inacbg.activity ? inacbg.activity : "",
                    "respiration": inacbg.respiration ? inacbg.respiration : "",
                },
                "menit_5": {
                    "appearance": inacbg.appearance5 ? inacbg.appearance5 : "",
                    "pulse": inacbg.pulse5 ? inacbg.pulse5 : "",
                    "grimace": inacbg.grimace5 ? inacbg.grimace5 : "",
                    "activity": inacbg.activity5 ? inacbg.activity5 : "",
                    "respiration": inacbg.respiration5 ? inacbg.respiration5 : "",
                }
            },
            "persalinan": persalinanfix,
            "tarif_poli_eks": inacbg.tarif_poli_eks ? inacbg.tarif_poli_eks : "",
            "nama_dokter": inacbg.dokter ? inacbg.dokter.namalengkap : "",
            "kode_tarif": inacbg.kodetarif ? inacbg.kodetarif.kode : "",
            "payor_id": 3,
            "payor_cd": inacbg.payor_cd ? inacbg.payor_cd.kode : "",
            "cob_cd": inacbg.cob ? inacbg.cob.kode : "#",
            "coder_nik": inacbg.coder_nik ? inacbg.coder_nik : "",
        }

    })


    isLoading.value = true
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element = r.response.dataresponse[x];
            if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                    'nomor_sep': element.datarequest.data.nomor_sep,
                    'inacbg_status': element.datarequest.metadata.method,
                    "diagnosa": element.datarequest.data.diagnosa ? element.datarequest.data.diagnosa : null,
                    "procedure": element.datarequest.data.procedure ? element.datarequest.data.procedure : null,
                    "diagnosa_inagrouper": element.datarequest.data.diagnosa_inagrouper ? element.datarequest.data.diagnosa_inagrouper : null,
                    "procedure_inagrouper": element.datarequest.data.procedure_inagrouper ? element.datarequest.data.procedure_inagrouper : null,
                    "dokterdpjp": inacbg.dokter ? inacbg.dokter.id : null,
                })
                H.alert('success', element.dataresponse.metadata.message)
            } else {
                H.alert('error', element.dataresponse.metadata.message)
            }
        }
        saveStatus(arrStatus)
        savePemakaianAsuransi()
        isLoading.value = false
    }, (error) => {
        isLoading.value = false
    })
}
const claim_print = async () => {
    let json = [
        {
            "metadata": {
                "method": "claim_print"
            },
            "data": {
                "nomor_sep": inacbg.nomor_sep
            }
        }
    ]
    isCetak.value = true
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element: any = r.response.dataresponse[x];
            if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                    'nomor_sep': element.datarequest.data.nomor_sep,
                    'inacbg_status': element.datarequest.metadata.method
                })
                const linkSource = 'data:application/pdf;base64,' + element.dataresponse.data;
                const downloadLink = document.createElement("a");

                let a = element.datarequest.data.nomor_sep
                let nama = a.substr(12);
                const fileName = nama + ".pdf";

                downloadLink.href = linkSource;
                downloadLink.download = fileName;
                downloadLink.click();
                H.alert('success', element.dataresponse.metadata.message)
            } else {
                H.alert('error', element.dataresponse.metadata.message)
            }
        }
        // saveStatus(arrStatus)
        isCetak.value = false
    }, (error) => {
        isCetak.value = false
    })
}
const finalClaim = async () => {
    isFinal.value = true
    let jsonclaimdata = [
        {
            "metadata": {
                "method": "get_claim_data"
            },
            "data": {
                "nomor_sep": inacbg.nomor_sep
            }
        }
    ]
    const resclaimdata = await useApi().postBPJS('/bridging/inacbgs/save', { 'data': jsonclaimdata })
    const claimdata = resclaimdata.response.dataresponse[0].dataresponse
    if (claimdata.metadata.code != 200) {
        isFinal.value = false
        H.alert('error', claimdata.metadata.message)
        return;
    }

    if (claimdata.response.data) {
        if (claimdata.response.data.grouper.response != null) {
            if (claimdata.response.data.grouper.response.cbg.description.indexOf("KEMOTERAPI") !== -1) {
                if (claimdata.response.data.tarif_rs.obat_kemoterapi <= 0) {
                    isFinal.value = false
                    H.alert("warning", "Dilakukan prosedur kemoterapi, tapi nilai obat kemoterapi masih kosong.")
                    return
                }
            }
        }
    }
    let json = [
        {
            "metadata": {
                "method": "claim_final"
            },
            "data": {
                "nomor_sep": inacbg.nomor_sep,
                "coder_nik": inacbg.coder_nik,
            }
        }
    ]
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element = r.response.dataresponse[x];
            if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                    'nomor_sep': element.datarequest.data.nomor_sep,
                    'inacbg_status': element.datarequest.metadata.method
                })
                H.alert('success', element.dataresponse.metadata.message)
            } else {
                H.alert('error', element.dataresponse.metadata.message)
            }
        }
        saveStatus(arrStatus)
        isFinal.value = false
    }, (error) => {
        isFinal.value = false
    })
}
const reeditClaim = () => {
    confirm.require({
        group: 'positionDialog',
        message: 'Yakin edit ulang klaim ?',
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: async () => {

            isReedit.value = true
            await useApi().postBPJS('/bridging/inacbgs/save', {
                'data': [{
                    "metadata": {
                        "method": "reedit_claim"
                    },
                    "data": {
                        "nomor_sep": inacbg.nomor_sep,
                    }
                }]
            }).then((r) => {
                let arrStatus = []
                for (let x = 0; x < r.response.dataresponse.length; x++) {
                    const element = r.response.dataresponse[x];
                    if (element.dataresponse.metadata.code == 200) {
                        arrStatus.push({
                            'nomor_sep': element.datarequest.data.nomor_sep,
                            'inacbg_status': element.datarequest.metadata.method
                        })
                        H.alert('success', element.dataresponse.metadata.message)
                    } else {
                        H.alert('error', element.dataresponse.metadata.message)
                    }
                }
                saveStatus(arrStatus)
                isReedit.value = false
            }, (error) => {
                isReedit.value = false
            })
        }
    })
}
const hapusKlaim = () => {

    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: async () => {
            isHapus.value = true
            await useApi().postBPJS('/bridging/inacbgs/save', {
                'data': [{
                    "metadata": {
                        "method": "delete_claim"
                    },
                    "data": {
                        "nomor_sep": inacbg.nomor_sep,
                        "coder_nik": inacbg.coder_nik
                    }
                }]
            }).then((r) => {
                let arrStatus = []
                for (let x = 0; x < r.response.dataresponse.length; x++) {
                    const element = r.response.dataresponse[x];
                    if (element.dataresponse.metadata.code == 200) {
                        arrStatus.push({
                            'nomor_sep': element.datarequest.data.nomor_sep,
                            'inacbg_status': element.datarequest.metadata.method
                        })
                        H.alert('success', element.dataresponse.metadata.message)
                    } else {
                        H.alert('error', element.dataresponse.metadata.message)
                    }
                }
                saveStatus(arrStatus)
                isHapus.value = false
            }, (error) => {
                isHapus.value = false
            })
        },
        reject: () => {
        }
    });

}
const saveStatus = async (e: any) => {
    if (!e.length) return

    for (var ii = 0; ii < e.length; ii++) {
        const elem2 = e[ii]
        if (inacbg.nomor_sep == elem2.nomor_sep) {
            elem2.norec = NOREC_PD
        }
    }

    await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': e }).then(async (r) => {
        fetchStatus()
    })
}
const grouper = async (e: any) => {

    isGrouping.value = true
    await newKlaim()
    await useApi().postBPJS('/bridging/inacbgs/save', {
        'data': [{
            "metadata": {
                "method": "grouper",
                "stage": "1"
            },
            "data": {
                "nomor_sep": inacbg.nomor_sep
            }
        },]
    }).then((r) => {
        updateStatusGrouping(r)

        scrollTo('#form-step-4', 800, { offset: -20 })

        isGrouping.value = false
    }, (error) => {
        isGrouping.value = false
    })
}
const saveGrouping = async (e: any) => {
    if (!e.length) return

    for (var ii = 0; ii < e.length; ii++) {
        const elem2 = e[ii]
        elem2.norec = NOREC_PD
        elem2.jenis_rawat = inacbg.jenis_rawat
        let totaldijamin = 0
        let biayanaikkelas = 0
        if (elem2.jenis_rawat != 1) {
            totaldijamin = parseFloat(elem2.dataresponse.tarif_alt[2].tarif_inacbg)
        } else {
            let hakkelas = elem2.dataresponse.response.kelas
            if (hakkelas == "kelas_1") {
                totaldijamin = parseFloat(elem2.dataresponse.tarif_alt[0].tarif_inacbg)
            } else if (hakkelas == "kelas_2") {
                totaldijamin = parseFloat(elem2.dataresponse.tarif_alt[1].tarif_inacbg)
            } else if (hakkelas == "kelas_3") {
                totaldijamin = parseFloat(elem2.dataresponse.tarif_alt[2].tarif_inacbg)
            }

            biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
            if (biayanaikkelas < 0) {
                biayanaikkelas = 0
            }
        }
        if (inacbg.specialdrug) {
            elem2.dataresponse.specialdrug = inacbg.specialdrug
        }
        if (inacbg.specialinvestigation) {
            elem2.dataresponse.specialinvestigation = inacbg.specialinvestigation
        }
        if (inacbg.specialprosthesis) {
            elem2.dataresponse.specialprosthesis = inacbg.specialprosthesis
        }
        if (inacbg.specialprocedure) {
            elem2.dataresponse.specialprocedure = inacbg.specialprocedure
        }
        let totalTOPUP = 0
        if (elem2.dataresponse.response.special_cmg != undefined && elem2.dataresponse.response.special_cmg.length) {
            for (let z = 0; z < elem2.dataresponse.response.special_cmg.length; z++) {
                const elementZ = elem2.dataresponse.response.special_cmg[z];
                totalTOPUP = totalTOPUP + elementZ.tariff
            }
        }
        let json = {
            'norec': elem2.norec,
            'totaldijamin': totaldijamin,
            'biayanaikkelas': biayanaikkelas,
            'inacbg_grouper': elem2.dataresponse,
            'totaltopup': totalTOPUP,
        }
        resGrouper.value = elem2.dataresponse
        setHasilGrouper(resGrouper.value)
        await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)

    }

}
const kirimClaimDataCenter = async () => {
    isDC.value = true
    let json = [
        {
            "metadata": {
                "method": "send_claim_individual"
            },
            "data": {
                "nomor_sep": inacbg.nomor_sep
            }
        }
    ]
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element = r.response.dataresponse[x];
            if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                    'nomor_sep': element.datarequest.data.nomor_sep,
                    'inacbg_status': element.datarequest.metadata.method,
                    'kemkes_dc_status': element.dataresponse.response.data[0].kemkes_dc_status,
                    'bpjs_dc_status': element.dataresponse.response.data[0].bpjs_dc_status,
                    'cob_dc_status': element.dataresponse.response.data[0].cob_dc_status
                })
                H.alert('success', element.dataresponse.metadata.message)
            } else {
                H.alert('error', element.dataresponse.metadata.message)
            }
        }
        saveStatus(arrStatus)
        isDC.value = false
    }, (error) => {
        isDC.value = false
    })
}
const fetchStatus = async () => {
    await useApi().get('/bridging/inacbgs/get-status?norec=' + NOREC_PD).then(async (r) => {
        inacbg.inacbg_status = r.inacbg_status
        inacbg.kemkes_dc_status = r.kemkes_dc_status
        inacbg.bpjs_dc_status = r.bpjs_dc_status
        inacbg.cob_dc_status = r.cob_dc_status
    })
}
const fetchHasilGroup = async () => {
    inacbg.TOTALKLAIM = 0;
    await useApi().get('/bridging/inacbgs/get-status-grouping?norec=' + NOREC_PD).then(async (r) => {
        resGrouper.value = r
        if (resGrouper.value.specialdrug) {
            inacbg.specialdrug = resGrouper.value.specialdrug
        }
        if (resGrouper.value.specialinvestigation) {
            inacbg.specialinvestigation = resGrouper.value.specialinvestigation
        }
        if (resGrouper.value.specialprosthesis) {
            inacbg.specialprosthesis = resGrouper.value.specialprosthesis
        }
        if (resGrouper.value.specialprocedure) {
            inacbg.specialprocedure = resGrouper.value.specialprocedure
        }
        setHasilGrouper(resGrouper.value)
    })
}

const setHasilGrouper = (hasil: any) => {

    if (hasil != null && hasil.special_cmg_option && hasil.special_cmg_option.length > 0) {
        setCMG(hasil.special_cmg_option)
    }
    // if (hasil != null &&  hasil.response.special_cmg && hasil.response.special_cmg.length > 0) {
    //     setCMGHasil(hasil.response.special_cmg)
    // }
    hitungTotal()
}
const hitungTotal = () => {
    inacbg.TOTALKLAIM = 0;
    let elem2 = resGrouper.value
    let totaldijamin = 0
    let biayanaikkelas = 0
    if (inacbg.jenis_rawat == false) {
        totaldijamin = elem2.tarif_alt[2].tarif_inacbg
    } else {
        let hakkelas = elem2.response.kelas
        if (hakkelas == "kelas_1") {
            totaldijamin = elem2.tarif_alt[0].tarif_inacbg
        } else if (hakkelas == "kelas_2") {
            totaldijamin = elem2.tarif_alt[1].tarif_inacbg
        } else if (hakkelas == "kelas_3") {
            totaldijamin = elem2.tarif_alt[2].tarif_inacbg
        }

        biayanaikkelas = elem2.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
        if (biayanaikkelas < 0) {
            biayanaikkelas = 0
        }
    }
    inacbg.TOTALKLAIM = parseFloat(totaldijamin) + parseFloat(biayanaikkelas)
    if (elem2.response.sub_acute) {
        inacbg.TOTALKLAIM = inacbg.TOTALKLAIM + parseFloat(elem2.response.sub_acute.tariff)
    }
    if (elem2.response.chronic) {
        inacbg.TOTALKLAIM = inacbg.TOTALKLAIM + parseFloat(elem2.response.chronic.tariff)
    }
    if (elem2.response.special_cmg) {
        for (let i = 0; i < elem2.response.special_cmg.length; i++) {
            const element = elem2.response.special_cmg[i];
            inacbg.TOTALKLAIM = inacbg.TOTALKLAIM + parseFloat(element.tariff)
            if (element.type == 'Special Procedure') {
                inacbg.specialprocedureValue = element.tariff
                inacbg.specialprocedureLoad = false
            }
            if (element.type == 'Special Prosthesis') {
                inacbg.specialprosthesisValue = element.tariff
                inacbg.specialprosthesisLoad = false
            }
            if (element.type == 'Special Investigation') {
                inacbg.specialinvestigationLValue = element.tariff
                inacbg.specialinvestigationLoad = false
            }
            if (element.type == 'Special Drug') {
                inacbg.specialdrugValue = element.tariff
                inacbg.specialdrugLoad = false
            }
        }
    }
}
// const setCMGHasil = (res: any) => {
//     for (let i = 0; i < res.length; i++) {
//         const element = res[i];
//         if (element.type == 'Special Drug') {
//             resGrouper.value.response.special_cmg = {
//                 tariff_drug: element.tariff
//             }
//         }
//         if (element.type == 'Special Procedure') {
//             resGrouper.value.response.special_cmg = {
//                 tariff_procedure: element.tariff
//             }
//         }
//         if (element.type == 'Special Prosthesis') {
//             resGrouper.value.response.special_cmg = {
//                 tariff_prosthesis: element.tariff
//             }
//         }
//         if (element.type == 'Special Investigation') {
//             resGrouper.value.response.special_cmg = {
//                 tariff_investigation: element.tariff
//             }
//         }
//     }
// }
const getTarifTopUP = (type: any) => {
    if (resGrouper.value.response != undefined && resGrouper.value.response.special_cmg != undefined) {
        for (let i = 0; i < resGrouper.value.response.special_cmg.length; i++) {
            const element = resGrouper.value.response.special_cmg[i];
            if (element.type == type) {
                return element.tariff
            }
        }
    } else {
        return 0
    }

}
const setCMG = (res: any) => {
    H.alert('info', 'Terdeteksi Top-up CMG Options')

    let responOptions = res
    let spesialDrug = []
    let specialProcedure = []
    let specialProsthesis = []
    let specialInvestigation = []
    for (let i = 0; i < responOptions.length; i++) {
        const element = responOptions[i];
        if (element.type == 'Special Drug') {
            spesialDrug.push(element)
        }
        if (element.type == 'Special Procedure') {
            specialProcedure.push(element)
        }
        if (element.type == 'Special Prosthesis') {
            specialProsthesis.push(element)
        }
        if (element.type == 'Special Investigation') {
            specialInvestigation.push(element)
        }
    }
    d_specialdrug.value = spesialDrug
    d_specialprocedure.value = specialProcedure
    d_specialprosthesis.value = specialProsthesis
    d_specialinvestigation.value = specialInvestigation
}
const hitungSpecialCMG = async (e, nama) => {
    inacbg[nama] = e.value;
    let cmg = "";

    if (inacbg.specialprocedure) {
        inacbg.specialprocedureLoad = true
        if (cmg != "") {
            cmg = cmg + '#' + inacbg.specialprocedure.code
        } else {
            cmg = inacbg.specialprocedure.code
        }
    }
    if (inacbg.specialprosthesis) {
        inacbg.specialprosthesisLoad = true
        if (cmg != "") {
            cmg = cmg + '#' + inacbg.specialprosthesis.code
        } else {
            cmg = inacbg.specialprosthesis.code
        }
    }
    if (inacbg.specialinvestigation) {
        inacbg.specialinvestigationLoad = true
        if (cmg != "") {
            cmg = cmg + '#' + inacbg.specialinvestigation.code
        } else {
            cmg = inacbg.specialinvestigation.code
        }
    }
    if (inacbg.specialdrug) {
        inacbg.specialdrugLoad = true
        if (cmg != "") {
            cmg = cmg + '#' + inacbg.specialdrug.code
        } else {
            cmg = inacbg.specialdrug.code
        }
    }

    let dt1 = {}
    let dt2 = []
    dt1 = {
        "metadata": {
            "method": "grouper",
            "stage": "2"
        },
        "data": {
            "nomor_sep": inacbg.nomor_sep,
            "special_cmg": cmg//"ambil dari table hasil grouper 1"
        }
    }
    dt2.push(dt1)

    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': dt2 }).then(async (r) => {
        // inacbg.specialdrugLoad = false
        // inacbg.specialinvestigationLoad = false
        // inacbg.specialprosthesisLoad = false
        // inacbg.specialprocedureLoad = false
        let response = r.response.dataresponse[0].dataresponse
        if (response.metadata.code == 200) {
            // resGrouper.value.response.special_cmg = response.response.special_cmg
            await updateStatusGrouping(r)
            scrollTo('#form-step-4', 800, { offset: -20 })

        } else {
            H.alert('error', response.metadata.message)
        }

    })
}
const updateStatusGrouping = async (r: any) => {
    let arrStatus = []
    let arrGroup = []
    for (let x = 0; x < r.response.dataresponse.length; x++) {
        const element = r.response.dataresponse[x];
        if (element.dataresponse.metadata.code == 200
            && element.dataresponse.response.cbg.tariff != undefined) {
            arrGroup.push({
                'nomor_sep': element.datarequest.data.nomor_sep,
                'inacbg_status': element.dataresponse.metadata.method,
                'dataresponse': element.dataresponse
            })
            arrStatus.push({
                'nomor_sep': element.datarequest.data.nomor_sep,
                'inacbg_status': element.datarequest.metadata.method
            })
            H.alert('success', element.dataresponse.response ? element.dataresponse.response.cbg.description : element.dataresponse.metadata.message)
        } else {
            arrGroup.push({
                'nomor_sep': element.datarequest.data.nomor_sep,
                'inacbg_status': element.dataresponse.metadata.method,
                'dataresponse': null
            })
            arrStatus.push({
                'nomor_sep': element.datarequest.data.nomor_sep,
                'inacbg_status': element.datarequest.metadata.method
            })
            H.alert('error', element.dataresponse.response ? element.dataresponse.response.cbg.description : element.dataresponse.metadata.message)
        }
    }
    saveStatus(arrStatus)
    saveGrouping(arrGroup)
}
const batalTopUp = async () => {
    isLoadingBatal.value = true
    delete inacbg.specialdrug
    delete inacbg.specialinvestigation
    delete inacbg.specialprosthesis
    delete inacbg.specialprocedure
    inacbg.specialprocedureValue = 0
    inacbg.specialprosthesisValue = 0
    inacbg.specialinvestigationLValue = 0
    inacbg.specialdrugValue = 0
    resGrouper.value.special_cmg = null
    await grouper()
    isLoadingBatal.value = false
}
const savePemakaianAsuransi = async () => {
    if (inacbg.nomor_sep == undefined || inacbg.nomor_sep == '') {
        return
    }
    if (inacbg.nomor_kartu == undefined || inacbg.nomor_kartu == '') {
        return
    }
    let json = {
        'norec_pd': NOREC_PD,
        'nomor_sep': inacbg.nomor_sep,
        'nomor_kartu': inacbg.nomor_kartu,
        'namapasien': pasien.value.namapasien,
        'noregistrasi': item.noregistrasi,
        'nocm': pasien.value.nocm,
        'kpid': item.kpid,
        'nocmfk': pasien.value.nocmfk,
    }
    await useApi().postNoMessage('/bridging/inacbgs/save-pemakaian-asuransi', json)

}
const changeTarif = () => {
    inacbg.totalmappingtarif =
        (inacbg.tarif_rs.prosedur_non_bedah ? inacbg.tarif_rs.prosedur_non_bedah : 0) +
        (inacbg.tarif_rs.prosedur_bedah ? inacbg.tarif_rs.prosedur_bedah : 0) +
        (inacbg.tarif_rs.konsultasi ? inacbg.tarif_rs.konsultasi : 0) +
        (inacbg.tarif_rs.tenaga_ahli ? inacbg.tarif_rs.tenaga_ahli : 0) +
        (inacbg.tarif_rs.keperawatan ? inacbg.tarif_rs.keperawatan : 0) +
        (inacbg.tarif_rs.penunjang ? inacbg.tarif_rs.penunjang : 0) +
        (inacbg.tarif_rs.radiologi ? inacbg.tarif_rs.radiologi : 0) +
        (inacbg.tarif_rs.laboratorium ? inacbg.tarif_rs.laboratorium : 0) +
        (inacbg.tarif_rs.pelayanan_darah ? inacbg.tarif_rs.pelayanan_darah : 0) +
        (inacbg.tarif_rs.rehabilitasi ? inacbg.tarif_rs.rehabilitasi : 0) +
        (inacbg.tarif_rs.kamar ? inacbg.tarif_rs.kamar : 0) +
        (inacbg.tarif_rs.rawat_intensif ? inacbg.tarif_rs.rawat_intensif : 0) +
        (inacbg.tarif_rs.obat ? inacbg.tarif_rs.obat : 0) +
        (inacbg.tarif_rs.obat_kronis ? inacbg.tarif_rs.obat_kronis : 0) +
        (inacbg.tarif_rs.obat_kemoterapi ? inacbg.tarif_rs.obat_kemoterapi : 0) +
        (inacbg.tarif_rs.alkes ? inacbg.tarif_rs.alkes : 0) +
        (inacbg.tarif_rs.bmhp ? inacbg.tarif_rs.bmhp : 0) +
        (inacbg.tarif_rs.sewa_alat ? inacbg.tarif_rs.sewa_alat : 0)
}
const validasisitb = async () => {
    let dt1 = {}
    let dt2 = []
    dt1 = {
        "metadata": {
            "method": "sitb_validate",
        },
        "data": {
            "nomor_sep": inacbg.nomor_sep,
            "nomor_register_sitb": inacbg.nomorsitb//"ambil dari table hasil grouper 1"
        }
    }
    dt2.push(dt1)

    isSITB.value = true
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': dt2 }).then(async (r) => {
        isSITB.value = false
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element = r.response.dataresponse[x];
            if (element.dataresponse.response.validation) {
                arrStatus.push({
                    'nomor_sep': element.datarequest.data.nomor_sep,
                    'inacbg_status': element.datarequest.metadata.method,
                    "sitb_id": element.dataresponse.response.validation ? element.dataresponse.response.validation.data[0].id : null,
                })
                H.alert('success', element.dataresponse.response.detail)
                saveStatus(arrStatus)
            } else {
                H.alert('error', element.dataresponse.response.detail)
            }
        }
    })
}
function addNewItem(x) {

    delivery.value.push({
        no: x,
    });
}
function removeItem(index: any) {
    delivery.value.splice(index, 1)
}
const resumeMedis = () => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    H.cacheHelper().set('xxx_cache_menu_' + ID_PASIEN, {
        'menu': [
            { "label": "Resume Medis", "icon": "pi pi-fw pi-file", "url_form": "ringkasan-keluar" }
        ],
        'active': 'Resume Medis',
        'url_form': "ringkasan-keluar",
        'collection': 'ResumeMedis'
    })
    router.push({
        name: 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        query: {
            pageKD: route.query.page,
            nocmfk: ID_PASIEN,
            norec_pd: item.NOREC_PD,
            norec_apd: item.NOREC_APD
        }
    })
}

const inputObat = async () => {
    // dataSourceX.value = []

    dataSourceXDokter.value.loading = true
    await useApi().get(
        `/farmasi/riwayat-order-resep?norec_pd=${item.NOREC_PD}`).then((response: any) => {
            if (response[0] && response[0].details) {
                let resep = ''; 
                response[0].details.forEach((detail) => {
                    resep += `${detail.namaproduk}, `;
                });
                input.value.resepObat = resep; 
            } else {
                console.log('Tidak ada data details');
            }


            dataSourceXDokter.value.loading = false
            dataSourceXDokter.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXDokter.value.loading = false
        })
}

const inputTindakan = async () => {
    // dataSourceX.value = []

    dataSourceXDokter.value.loading = true
    await useApi().get(
        `/kasir/billing/koding?norec_pd=${item.NOREC_PD}&istindakan=true`).then(async (response: any) => {
            if (response.length) {
                let tindakan = '';                 
                response.forEach((detail) => {  
                    tindakan += `${detail.namaproduk}, \n`;                  
                    // console.log(detail);
                });
                input.value.tindakan = tindakan; 
            } else {
                console.log('Tidak ada data details');
            }


            dataSourceXDokter.value.loading = false
            dataSourceXDokter.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXDokter.value.loading = false
        })
}

const inputLab = async () => {
    dataSourceXDokter.value.loading = true
    await useApi().get(
        `laboratorium/riwayat-order?norec_pd=${item.NOREC_PD}`).then((response: any) => {
            if (response[0] && response[0].details) {
                let lab = ''; 
                response[0].details.forEach((detail) => {
                    lab += `${detail.namaproduk}, `;
                });
                input.value.laboratoriumAja = lab; 
            } else {
                console.log('Tidak ada data details');
            }


            dataSourceXDokter.value.loading = false
            dataSourceXDokter.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXDokter.value.loading = false
        })
}

const inputRad = async () => {
    dataSourceXDokter.value.loading = true
    await useApi().get(
        `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`).then((response: any) => {
            if (response.length) {
                let rad = '';                 
                response.detail[0].details.forEach((detail) => {
                    rad += `${detail.namaproduk}, `;
                });
                input.value.radiologiAja = rad; 
            } else {
                console.log('Tidak ada data details');
            }


            dataSourceXDokter.value.loading = false
            dataSourceXDokter.value = response.data.sort(compare)

        }).catch((e: any) => {
            dataSourceXDokter.value.loading = false
        })
}
const input: any = ref({})
const fetchDiagnosisData = async () => {
    try {
        // Fetch Asesmen Medis Rawat Jalan
        let response = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenMedisRawatJalan&field=TADiagnosa,MOI`);
        if (response) {
            if (response.TADiagnosa) {
                input.value.TADiagnosa = response.TADiagnosa;
            }
        } 

        let response2 = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenMedisRawatJalan&field=TADiagnosa,MOI`);
        if (response2) {
            if (response2.MOI) {
                item.moi = response2.MOI;
            }
        } 

        // Fetch Ringkasan Keluar jika data pertama kosong
        if (!input.value.TADiagnosa) {
            response = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=RingkasanKeluar&field=TADiagnosisPrimer`);
            if (response?.TADiagnosisPrimer) {
                input.value.TADiagnosa = response.TADiagnosisPrimer;
            }
        }

        // Fetch Asesmen Awal Medis Gawat Darurat jika masih kosong
        if (!input.value.TADiagnosa) {
            response = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI`);
            if (response) {
                if (response.TADiagnosis) {
                    input.value.TADiagnosa = response.TADiagnosis;
                }
            }
        }

        console.log('MOI', item.moi)
        if (item.moi == undefined) {
            let responses = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI`);
            if (responses) {
                if (responses.TAMOI) {
                    item.moi = responses.TAMOI;
                }
            }
        }

        // Fetch Ringkasan Masuk dan Keluar Rawat Inap
        response = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=RingkasanMasukDanKeluarRawatInap&field=DiagnosaUtama,Diagnosasekunder1,Diagnosasekunder2,Diagnosasekunder3,Diagnosasekunder4,Diagnosasekunder5,Diagnosasekunder6`);
        if (response?.DiagnosaUtama) {
            let data = '';
            data += response.DiagnosaUtama ? `${response.DiagnosaUtama}; ` : '' 
            data += response.Diagnosasekunder1 ? `${response.Diagnosasekunder1}; ` : '' 
            data += response.Diagnosasekunder2 ? `${response.Diagnosasekunder2}; ` : ''
            data += response.Diagnosasekunder3 ? `${response.Diagnosasekunder3}; ` : ''
            data += response.Diagnosasekunder4 ? `${response.Diagnosasekunder4}; ` : ''
            data += response.Diagnosasekunder5 ? `${response.Diagnosasekunder5}; ` : ''
            data += response.Diagnosasekunder6 ? `${response.Diagnosasekunder6}; ` : ''
            input.value.TADiagnosaKeluar = data;
        }

    } catch (error) {
        console.error('Terjadi kesalahan saat mengambil data diagnosis dan MOI:', error);
    }
};

watch(
    () => [
        inacbg.tarif_rs.prosedur_non_bedah,
        inacbg.tarif_rs.prosedur_bedah,
        inacbg.tarif_rs.konsultasi,
        inacbg.tarif_rs.tenaga_ahli,
        inacbg.tarif_rs.keperawatan,
        inacbg.tarif_rs.penunjang,
        inacbg.tarif_rs.radiologi,
        inacbg.tarif_rs.laboratorium,
        inacbg.tarif_rs.pelayanan_darah,
        inacbg.tarif_rs.rehabilitasi,
        inacbg.tarif_rs.kamar,
        inacbg.tarif_rs.rawat_intensif,
        inacbg.tarif_rs.obat,
        inacbg.tarif_rs.obat_kronis,
        inacbg.tarif_rs.obat_kemoterapi,
        inacbg.tarif_rs.alkes,
        inacbg.tarif_rs.bmhp,
        inacbg.tarif_rs.sewa_alat,
    ],
    () => {
        changeTarif()
    }
)

watch(
  () => item.diagnosa10,
  (newValue, oldValue) => {
    if(item.diagnosa10 != undefined){
        if(item.diagnosa10.id != undefined){
            simpanICD10()
        }
    }
  }
)

watch(
  () => item.diagnosa9,
  (newValue, oldValue) => {
    if(item.diagnosa9 != undefined){
        if(item.diagnosa9.id != undefined){
            simpanICD9()
        }
    }
  }
)

watch(
  () => item.diagnosa10Kematian,
  (newValue, oldValue) => {
    if(item.diagnosa10Kematian != undefined){
        if(item.diagnosa10Kematian.id != undefined){
            simpanICD10Kematian()
        }
    }
  }
)

watch(
  () => item.diagnosa10MOI,
  (newValue, oldValue) => {
    if(item.diagnosa10MOI != undefined){
        if(item.diagnosa10MOI.id != undefined){
            simpanICD10MOI()
        }
    }
  }
)
watch(
  () => item.diagnosaMasuk,
  (newValue, oldValue) => {
    if(item.diagnosaMasuk != undefined){
        if(item.diagnosaMasuk.id != undefined){
            simpanICD10Masuk()
        }
    }
  }
)
pasienByID(ID_PASIEN)
// inacbgDetail(NOREC_PD)
inputObat()
inputTindakan()
inputLab()
inputRad()
fetchHasilGroup()
dropdownList()
riwayatDiagnosa10()
riwayatDiagnosaO()
riwayatDiagnosa9()
fetchDiagnosisData()
// riwayatMOI()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/inacbgs/inacbgs-detail';
</style>