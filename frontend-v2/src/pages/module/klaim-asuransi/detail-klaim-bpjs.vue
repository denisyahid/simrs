<script setup lang="ts">
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from "vue-router";
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import FloatingButton from "./float-button.vue";
import * as H from '/@src/utils/appHelper'
import TLaboratorium from './t-laboratorium.vue'
import TRadiologi from './t-radiologi.vue'
import TBerkasemr from './t-berkas-emr.vue'
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const router = useRouter()
const activeTab = ref('laboratorium')
const colors = ['primary', 'info', 'success', 'warning', 'danger', 'purple']
const route = useRoute();
const pasienData: any = ref([])
const headerpasienData: any = ref([])
const vitalSignData: any = ref([])
const hasillab: any = ref([])
const hasilrad: any = ref([])
const listemr: any = ref([])
const isLoading: any = ref(false)
const isLoadingTab: any = ref(false)
const fileData = computed(() => {
    return [
        { id: 1, name: 'Surat Elegebilitas Peserta', color: colors[0], click: cetakSEP },
        { id: 2, name: 'Resume Medis', color: colors[1], click: cetakResume },
        { id: 3, name: 'Billing', color: colors[4], click: cetakBilling },
        { id: 4, name: 'E-Klaim', color: colors[3], click: cetakEklaim },
        { id: 5, name: 'Dokumen Upload', color: colors[5], click: cetakDokumenUpload },
    ]
})
const fetchPasien = async () => {
    try {
        isLoading.value = true
        const response = await useApi().get(`/bridging/klaim/get-daftar-klaim?norec_pd=${route.query.norec_pd}`)
        isLoading.value = false
        pasienData.value = response[0]
        fetchHeaderPasien(response[0].tglregistrasi, response[0].tglregistrasi)
    } catch(error) {
        isLoading.value = false
        console.log(error)
    }
}
const fetchHeaderPasien = async (dari: any, sampai: any) => {
    isLoading.value = true
    const daridateonly = H.formatDate(dari, "YYYY-MM-DD")
    const sampaidateonly = H.formatDate(sampai, "YYYY-MM-DD")
    const response = await useApi().get(`/emr/header-pasien?nocmfk=${route.query.nocmfk}&norec_pd=${route.query.norec_pd}&dari=${daridateonly}&sampai=${sampaidateonly}`)
    isLoading.value = false
    headerpasienData.value = response
}
const fetchVitalSign = async () => {
    try {
        isLoading.value = true
        const response = await useApi().get(`/emr/get-emr?nocmfk=${route.query.nocmfk}&norec_pd=${route.query.norec_pd}&collection=VitalSign`)
        isLoading.value = false
        vitalSignData.value = response[0]
    } catch(error) {
        isLoading.value = false
        console.log(error)
    }
}
const fetchHasilLab = async () => {
    try {
        isLoadingTab.value = true
        const response = await useApi().get(`/bridging/klaim/get-daftar-hasil-lab?norec_pd=${route.query.norec_pd}`)
        isLoadingTab.value = false
        hasillab.value = response.data
    } catch(error) {
        isLoadingTab.value = false
        console.log(error)
    }
}
const fetchExpertise = async () => {
    try {
        isLoadingTab.value = true
        const response = await useApi().get(`/bridging/klaim/get-daftar-expertise-radiologi?norec_pd=${route.query.norec_pd}`)
        isLoadingTab.value = false
        hasilrad.value = response
    } catch(error) {
        isLoadingTab.value = false
        console.log(error)
    }
}
const fetchBerkasEmr = async () => {
    try {
        isLoadingTab.value = true
        const response = await useApi().get(`/bridging/klaim/get-daftar-emr?norec_pd=${route.query.norec_pd}`)
        isLoadingTab.value = false
        for (let i = 0; i < response.length; i++) {
            response[i].hover = false;   
        }
        listemr.value = response
    } catch(error) {
        isLoadingTab.value = false
        console.log(error)
    }
}
const handleClick = (func: any) => {
    // Do something based on the click property
    // For example, call a function dynamically
    const clickFunction = func;
    if (clickFunction && typeof clickFunction === 'function') {
        clickFunction();
    }
}
const cetakSEP = () => {
    H.printBlade(`registrasi/pemakaian-asuransi/sep?nosep=${pasienData.value.nosep}&pdf=true`);
}
const cetakResume = () => {
    H.printBlade(`emr/cetak/resumeMedis?noregistrasi=${pasienData.value.noregistrasi}&pdf=true`);
}
const cetakBilling = () => {
  H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasienData.value.noregistrasi}&pdf=true`);
}
const cetakEklaim = async () => {
    let json = [
        {
            "metadata": {
                "method": "claim_print"
            },
            "data": {
                "nomor_sep": pasienData.value.nosep
            }
        }
    ]
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
        let arrStatus = []
        for (let x = 0; x < r.response.dataresponse.length; x++) {
            const element: any = r.response.dataresponse[x];
            if (element.dataresponse.metadata.code == 200) {
                const byteCharacters = atob(element.dataresponse.data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });
                const pdfUrl = URL.createObjectURL(blob);
                const printWindow = window.open(pdfUrl, '_blank');
        
                H.alert('success', element.dataresponse.metadata.message)
            } else {
                H.alert('error', element.dataresponse.metadata.message)
            }
        }
    }, (error) => {
        H.alert('error', error)
    })
}
const cetakDokumenUpload = () => {
    H.open(`bridging/inacbgs/bundle-dokumen?noregistrasi=${pasienData.value.noregistrasi}`);
}
const getRandomArbitrary = (min: any, max: any) => {
    return Math.random() * (max - min) + min;
}
const clickTab = async (e: any) => {
    switch (e) {
        case "laboratorium":
            fetchHasilLab()
            break;
        case "radiologi":
            fetchExpertise()
            break;
        case "bekasemr":
            fetchBerkasEmr()
            break;
    }
    activeTab.value = e
}
const showUlasan = () => {

}
const backPage = () => {
    router.push({
        name: `module-klaim-asuransi-klaim-bpjs`,
    })
//   window.history.back()
}

onMounted(() => {
    fetchPasien()
    fetchVitalSign()
    fetchHasilLab()
})
</script>

<template>
    <FloatingButton @click="showUlasan()" />
    <VCard radius="smooth" elevated class="mb-3-min br-16">
        <div class="page-content-inner">
            <div class="lifestyle-dashboard lifestyle-dashboard-v3">
                <div class="column is-multiline mb-3">
                  <VIconButton icon="feather:arrow-left" light dark-outlined @click="backPage()" />
                  <span class="title-emr ml-3">Detail Klaim</span>
                </div>
                <div v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="200px" width="100%" class="mb-5" />
                    </VPlaceloadWrap>
                </div>
                <div class="illustration-header" v-else>
                    <div class="header-image">
                        <img src="/@src/assets/illustrations/dashboards/lifestyle/doctor.svg" alt="" />
                    </div>
                    <div class="header-meta">
                        <h3>{{ pasienData.nocm }} - {{ pasienData.namapasien?.toUpperCase() }}</h3>
                        <p>{{ pasienData.namaruangan }}</p>
                        <div class="summary-stats mb-3">
                            <div class="summary-stat">
                                <span>{{ pasienData.nobpjs }}</span>
                                <span>No Peserta</span>
                            </div>
                            <div class="summary-stat">
                                <span>{{ pasienData.noregistrasi }}</span>
                                <span>No Registrasi</span>
                            </div>
                            <div class="summary-stat">
                                <span>{{ pasienData.umur }} Thn</span>
                                <span>Umur</span>
                            </div>
                            <div class="summary-stat">
                                <span>{{ pasienData.namakelas }}</span>
                                <span>Kelas</span>
                            </div>
                            <div class="summary-stat h-hidden-tablet-p">
                                <span>{{ pasienData.nosep }}</span>
                                <span>SEP</span>
                            </div>
                        </div>
                        <div class="summary-stats">
                            <div class="summary-stat">
                                <span>{{ H.formatDate(pasienData.tgllahir, "DD-MM-YYYY") }}</span>
                                <span>Tgl Lahir</span>
                            </div>
                            <div class="summary-stat">
                                <span>{{ H.formatDate(pasienData.tglregistrasi, "DD-MM-YYYY HH:mm") }}</span>
                                <span>Tgl Registrasi</span>
                            </div>
                            <div class="summary-stat">
                                <span>{{ H.formatDate(pasienData.tglpulang, "DD-MM-YYYY HH:mm") }}</span>
                                <span>Tgl Pulang</span>
                            </div>
                            <div class="summary-stat h-hidden-tablet-p">
                                <span>{{ H.formatRupiah(pasienData.inacbg_totalgrouper, "Rp. ") }}</span>
                                <span>Grouping</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="130px" width="25%" class="mr-3 mb-5" />
                        <VPlaceload height="130px" width="25%" class="mr-3 mb-5" />
                        <VPlaceload height="130px" width="25%" class="mr-3 mb-5" />
                        <VPlaceload height="130px" width="25%" class="mb-5" />
                    </VPlaceloadWrap>
                </div>
                <div class="columns is-multiline is-flex-tablet-p mb-5" v-else>
                    <!--Tile-->
                    <div class="column is-3">
                        <div class="health-tile">
                            <div class="tile-head">
                                <VIconBox color="primary">
                                    <i aria-hidden="true" class="fas fa-thermometer-half"></i>
                                </VIconBox>
                                <h4>
                                    <span class="dark-inverted">{{ vitalSignData?.suhu ? vitalSignData.suhu: "-" }}</span>
                                    <span>Celcius</span>
                                </h4>
                            </div>
                            <h3 class="dark-inverted">Suhu</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit Illis videtur.</p>
                        </div>
                    </div>

                    <!--Tile-->
                    <div class="column is-3">
                        <div class="health-tile">
                            <div class="tile-head">
                                <VIconBox color="primary">
                                    <i aria-hidden="true" class="fas fa-heart"></i>
                                </VIconBox>
                                <h4>
                                    <span class="dark-inverted">{{ vitalSignData?.nadi ? vitalSignData.nadi : "-" }}</span>
                                    <span>X/Menit</span>
                                </h4>
                            </div>
                            <h3 class="dark-inverted">Nadi</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit Illis videtur.</p>
                        </div>
                    </div>

                    <!--Tile-->
                    <div class="column is-3">
                        <div class="health-tile">
                            <div class="tile-head">
                                <VIconBox color="primary">
                                    <i aria-hidden="true" class="fas fa-pump-medical"></i>
                                </VIconBox>
                                <h4>
                                    <span class="dark-inverted">{{ vitalSignData?.tekananDarah ? vitalSignData.tekananDarah : "-" }}</span>
                                    <span>mmHg</span>
                                </h4>
                            </div>
                            <h3 class="dark-inverted">Tekanan Darah</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit Illis videtur.</p>
                        </div>
                    </div>

                    <!--Tile-->
                    <div class="column is-3">
                        <div class="health-tile">
                            <div class="tile-head">
                                <VIconBox color="primary">
                                    <i aria-hidden="true" class="fas fa-weight"></i>
                                </VIconBox>
                                <h4>
                                    <span class="dark-inverted">{{ vitalSignData?.beratBadan ? vitalSignData.beratBadan : "-" }}</span>
                                    <span>Kg</span>
                                </h4>
                            </div>
                            <h3 class="dark-inverted">Berat Badan</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit Illis videtur.</p>
                        </div>
                    </div>
                </div>

                <div v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="80px" width="33%" class="mr-3 mb-5" />
                        <VPlaceload height="80px" width="33%" class="mr-3 mb-5" />
                        <VPlaceload height="80px" width="33%" class="mb-5" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap>
                        <VPlaceload height="80px" width="33%" class="mr-3 mb-5" />
                        <VPlaceload height="80px" width="33%" class="mb-5" />
                    </VPlaceloadWrap>
                </div>
                <TransitionGroup name="list" tag="div" class="columns is-multiline mb-5" v-else>
                    <!--Grid item-->
                    <div v-for="item in fileData" :key="item.id" class="column is-4 p-2" @click="() => handleClick(item.click)">
                        <div class="tile-grid-item">
                            <div class="tile-grid-item-inner">
                                <VIconBox size="medium" :color="item.color">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                </VIconBox>
                                <div class="meta">
                                    <span class="dark-inverted">{{ item.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>

                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="tabs-wrapper">
                            <div class="tabs-inner">
                                <div class="tabs">
                                    <ul>
                                        <li :class="[activeTab === 'laboratorium' && 'is-active']">
                                            <a tabindex="0" @keydown.space.prevent="activeTab = 'laboratorium'"
                                                @click="clickTab('laboratorium')">LABORATORIUM</a>
                                        </li>
                                        <li :class="[activeTab === 'radiologi' && 'is-active']">
                                            <a tabindex="0" @keydown.space.prevent="activeTab = 'radiologi'"
                                                @click="clickTab('radiologi')">RADIOLOGI</a>
                                        </li>
                                        <li :class="[activeTab === 'bekasemr' && 'is-active']">
                                            <a tabindex="0" @keydown.space.prevent="activeTab = 'bekasemr'"
                                                @click="clickTab('bekasemr')">BERKAS ELEKTRONIK</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="overview-ta" class="tab-content is-active pb-5" v-if="isLoadingTab">
                                <VPlaceloadWrap>
                                    <VPlaceload height="200px" width="100%" class="mb-5" />
                                </VPlaceloadWrap>
                            </div>
                            <div id="overview-tab" class="tab-content is-active px-5 pb-5" v-else>
                                <TLaboratorium :hasillab="hasillab" v-if="activeTab === 'laboratorium'" />
                                <TRadiologi :hasilrad="hasilrad" v-else-if="activeTab === 'radiologi'" />
                                <TBerkasemr :listemr="listemr" :headerpasienData="headerpasienData" :nopeserta="pasienData.nobpjs" :nocmfk="pasienData.nocmfk" v-else-if="activeTab === 'bekasemr'" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </VCard>
</template>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.lifestyle-dashboard-v3 {
    .illustration-header {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 16px;
        background: var(--primary-dark-24);
        font-family: var(--font);
        margin-bottom: 30px;

        .header-image {
            position: relative;
            height: 168px;
            width: 280px;

            img {
                position: absolute;
                top: -76px;
                left: -30px;
                display: block;
                pointer-events: none;
            }
        }

        .header-meta {
            margin-left: 0;

            h3 {
                color: var(--smoke-white);
                font-family: var(--font-alt);
                font-weight: 700;
                font-size: 1.3rem;
            }

            p {
                font-weight: 400;
                color: var(--smoke-white-dark-16);
                margin-bottom: 16px;
            }

            .summary-stats {
                display: flex;

                .summary-stat {
                    margin-right: 30px;

                    span {
                        font-family: var(--font);
                        display: block;

                        &:first-child {
                            font-size: 1.1rem;
                            font-family: var(--font-alt);
                            color: var(--smoke-white);
                        }

                        &:nth-child(2) {
                            color: var(--primary-light-18);
                            font-size: 0.85rem;
                        }
                    }
                }
            }

            .action-link {
                span {
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    margin-right: 6px;
                }

                i {
                    font-size: 12px;
                }
            }
        }
    }

    .tabs-wrapper {
        .tabs-inner {
            .tabs {
                margin-bottom: 30px;
                z-index: 10;
                ul {
                    border: none;
                    li {
                        z-index: 10;
                        a {
                            border-bottom-width: 4px;
                        }
                    }
                }
            }
        }
    }

    .health-tile {
        font-family: var(--font);

        .tile-head {
            display: flex;
            align-items: center;
            margin-bottom: 10px;

            .v-icon {
                margin-right: 10px;
            }

            h4 {
                span {
                    &:first-child {
                        font-size: 1.2rem;
                        font-weight: 600;
                        color: var(--dark-text);
                        margin-right: 0.25rem;
                    }

                    &:nth-child(2) {
                        font-size: 0.95rem;
                        color: var(--light-text);
                    }
                }
            }
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.95rem;
        }
    }

    .dashboard-card {
        @include vuero-l-card;

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;

            h3 {
                color: var(--dark-text);
                font-size: 1.2rem;
                font-weight: 500;
            }
        }

        .chart-meta {
            p {
                font-size: 0.95rem;
                max-width: 80%;

                svg {
                    position: relative;
                    top: 2px;
                    height: 16px;
                    width: 16px;
                    color: var(--light-text);
                }

                a {
                    color: var(--primary);
                    font-weight: 500;
                }
            }
        }
    }

    .tile-grid-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;
        cursor: pointer;

        &:hover,
        &:focus {
        border-color: var(--primary);
        box-shadow: var(--light-box-shadow);
        }

        .tile-grid-item-inner {
        display: flex;
        align-items: center;

        > img {
            display: block;
            width: 50px;
            height: 50px;
            min-width: 50px;
        }

        .meta {
            margin-left: 10px;
            line-height: 1.4;

            span {
            display: block;
            font-family: var(--font);

            &:first-child {
                color: var(--dark-text);
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 1rem;
            }

            &:nth-child(2) {
                display: flex;
                align-items: center;

                span {
                display: inline-block;
                color: var(--light-text);
                font-size: 0.8rem;
                font-weight: 400;
                }

                .icon-separator {
                position: relative;
                font-size: 4px;
                color: var(--light-text);
                padding: 0 6px;
                }
            }
            }
        }

        .dropdown {
            margin-left: auto;
        }
        }
    }
}

.is-dark {
    .lifestyle-dashboard-v3 {
        .dashboard-card {
            @include vuero-card--dark;
        }
    }
}

@media only screen and (max-width: 767px) {
    .lifestyle-dashboard-v3 {
        .illustration-header {
            flex-direction: column;
            text-align: center;

            .header-image {
                height: auto;
                width: 100%;

                img {
                    position: relative;
                    width: 100%;
                    max-width: 260px;
                    margin: 0 auto;
                    top: 0;
                    margin-top: -75px;
                }
            }

            .header-meta {
                padding: 20px;

                >p {
                    max-width: 280px;
                    margin-left: auto;
                    margin-right: auto;
                }

                .summary-stats {
                    flex-wrap: wrap;

                    .summary-stat {
                        margin: 10px;
                        min-width: calc(33.3% - 20px);
                    }
                }
            }
        }

        .tabs-wrapper {
        .tabs-inner {
            .tabs {
            ul {
                text-align: center;
                justify-content: center;
            }
            }
        }
        }

        .health-tile {
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: 10px;
        }
    }

    .is-dark {
        .lifestyle-dashboard-v3 {
            .health-tile {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .lifestyle-dashboard-v3 {
        .illustration-header {
            .header-image {
                width: 250px;
            }
        }
    }
}
</style>
