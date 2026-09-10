<script setup lang="ts">

const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void,
    (e: 'hasilItems', value: any): void,
    (e: 'expertiseItems', value: any): void,
    (e: 'cetakItems', value: any): void,
    (e: 'showResultRadiologiManual', value: any): void,
}>()
const props = withDefaults(
    defineProps<{
        title?: string
        class?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
        isHideEdit: boolean,
    }>(),
    {
        title: 'List Widget',
        items: () => [],
        isHideEdit: false,
    }
)
</script>

<template>
    <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="props.items.length == 0">
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

        <div class="timeline-wrapper" v-if="props.items.length > 0">
            <div class="timeline-header"></div>
            <div class="timeline-wrapper-inner pt-0">
                <div class="timeline-container">
                    <div class="timeline-item is-unread " v-for="(item, index)  in props.items" :key="item.norec">
                        <div class="date">
                            <span>{{ item.tglorder }}</span>
                        </div>
                        <div :class="'dot is-' + item.color"></div>
                        <div class="content-wrap is-grey">
                            <div class="content-box ">
                                <div class="status"></div>
                                <VIconBox size="small" :color="'facebook'" rounded>
                                    <i :class="item.icon" aria-hidden="true"></i>
                                </VIconBox>
                                <div class="box-text" style="width:70%">
                                    <div class="meta-text">
                                        <table class="tb-order">
                                            <tr>
                                                <td>No Registrasi</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.noregistrasi }} </td>
                                            </tr>
                                            <tr>
                                                <td>No Order</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.noorder }} </td>
                                            </tr>
                                            <tr>
                                                <td>Ruangan Asal </td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.ruanganasal }} </td>
                                            </tr>
                                            <tr>
                                                <td>Dokter</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.dokter }} </td>
                                            </tr>
                                            <tr>
                                                <td>Dokter Baca</td>
                                                <td>:</td>
                                                <td class="text-value">{{ item.dokterbaca }} </td>
                                            </tr>
                                        </table>
                                        <div class="is-divider p-0 m-2"></div>
                                        <div v-for="(detail, index2) in  item.details">
                                            <p  class=" is-clickable" style="  display: flex;  justify-content: space-between; width: 500px;"> 
                                            <!-- <p  class=" is-clickable" style="  display: flex;  justify-content: space-between; width: 500px;"     v-tooltip-prime.bottom="'Lihat Expertise'"
                                                 @click="$emit('expertiseItems', detail)">  -->
                                                <span class="status mt-1" style="position: absolute;"></span> <span
                                                    class="ml-3"> {{ detail.namaproduk }}</span>
                                                <i class="fas fa-notes-medical is-success" v-if="detail.norec_pp"
                                            ></i>

                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <div class="box-end" style="width:30%">
                                    <div class="columns is-multiline">
                                        <div class="column is-12 mt-3">
                                            <div class="status is-pulled-right mt-2 ml-2"></div>
                                            <VTag :label="item.status" :color="item.color_status" rounded
                                                class="is-pulled-right" />
                                        </div>
                                        <div class="column is-12 ">
                                            <VIconButton v-if="item.objectruangantujuanfk == 331" icon="feather:file-text" @click="$emit('showResultRadiologiManual', item)" raised
                                                circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Hasil Manual'">
                                            </VIconButton>
                                            <VIconButton v-else icon="feather:file-text" @click="$emit('hasilItems', item)" raised
                                                circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Hasil'">
                                            </VIconButton>
                                            <!-- <VIconButton icon="feather:trash" @click="$emit('hapusItems', item)"
                                                color="danger" raised circle class="mr-2 is-pulled-right"
                                                v-tooltip.bubble="'Hapus'">
                                            </VIconButton>
                                            <VIconButton icon="feather:edit" @click="$emit('editItems', item)" color="info"
                                                raised circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Edit'"  v-if="!isHideEdit"> 
                                            </VIconButton> -->
                                            <VIconButton icon="feather:edit" @click="$emit('cetakItems', item)" color="warning"
                                                raised circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Expertise'"> 
                                            </VIconButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
