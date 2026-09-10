<template>
    <div v-if="pasien.noregistrasi != undefined" :class="[isaktif == true ? 'block-header info' : 'block-header info']"
        style="height: auto;">
        <div class="right" v-if="pasien.nocm != undefined">
            <div class="job-card-subtitle-2">
                <div class="columns is-multiline">
                    <div class="column is-10">
                        <h3 class="emr" style="white-space: pre-wrap;">
                            <i :class="(pasien.jeniskelamin.toUpperCase() == 'PEREMPUAN' ? 'fas fa-venus' : 'fas fa-mars')"
                                :style="(pasien.jeniskelamin.toUpperCase() === 'PEREMPUAN' ? 'color: pink;' : 'color: lightblue;')"
                                aria-hidden="true" style="font-size: 20px;"></i> &nbsp;{{ pasien.namapasien }}
                        </h3>
                        <h3 class="emr" style="font-size: 14px;">
                            {{ pasien.jeniskelamin }}
                        </h3>
                        <h3 class="emr" style="font-size: 14px;">{{ pasien.noregistrasi }}-{{ pasien.nocm }}</h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2" style="margin-top: 20px;">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px; white-space: pre-wrap;">
                            <i class="fas fa-hospital mr-2" aria-hidden="true"></i> {{ pasien.alamatlengkap }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i class="fas fa-calendar mr-2" aria-hidden="true"></i> {{ pasien.tgllahir }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i aria-hidden="true" class="fas fa-user mr-2"></i> {{ pasien.umur }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i class="fas fa-notes-medical mr-2" aria-hidden="true"></i> {{ pasien.kelompokpasien }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2" v-if="pasien.kelompokpasien == 'BPJS'">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i class="fas fa-angle-double-up mr-2" aria-hidden="true"></i> {{
                            registrasi.klsrawathak_nama ?? '-' }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i class="fas fa-user mr-2" aria-hidden="true"></i>
                            {{ pasien.penanggungjawab ? pasien.penanggungjawab : '-' }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i class="fas fa-user-circle mr-2" aria-hidden="true"></i>
                            {{ pasien.hubungankeluarga ? pasien.hubungankeluarga : '-' }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="job-card-subtitle-2 mb-2">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h3 class="emr" style="font-size: 12px;">
                            <i data-icon="feather:dollar-sign" class="mr-2 iconify" aria-hidden="true"></i>
                            {{ registrasi.billing ? registrasi.billing : '-' }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="column pb-2"></div>
        <div class="column is-12 mx-auto text-center p-0 mb-3" v-if="pasien.iseval">
            <VTag color="danger"
                :label="'Perlu Evaluasi ditanggal : ' + H.formatDate(pasien.evaluasi_pasien.tanggal, 'DD-MM-YYYY')" />
        </div>
        <div class="column is-12 mx-auto text-center p-0 mb-3" v-if="registrasi?.apd?.konsul">
            <VButton raised color="info" class="btn-sm mt-3" @click="modalIntru" v-tooltip.bubble="'Lihat Intruksi'">
                Pasien Konsultasi
            </VButton>
        </div>
        <div class="is-flex border-blue mt-2 mb-2 card-catatan-pasien" v-if="pasien.nocm != undefined">
            <div class="column is-10 mb-2-min">
                <div class="job-card-subtitle-2" style="justify-content: left;">
                    <i aria-hidden="true" class="pi pi-book"></i>
                    <p class="block-text  mt-1-min" style="color: var(--dark-text);">
                        {{ pasien.catatan ?? "Belum ada catatan" }}
                    </p>
                </div>
            </div>
            <div class="column is-2 py-1 px-0">
                <VIconButton icon="feather:plus" @click="openModal" color="info" raised circle
                    v-tooltip.bubble="'Alergi Pasien'"> </VIconButton>
            </div>
        </div>
        <div class="is-flex border-blue mt-2 mb-2" :style="pasien.alergi ? 'background-color: #f5d67a;' : 'background-color: #A0E3C5'" v-if="pasien.nocm != undefined">
            <div class="column is-10">
                <div class="job-card-subtitle-2" style="justify-content: left;">
                    <i aria-hidden="true" class="pi pi-exclamation-triangle"
                        :style="pasien.alergi ? 'color: black;' : 'color: white;'"></i>
                    <p class="block-text" style="color: var(--dark-text);">
                        {{ pasien.alergi ?? "Belum ada alergi" }}
                    </p>
                </div>
            </div>
            <div class="column is-2 py-1 px-0">
                <VIconButton :icon="pasien.alergi ? 'feather:edit' : 'feather:plus'" @click="emits('openModalAlergi')"
                    :color="pasien.alergi ? 'primary' : 'info'" raised circle v-tooltip.bubble="'Tambah Alergi'">
                </VIconButton>
            </div>
        </div>
    </div>
    <div v-else :class="[isaktif == true ? 'block-header' : 'block-header nonaktif']">
        <div class="left" v-if="pasien.nocm != undefined">
            <div class="current-user">
                <VAvatar class="is-head avatar-wrapper is-warning" :picture="pasien.jeniskelamin.toUpperCase() ==
                    'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'" squared />
                <h3>{{ pasien.namapasien }}</h3>
                <p class="block-text">{{ pasien.nocm }}</p>
            </div>
        </div>
        <div class="center" v-if="pasien.nocm != undefined">
            <div class="columns">
                <div class="column">
                    <h4 class="block-heading">NIK</h4>
                    <p class="block-text"> {{ pasien.noidentitas }}</p>
                    <h4 class="block-heading">Umur </h4>
                    <p class="block-text"> {{ pasien.umur }}</p>
                </div>
                <div class="column">
                    <h4 class="block-heading">No HP </h4>
                    <p class="block-text"> {{ pasien.nohp }}</p>
                    <h4 class="block-heading">Kelamin</h4>
                    <p class="block-text"> {{ pasien.jeniskelamin }}</p>
                </div>

            </div>
        </div>
        <div class="right" v-if="pasien.nocm != undefined">
            <div class="columns">
                <div class="column">
                    <h4 class="block-heading">Ruangan </h4>
                    <p class="block-text">{{ pasien.namaruangan }}</p>
                    <h4 class="block-heading">Tgl Registrasi</h4>
                    <p class="block-text">{{ pasien.tglregistrasi }} </p>
                </div>
                <div class="column">
                    <h4 class="block-heading">Pembiayaan</h4>
                    <p class="block-text">{{ pasien.kelompokpasien }}</p>
                    <h4 class="block-heading">Status</h4>
                    <VTag color="warning" :label="pasien.status" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
const props = withDefaults(
    defineProps<{
        isaktif?: boolean
        pasien?: any
        registrasi?: any
    }>(),
    {
        pasien: {},
        isaktif: true,
        registrasi: {}
    }
)
const emits = defineEmits();

const openModal = () => {
    emits('open-modal');
};

const modalIntru = () => {
    emits('open-intruksi');
};
const openModalKanker = () => {
    emits('open-histori-kanker');
};
</script>
<style lang="scss">
.info {
    display: block !important;
    align-items: center !important;
}

.hr-dashboard .block-header {
    display: flex;
    border-radius: 16px;
    padding: 32px !important;
    // background: #1f8658 !important;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);
}

.card-catatan-pasien {
    background-color: #A0E3C5;
}

.is-dark {
    .hr-dashboard .block-header {
        background: var(--dark-sidebar) !important;
    }

    .card-catatan-pasien {
        border-color: var(--dark-sidebar-light-12);
        background: var(--dark-sidebar-light-4);
    }
}

.hr-dashboard .block-header .left {
    width: 30%;
    padding-left: 20px;
}

.hr-dashboard .block-header .right {
    width: 70% !important;
    padding-left: 40px;
}

.badge-emr {
    padding: 9px 12px 10px 11px;
    border-radius: 50%;
    background: #116863;
    color: white;
    height: 30px;
    width: 30px;
}

h3.emr {

    font-family: var(--font-alt);
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--white);
    line-height: 1.5;
    width: 200px !important;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}

@media (max-width: 1144px) {
    h3.emr {
        width: 140px !important;
    }
}

.job-card-subtitle-2 {
    color: var(--subtitle-color);
    font-family: var(--font);
    font-size: 0.95rem;
    line-height: 1.6em;
    margin-top: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.job-card-subtitle-2 i {
    color: white
}

.job-card-subtitle-2 svg {
    color: white
}

.hr-dashboard .block-header .block-text {
    margin-bottom: 0 !important;
    margin-left: 10px;
    width: 200px !important;
}

.hr-dashboard .block-header {
    height: 300px;
}

.border-blue {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: 16px;
    padding: 0 10px 0 10px;
    justify-content: center;
    align-items: center;
}

.button.v-button.btn-sm {
    padding: 0px 9px;
    height: 29px;
    line-height: 1.1;
    font-size: 0.75rem;
    font-family: var(--font);
    transition: all 0.3s;
}

.hr-dashboard .block-header {
    flex-direction: column;
    padding: 30px;
    height: 100% !important;
    overflow: auto;
    width: 100%;
    margin-top: 20px;
}

@media only screen and (max-width: 767px) {
    .hr-dashboard .block-header {
        flex-direction: column;
        padding: 30px;
        height: 100% !important;
        overflow: auto;
        width: 100%;
        margin-top: 20px;
    }

    h3.emr {
        width: 100%;
    }
}

@media only screen and (max-width: 997px) {
    .job-card-subtitle-2 h3.emr {
        font-size: 8pt !important;
    }

    .riwayat-reg-mobile {
        display: flex;
        flex-direction: column;
    }
}
</style>
