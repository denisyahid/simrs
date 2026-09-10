<script setup lang="ts">
import { onceImageErrored } from '/@src/utils/via-placeholder'
// export default {
//     emits: ['editItems', 'hapusItems'],

// }
const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'hapusItems', value: any): void
}>()
const props = withDefaults(
    defineProps<{
        title?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
    }>(),
    {
        title: 'List Widget',
        items: () => [],
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
        <div class="widget-head">
            <h3 class="dark-inverted">{{ props.title }}</h3>
            <VDropdown icon="feather:more-vertical" dots right spaced>
                <template #content>
                    <a href="#" class="dropdown-item is-media">
                        <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-reload"></i>
                        </div>
                        <div class="meta">
                            <span>Reload</span>
                            <span>Reload Data</span>
                        </div>
                    </a>

                    <hr class="dropdown-divider" />
                    <a href="#" class="dropdown-item is-media">
                        <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                        </div>
                        <div class="meta">
                            <span>Remove</span>
                            <span>Remove from view</span>
                        </div>
                    </a>
                </template>
            </VDropdown>
        </div>

        <div class="inner-list">
            <div class="icon-timeline">
                <div v-for="item in props.items" :key="item.id" class="timeline-item">
                    <div class="timeline-icon"
                        :class="[props.squared && 'is-squared', props.colored && 'is-' + item.color]">
                        <img v-if="item.picture" class="avatar" :src="item.picture" alt=""
                            @error.once="(event) => onceImageErrored(event, '150x150')" />
                        <i v-else aria-hidden="true" class="iconify" :data-icon="item.icon"></i>
                    </div>
                    <div class="timeline-content">
                        <table class="is-fullwidth">
                            <tr>
                                <td style="width:25%" rowspan="2">
                                    <p>{{ item.namaproduk }}</p>
                                    <span>{{ item.tglorder }}</span>
                                </td>
                                <td style="width:10%">
                                    <span class="td-label">No Order</span>
                                </td>
                                <td style="width:10%" v-if="item.tgloperasi">
                                    <span class="td-label">Rencana Operasi</span>
                                </td>
                                <td style="width:15%">
                                    <span class="td-label">Ruangan Asal</span>
                                </td>
                                <td style="width:15%">
                                    <span class="td-label">Dokter </span>
                                </td>
                                <td style="width:10%">
                                    <span class="td-label">Status </span>
                                </td>
                                <td style="width:10%" rowspan="2">
                                    <VIconButton icon="feather:edit" @click="$emit('editItems',item)" color="warning"
                                        raised circle class="mr-2">
                                    </VIconButton>
                                    <VIconButton icon="feather:trash" @click="$emit('hapusItems',item)" color="danger"
                                        raised circle>
                                    </VIconButton>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>{{ item.noorder }}</span>
                                </td>
                                <td v-if="item.tgloperasi">
                                    <span>{{ item.tgloperasi }}</span>
                                </td>
                                <td>
                                    <span>{{ item.ruanganasal }}</span>
                                </td>
                                <td>
                                    <span>{{ item.dokter }}</span>
                                </td>
                                <td>
                                    <VTag :color="item.color_status" :label="item.status" />

                                    <!-- <span>{{ item.status }}</span> -->
                                </td>

                            </tr>

                        </table>
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
    