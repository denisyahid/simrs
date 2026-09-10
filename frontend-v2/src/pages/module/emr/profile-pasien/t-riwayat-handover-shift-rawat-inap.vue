<script setup lang="ts">

import { ref } from "vue";
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';


const globalFilter = ref("");
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });

const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void,
    (e: 'hasilItems', value: any): void,

}>()
const props = withDefaults(
    defineProps<{
        title?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
        isLoading: boolean
    }>(),
    {
        title: 'List Widget',
        items: () => [],
    }
)

const editRiwayat = (index) => {
    console.log(index);
    console.log(props.value);
    activeValue.value = 1;
}

const hapusRiwayat = (index) => {
console.log(index);
}

</script>

<template>
    <div class="list-widget is-straight is-12" v-if="props.items.length == 0 && props.isLoading">
        <div class="timeline-wrapper">
            <div class="timeline-container">
                <VPlaceloadWrap  v-for="key in 6" :key="key">
                    <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
                </VPlaceloadWrap>
            </div>
        </div>
    </div>

    <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="props.items.length == 0 && !props.isLoading">
        <div class="search-results-wrapper">
            <div class="search-results-body ">
                <!--Search Placeholder -->
                <div class="page-placeholder">
                    <div class="placeholder-content">
                        <img class="light-image" style=" max-width: 340px;"
                            src="/@src/assets/illustrations/placeholders/search-7.svg" alt="" />
                        <img class="dark-image" style=" max-width: 340px;"
                            src="/@src/assets/illustrations/placeholders/search-7-dark.svg" alt="" />
                        <h3>Data belum ada.</h3>
                        <p class="is-larger">
                            Sepertinya data ini belum di inputkan,
                            silahkan melakukan penginputan terlebih
                            dahulu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="list-widget" :class="[props.straight && 'is-straight']" v-if="props.items.length > 0">
        
        <div>
            <!-- Global Search Input -->
            <div class="p-inputgroup mb-3">
              <span class="p-inputgroup-addon">
                <i class="pi pi-search"></i>
              </span>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search" @keydown.enter.prevent />
            </div>
        
            <!-- PrimeVue DataTable -->
            <DataTable 
            v-model:filters="filtersTemplate"
              :value="props.items" 
              :paginator="true" 
              :rows="10" 
              :globalFilterFields="['petugasPemberi.label', 'petugasPenerima.label','tanggalPemberi','tanggalPenerima']"
              responsiveLayout="scroll" 
              class="p-datatable-striped p-datatable-gridlines"
            >
              <template #header>
                <div class="table-header">
                  <h5 class="m-0">Handover Data</h5>
                </div>
              </template>
        
              <Column field="index" header="No" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.index + 1 }}
                </template>
              </Column>
        
              <Column field="petugasPemberi.label" header="Pemberi Informasi" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.petugasPemberi?.label ?? '' }}
                </template>
              </Column>
        
              <Column field="petugasPenerima.label" header="Penerima Informasi" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.petugasPenerima?.label ?? '' }}
                </template>
              </Column>
        
              <Column field="tanggalPemberi" header="Tanggal Pemberi" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.tanggalPemberi ? H.formatDate(slotProps.data.tanggalPemberi, "YYYY-MM-DD HH:mm") : '' }}
                </template>
              </Column>
        
              <Column field="tanggalPenerima" header="Tanggal Penerima" :sortable="true">
                <template #body="slotProps">
                  {{ slotProps.data.tanggalPenerima ? H.formatDate(slotProps.data.tanggalPenerima, "YYYY-MM-DD HH:mm") : '' }}
                </template>
              </Column>
        
              <Column header="#">
                <template #body="slotProps">
                  <VIconButton 
                    class="mr-4" 
                    type="button" 
                    raised 
                    circle 
                    icon="fas fa-edit" 
                    @click="$emit('editItems', slotProps.data)"
                    color="warning" 
                    v-tooltip="'Edit'"
                  />
                  <VIconButton 
                    type="button" 
                    raised 
                    circle 
                    icon="fas fa-trash" 
                    @click="$emit('hapusItems', slotProps.data)"
                    color="danger" 
                    v-tooltip="'Hapus'"
                  />
                </template>
              </Column>
            </DataTable>
          </div>
    </div>
</template>

<style lang="scss">
@import '/@src/scss/abstracts/all';



.is-dark .td-label {

    color: var(--dark-dark-text);
}

.list-widget {
    @include vuero-l-card;

    padding: 30px;

    &:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    &.is-straight {
        @include vuero-s-card;
    }

    .widget-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 32px;
        margin-bottom: 10px;

        h3 {
            color: var(--dark-text);
            font-size: 1.1rem;
            font-weight: 500;
        }
    }

    .inner-list {
        padding: 10px 0;

        .inner-list-item {
            +.inner-list-item {
                margin-top: 24px;
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;
    }
}

.tb-order {
    color: var(--light-text-dark-10);
    font-family: var(--font);
    font-weight: 300;

    .text-value {
        font-family: var(--font-alt);
        color: var(--dark-text);
        font-weight: 600;
    }

    td {
        padding: 0 3px 0 0;
    }
}

.list-widget {
    .icon-timeline {
        .timeline-item {
            position: relative;
            display: flex;
            padding-bottom: 30px;

            &::after {
                content: '';
                position: absolute;
                top: 36px;
                left: 18px;
                width: 1px;
                height: calc(100% - 36px);
                border-left: 1px solid var(--fade-grey-dark-3);
            }

            .timeline-icon {
                position: relative;
                // height: 36px;
                height: auto;
                width: 36px;
                display: flex;
                justify-content: center;
                align-items: center;
                background: var(--white);
                border: 1px solid var(--fade-grey-dark-3);
                border-radius: var(--radius-rounded);
                color: var(--light-text);
                box-shadow: var(--light-box-shadow);

                &::after {
                    content: '';
                    position: absolute;
                    top: 17px;
                    left: 40px;
                    width: 20px;
                    height: 1px;
                    border-top: 1px solid var(--fade-grey-dark-3);
                }

                &.is-squared {
                    border-radius: 10px;

                    img {
                        border-radius: 10px;
                    }
                }

                &.is-primary {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-info {
                    background: var(--info);
                    border-color: var(--info);
                    box-shadow: var(--info-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-success {
                    background: var(--success);
                    border-color: var(--success);
                    box-shadow: var(--success-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-orange {
                    background: var(--orange);
                    border-color: var(--orange);
                    box-shadow: var(--orange-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-yellow {
                    background: var(--yellow);
                    border-color: var(--yellow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                img {
                    display: block;
                    height: 28px;
                    width: 28px;
                    border-radius: var(--radius-rounded);
                }

                svg {
                    height: 16px;
                    width: 16px;
                    stroke-width: 1.6px;
                }
            }

            .timeline-content {
                margin-left: 34px;
                line-height: 1.2;
                width: 100%;

                span {
                    font-size: 0.85rem;
                    color: var(--light-text);

                }

                .is-danger {
                    span.icon {
                        color: var(--danger--color-invert);
                    }

                }

                .is-warning {
                    span.icon {
                        color: var(--warning--color-invert);
                    }

                }

                span.td-label {
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                    font-size: 0.95rem;
                    font-weight: 500;
                    color: var(--dark-text);
                    width: 220px;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        .icon-timeline {
            .timeline-item {
                &::after {
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
                    background: var(--dark-sidebar-light-3) !important;
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon {
                    &::after {
                        border-color: var(--dark-sidebar-light-12) !important;
                    }

                    &.is-primary {
                        background: var(--primary);
                        border-color: var(--primary);
                        box-shadow: var(--primary-box-shadow);

                        svg {
                            color: var(--smoke-white);
                        }
                    }
                }

                .timeline-content {
                    p {
                        color: var(--dark-dark-text);
                    }
                }
            }
        }
    }
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
    display: none;
}
</style>
