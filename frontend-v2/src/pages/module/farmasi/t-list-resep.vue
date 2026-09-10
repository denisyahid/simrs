<script setup lang="ts">
import Toolbar from 'primevue/toolbar';
import { onceImageErrored } from '/@src/utils/via-placeholder'
// export default {
//     emits: ['editItems', 'hapusItems'],

// }
import * as H from '/@src/utils/appHelper'
const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void,
    (e: 'addItems', value: any): void,
    (e: 'splitObat', value: any): void,
}>()
const props = withDefaults(
    defineProps<{
        title?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
        iskronis?: boolean
        isSave ?: boolean
        disabled ?: Boolean
    }>(),
    {
        title: 'List Widget',
        items: () => [],
        isSave: false
    }
)
</script>

<template>
    <div>
        <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="props.items.length == 0">
            <div class="search-results-wrapper">

                <div class="search-results-body ">
                    <!--Search Placeholder -->
                    <div class="page-placeholder">
                        <div class="placeholder-content">
                            <img class="light-image" style=" max-width: 340px;"
                                src="/images/simrs/not-found-emr.png" alt="" />
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
            <!-- <div class="widget-head">
                <h3 class="dark-inverted">{{ props.title }}</h3>
                <VButton icon="feather:plus" color="info" raised @click="$emit('addItems')">
                    Tambah
                </VButton>
            </div> -->

            <div class="inner-list">
                <div class="icon-timeline">
                    <div v-for="item in props.items" :key="item.id" class="timeline-item">
                        <div class="timeline-icon"
                            :class="[props.squared && 'is-squared']">
                            <!-- , props.colored && 'is-' + item.color]"> -->
                            <i :class="item.icon" aria-hidden="true"></i>
                            <!-- <i aria-hidden="true" class="iconify" :data-icon="item.icon"></i> -->
                        </div>
                        <div class="timeline-content">
                            <table class="is-fullwidth">
                                <tr>
                                    <td style="width:18%; word-break: break-word;" rowspan="2">
                                        <p style="margin: 0; white-space: normal; word-break: break-word;">{{ item.kdproduk }}</p>
                                        <p style="margin: 0; white-space: normal; word-break: break-word;">{{ item.namaproduk }}</p>
                                        <span>{{ 'R/ke : ' + item.rke }}</span>
                                        <p class="p-bawah">{{ item.jeniskemasan }}</p>
                                        <VTag :color="'warning'" :label="'Kronis'" v-if="item.iskronis && !iskronis"/>
                                        <small style="color:red" v-if="item.lastorder != ''&& item.lastorder!=null ">diresepkan terakhir : {{ item.lastorder? H.formatDateNoTime(item.lastorder) :''}}</small>
                                    </td>
                                    <td style="width:15%; text-align: center;">
                                        <span class="td-label">Aturan
                                        <br> Pakai </span>
                                    </td>

                                    <td style="width:6%; text-align: center">
                                        <span class="td-label" style="color:red">History <br> Pemakaian
                                        </span>
                                    </td>

                                    <td style="width:7%; text-align: center;">
                                        <span class="td-label">Qty</span>
                                    </td>

                                    <td style="width:10%; text-align: center;">
                                        <span class="td-label">Jumlah Racikan</span>
                                    </td>

                                    <td style="width:7%; text-align: center;">
                                        <span class="td-label">Dosis</span>
                                    </td>

                                    <td style="width:17%; text-align: center;">
                                        <span class="td-label">Keterangan</span>
                                    </td>

                                    <td style="width:10%; text-align: center;">
                                        <span class="td-label">Satuan</span>
                                    </td>
                                    <td style="width:12%; text-align: center;">
                                        <span class="td-label">Harga</span>
                                    </td>
                                   
                                    <td style="width:12%; text-align: center;">
                                        <span class="td-label">Total</span>
                                    </td>
                                    <!-- <td style="width:8%; text-align: center;">
                                        <span class="td-label">Dosis</span>
                                    </td> -->

                                        
                                    <td style="width:10%" rowspan="2" v-if="!iskronis">
                                        <!-- <td style="width:10%" rowspan="2"> -->
                                        <VIconButton icon="feather:edit" @click="$emit('editItems', item)" v-tooltip.prime="'Edit Obat'"
                                            color="warning" raised circle class="mr-2" :loading="item.btnLoading" :disabled="disabled">
                                        </VIconButton>
                                        
                                        <VIconButton v-if="item.iskronis && isSave" icon="feather:aperture" @click="$emit('splitObat', item)" v-tooltip.prime="'Split Stok Kronis'"
                                            color="info" raised circle class="mr-2" :loading="item.btnLoading">
                                        </VIconButton>
                                        
                                        <VIconButton icon="feather:trash" @click="$emit('hapusItems', item)" v-tooltip.prime="'Hapus Obat'" :disabled="disabled"
                                            color="danger" raised circle :loading="$emit('btnLoading',item)">
                                        </VIconButton>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <VTag :color="'info'" :label="item.aturanpakai" />

                                        <!-- <span>{{ item.aturanpakai }}</span> -->
                                    </td>

                                    <td style="text-align: center">
                                        <span style="color:red">{{ item.jumlahlast }}</span>
                                    </td>
                                    
                                    <td style="text-align: center">
                                        <span>{{ item.jumlah }}</span>
                                    </td>

                                    <td style="text-align: center">
                                        <span>
                                            {{ item.jeniskemasan === 'Non Racikan' ? '-' : item.racikan }}
                                        </span>
                                    </td>

                                    <td style="text-align: center">
                                        <span>
                                            {{ item.jeniskemasan === 'Non Racikan' ? '-' : item.dosis }}
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        <span>{{ item.keterangan }}</span>
                                    </td>
                                    <td style="text-align: center">
                                        <span>{{ item.satuanstandar }}</span>
                                    </td>
                                    <td style="text-align: center">
                                        <span>{{ H.formatRp(item.hargasatuan, 'Rp.') }}</span>
                                    </td>
                                    
                                    <td style="text-align: center">
                                        <span>{{ H.formatRp(item.total, 'Rp.') }}</span>
                                    </td>
                                    <!-- <td style="text-align: center">
                                        <span>{{ item.jmldosis }}</span>
                                    </td> -->



                                </tr>

                            </table>
                            <!-- <table>
                                <tr>
                                    <td > 
                                        Alergi Obat 
                                    </td>
                                    <td width="10%">:</td>
                                    <td style="color: red;">{{ item.alergiobat }}</td>
                                </tr>
                            </table> -->
                        </div>

                    </div>
                </div>
            </div>
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

                p.p-bawah {
                    font-size: 0.85rem;
                    font-style: inherit;
                    font-weight: inherit;
                    color: var(--light-text);
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
</style>
