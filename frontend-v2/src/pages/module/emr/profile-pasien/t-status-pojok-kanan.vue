<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        title?: string
        user?: string
        picture?: string
        subtitle?: string
        straight?: boolean
        color?: string
    }>(),
    {
        title: 'Status',
        user: 'Alice C.',
        picture: '/demo/avatars/7.jpg',
        subtitle: '-',
        color: ''
    }
)
</script>

<template>
    <div class="widget-2" :class="[props.straight && 'is-straight'] && props.title == 'Alergi' ? 'pointer' : ''" >
        <div class="sender-block">
            <div class="exerpt">
                <h5>
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span>{{ props.title }}</span>
                </h5>
                <small  :class="props.color">{{ props.subtitle }}</small>
            </div>
        </div>

    </div>
</template>


<style lang="scss">
@import '/@src/scss/abstracts/all';

.pointer{
    cursor:pointer;
}

.widget-2 {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 0;
    background-color: var(--white);
    transition: all .3s;
    margin-bottom: 5px;
}

// Picker widget-2
// Picker widget-2
.widget-2-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: var(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: var(--radius-rounded);
                    background: var(--secondary);
                    border: 1.8px solid var(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: var(--font-alt);
        font-size: 0.9rem;
        color: var(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: var(--radius-rounded);
        color: var(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.picker-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .calendar {
        color: var(--dark-text);
        font-size: 12px;
        font-family: var(--font);
        width: 100%;
        margin-bottom: 5px;

        thead,
        tbody {
            width: 100%;
        }
    }

    .calendar thead,
    .calendar th {
        color: var(--primary);
        font-weight: 600;
        font-size: 10px;
        text-transform: uppercase;
        text-align: center;
    }

    .calendar td {
        padding: 0.5em 0;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
    }

    .calendar tbody td:hover {
        background: var(--fade-grey-light-3);
        color: var(--primary);
    }

    .current-day {
        color: var(--primary);
        font-weight: 600;
    }

    .prev-month,
    .next-month {
        color: #cacaca;
    }
}

// Search widget-2
.search-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .field {
        .control {
            position: relative;

            .input {
                height: 42px;
                border-radius: 8px;
                background: var(--widget-2-grey);
                border-color: var(--widget-2-grey);

                &:focus {
                    border-color: var(--fade-grey-dark-3);
                    background: var(--white);
                }
            }

            .searcv-button {
                position: absolute;
                top: 0;
                right: 0;
                height: 42px;
                width: 42px;
                border-radius: 8px;
                background: var(--primary);
                display: flex;
                justify-content: center;
                align-items: center;
                border: none;
                color: var(--white);
                cursor: pointer;

                svg {
                    height: 18px;
                    width: 18px;
                }
            }
        }

        .topics {
            display: flex;
            justify-content: space-between;
            text-align: center;
            padding-top: 12px;

            a {
                font-family: var(--font);
                color: var(--light-text);
                font-size: 0.95rem;

                &:hover,
                &:focus {
                    color: var(--primary);
                }
            }
        }
    }
}

// Gauge widget-2
.gauge-widget-2 {


    text-align: center;

    .gauge-wrap {
        padding: 30px 0;
    }
}

// Icon Toolbar widget-2
.icon-toolbar-widget-2 {


    padding: 16px;

    .icon-toolbar {
        display: flex;

        .toolbar-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1 1 0;

            .inner-icon {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 32px;
                width: 32px;
                min-width: 32px;
                border-radius: var(--radius-rounded);
                color: var(--light-text-light-12);
                transition: all 0.3s; // transition-all test

                &:hover:not(.is-active) {
                    background: var(--fade-grey-light-3);
                    color: var(--light-text);
                }

                &.is-active {
                    color: var(--primary);
                }

                svg {
                    height: 18px;
                    width: 18px;
                    transition: stroke 0.3s;
                }
            }
        }
    }
}

// Text widget-2
.text-widget-2 {


    .widget-2-content {
        p {
            font-size: 0.9rem;

            span {
                color: var(--dark-text);
                font-weight: 500;
            }
        }

        .hashtags {
            padding-top: 8px;
            font-family: var(--font);

            a {
                color: var(--primary);
                margin: 0 2px;
            }
        }
    }
}

// Tags widget-2
.tags-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }
}

// Calendar widget-2
.calendar-widget-2 {
    .calendar-widget-2-inner {
        display: flex;
        flex-wrap: wrap;
        margin-top: -6px;
        margin-left: -6px;
        margin-right: -6px;

        .calendar-square {
            @include vuero-l-card;

            flex: 1;
            margin: 6px;
            width: calc(50% - 6px);

            .date {
                text-align: center;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        font-weight: 700;
                        color: var(--dark-text);
                        font-size: 1.6rem;
                    }

                    &:nth-child(2) {
                        font-size: 0.75rem;
                        text-transform: uppercase;
                        color: var(--light-text);
                    }
                }
            }
        }
    }
}

// Calendar widget-2
.image-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    position: relative;
    padding: 10px;
    height: 310px;

    >img {
        display: block;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    .progress-wrap {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        padding: 0 30px;

        .progress {
            background-color: #000;
            background-image: linear-gradient(to right, var(--primary) 30%, #000 30%);
            height: 0.15rem !important;
        }
    }
}

// Stats widget-2
.stats-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    padding: 20px 0 0;

    .widget-2-toolbar {
        padding: 0 20px;
    }

    .stat-number {
        span {
            display: block;
            font-family: var(--font);

            &:first-child {
                font-size: 2rem;
                font-weight: 700;
                color: var(--dark-text);

                svg {
                    color: var(--success);
                }
            }

            &:nth-child(2) {
                font-size: 0.75rem;
                font-family: var(--font-alt);
                text-transform: uppercase;
                color: var(--light-text);
            }
        }
    }

    .stat-chart {
        position: relative;
        bottom: -30px;
    }
}

// Icon list widget-2
.icon-list-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .icon-list {
        .icon-list-item {
            display: flex;
            align-items: center;
            cursor: pointer;

            &:not(:last-child) {
                margin-bottom: 20px;
            }

            &:hover {
                .icon-wrap {
                    background: var(--primary);
                    color: var(--white);
                    box-shadow: var(--primary-box-shadow);
                }

                .item-meta {
                    span {
                        color: var(--dark-text);
                    }
                }
            }

            .icon-wrap {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 42px;
                width: 42px;
                border: none;
                border-radius: 10px;
                background: var(--widget-2-grey-dark-2);
                color: var(--light-text-dark-6);
                transition: all 0.3s; // transition-all test

                svg {
                    transition: stroke 0.3s;
                }

                img,
                svg {
                    height: 18px;
                    width: 18px;
                }

                &.is-placeholder {
                    background: var(--fade-grey-light-2);
                    border: 1px solid var(--fade-grey-dark-3);
                    color: var(--light-text);

                    i {
                        font-size: 1.4rem;
                    }
                }
            }

            .item-meta {
                margin-left: 12px;

                span {
                    font-family: var(--font);
                    color: var(--light-text);
                }
            }
        }
    }
}

// Video widget-2
.video-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    padding: 10px;

    .widget-2-content {
        display: flex;

        >img {
            height: 100px;
            width: 100px;
            border-radius: 10px;
            object-fit: cover;
        }

        .widget-2-meta {
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            font-family: var(--font);

            >svg {
                height: 20px;
                width: 20px;
                stroke-width: 1.6px;
                color: var(--primary);
                margin-bottom: 6px;
            }

            h4 {
                font-weight: 600;
                font-family: var(--font-alt);
                font-size: 0.9rem;
                line-height: 1.3;
                color: var(--dark-text);
            }

            .views {
                display: flex;
                align-items: center;
                margin-top: auto;
                color: var(--light-text);

                svg {
                    height: 14px;
                    width: 14px;
                    stroke-width: 1.4px;
                    margin-right: 6px;
                }

                span {
                    font-size: 0.85rem;
                }
            }
        }
    }
}

// Inbox widget-2
.inbox-widget-2 {



    .sender-block {
        h4 {
            font-family: var(--font);
            font-weight: 500;
            color-interpolation-filters: var(--dark-text);
            font-size: 0.95rem;
        }

        .sender-block-inner {
            display: flex;
            align-items: center;
            padding: 16px 0;

            .meta {
                margin-left: 10px;
                line-height: 1.2;

                span {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    font-size: 0.8rem;
                }
            }
        }

        .exerpt {
            padding: 5px 10px 5px 10px;
            border-radius: 10px;
            border: 1px solid var(--fade-grey-dark-3);
            font-family: var(--font);

            h5 {
                display: flex;
                align-items: center;
                font-size: 0.8rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--primary);
                margin-bottom: 0px;

                i {
                    font-size: 6px;
                    margin-right: 6px;
                }
            }

            p {
                font-size: 0.9rem;
                color: var(--dark-text);
                margin-bottom: 6px;
            }

            small {
                color: var(--light-text);
            }
        }
    }
}

// Illustration widget-2s
.illustration-widget-2 {
    &.illustration-widget-2-v1 {
        @include vuero-l-card;

        &.is-straight {
            @include vuero-s-card;
        }

        padding: 40px 30px;
        text-align: center;
        font-family: var(--font);

        .img-container {
            position: relative;
            margin: 0 auto 12px;
            max-width: 180px;

            .main {
                display: block;
            }

            .badge {
                position: absolute;

                &.badge-1 {
                    height: 70px;
                    width: 70px;
                    top: 0;
                    right: 0;
                }

                &.badge-2 {
                    height: 50px;
                    width: 50px;
                    top: 0;
                    left: 0;
                }
            }
        }

        h3 {
            font-weight: 600;
            font-size: 0.95rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            margin-bottom: 4px;
        }

        p {
            font-size: 0.9rem;
        }
    }

    &.illustration-widget-2-v2 {
        @include vuero-l-card;

        &.is-straight {
            @include vuero-s-card;
        }

        padding: 16px;

        img {
            border-radius: 14px;
            margin-bottom: 4px;
        }

        h3 {
            font-weight: 600;
            font-size: 0.95rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
        }

        p {
            font-size: 0.9rem;
        }
    }

    &.illustration-widget-2-v3 {
        @include vuero-l-card;

        &.is-straight {
            @include vuero-s-card;
        }

        padding: 24px;

        img {
            border-radius: 18px;
            margin-bottom: 4px;
        }

        h3 {
            font-weight: 600;
            font-size: 0.95rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
        }

        p {
            font-size: 0.9rem;
        }
    }
}

// Creative list widget-2
.creative-list-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .creative-list {
        .creative-list-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            background: var(--fade-grey-light-3);
            margin-bottom: 8px;

            &.is-orange {
                background: var(--orange-light-20);
            }

            &.is-purple {
                background: var(--purple-light-38);
            }

            &.is-green {
                background: var(--success-light-48);
            }

            .meta {
                font-family: var(--font);
                font-size: 0.95rem;
                color: var(--dark-text);
                font-weight: 500;
                margin-left: 10px;
            }
        }
    }
}

// Social buttons widget-2
.social-buttons-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    padding: 16px;

    .social-buttons {
        display: flex;

        .social-button {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1 1 0;

            .inner-button {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 32px;
                width: 32px;
                min-width: 32px;
                border-radius: 8px;
                color: var(--light-text-light-12);
                transition: all 0.3s; // transition-all test

                &:hover:not(.is-active) {
                    background: var(--fade-grey-light-3);
                    color: var(--light-text);
                }

                &.is-rounded {
                    border-radius: var(--radius-rounded);
                }

                &.is-active {
                    &.is-facebook {
                        background: var(--facebook);
                        color: var(--smoke-white);
                        box-shadow: var(--facebook-box-shadow);
                    }

                    &.is-linkedin {
                        background: var(--linkedin);
                        color: var(--smoke-white);
                        box-shadow: var(--facebook-box-shadow);
                    }

                    &.is-twitter {
                        background: var(--twitter);
                        color: var(--smoke-white);
                        box-shadow: var(--twitter-box-shadow);
                    }

                    &.is-instagram {
                        background: var(--instagram);
                        color: var(--smoke-white);
                        box-shadow: var(--angular-box-shadow);
                    }

                    &.is-github {
                        background: var(--github);
                        color: var(--smoke-white);
                        box-shadow: var(--github-box-shadow);
                    }

                    &.is-invision {
                        background: var(--invision);
                        color: var(--smoke-white);
                        box-shadow: var(--invision-box-shadow);
                    }

                    &.is-atlassian {
                        background: var(--linkedin);
                        color: var(--smoke-white);
                        box-shadow: var(--linkedin-box-shadow);
                    }

                    &.is-bootstrap {
                        background: var(--purple);
                        color: var(--smoke-white);
                        box-shadow: var(--purple-box-shadow);
                    }
                }

                svg {
                    height: 18px;
                    width: 18px;
                    transition: stroke 0.3s;
                }
            }
        }
    }
}

// Call widget-2
.call-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    padding: 40px 30px;
    text-align: center;
    font-family: var(--font);

    .img-container {
        position: relative;
        margin: 0 auto 12px;
        max-width: 180px;
        max-height: 180px;

        .main {
            display: block;
        }

        .badge {
            position: absolute;
            height: 60px;
            width: 60px;
            bottom: 0;
            right: 2px;
            border-radius: var(--radius-rounded);
            border: 6px solid var(--white);
        }
    }

    h3 {
        font-weight: 600;
        font-family: var(--font-alt);
        font-size: 1rem;
        color: var(--dark-text);
        margin-bottom: 4px;
    }

    p {
        font-size: 0.9rem;
    }

    .actions {
        display: flex;
        justify-content: center;
        padding-top: 12px;

        .action {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 36px;
            width: 36px;
            min-width: 36px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            margin: 0 6px;
            border: 1px solid var(--fade-grey-dark-3);
            box-shadow: var(--light-box-shadow);
            transition: all 0.3s; // transition-all test

            &:hover {
                background: var(--primary);
                border-color: var(--primary);
                color: var(--smoke-white);
                box-shadow: var(--primary-box-shadow);
            }

            svg {
                height: 16px;
                width: 16px;
                transition: stroke 0.3s;
            }
        }
    }
}

// Avatar list widget-2
.avatar-list-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    padding: 14px;

    .field {
        .control {
            position: relative;

            .input {
                height: 42px;
                border-radius: 8px;
                background: var(--widget-2-grey);
                border-color: var(--widget-2-grey);

                &:focus {
                    border-color: var(--fade-grey-dark-3);
                    background: var(--white);
                }
            }

            .searcv-button {
                position: absolute;
                top: 0;
                right: 0;
                height: 42px;
                width: 42px;
                border-radius: 8px;
                background: var(--primary);
                display: flex;
                justify-content: center;
                align-items: center;
                border: none;
                color: var(--white);
                cursor: pointer;

                svg {
                    height: 18px;
                    width: 18px;
                }
            }
        }
    }

    .avatar-list {
        .avatar-list-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s; // transition-all test

            &:hover {
                background: var(--fade-grey-light-3);
            }

            .meta {
                font-family: var(--font-alt);
                font-size: 0.95rem;
                color: var(--dark-text);
                font-weight: 600;
                margin-left: 10px;
            }
        }
    }
}

// Followers widget-2
.followers-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .channels {
        display: flex;
        margin-left: -8px;
        margin-right: -8px;
        width: 100%;

        .channel {
            width: calc(33.3% - 16px);
            text-align: center;
            margin: 0 8px;

            .channel-icon {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 36px;
                width: 36px;
                border-radius: 10px;
                background: var(--widget-2-grey);
                margin: 0 auto 4px;

                i {
                    font-size: 14px;
                    color: var(--light-text);
                }
            }

            .channel-stats {
                span {
                    font-family: var(--font);
                    display: block;

                    &:first-child {
                        font-weight: 500;
                        color: var(--light-text);
                    }
                }
            }
        }
    }
}

// Cart widget-2

.cart-widget-2 {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    .cart-items {
        .cart-item {
            display: flex;
            margin: 8px 0;

            .meta {
                margin-left: 12px;
                display: flex;
                flex-direction: column;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        font-size: 0.9rem;
                        color: var(--light-text);
                    }

                    &:nth-child(2) {
                        color: var(--dark-text);
                        margin-top: auto;
                        font-weight: 600;
                        font-size: 1.2rem;
                    }
                }
            }
        }
    }

    .cart-button {
        padding-top: 16px;

        .button {
            min-height: 50px;
            border-radius: 10px;
        }
    }
}

.is-dark {
    .widget-2 {
        @include vuero-card--dark;
    }

    .illustration-widget-2 {

        &.illustration-widget-2-v1,
        &.illustration-widget-2-v2,
        &.illustration-widget-2-v3 {
            h3 {
                color: var(--dark-dark-text);
            }
        }
    }

    .creative-list-widget-2 {
        .creative-list {
            .creative-list-item {
                background: var(--dark-sidebar-light-2);
                border: 1px solid var(--dark-sidebar-light-12);

                .meta {
                    color: var(--smoke-white);
                }
            }
        }
    }

    .avatar-list-widget-2 {
        .field {
            .control {
                .input {
                    background: var(--dark-sidebar-light-3);
                    border-color: var(--dark-sidebar-light-12);
                }

                .searcv-button {
                    background: var(--primary);
                }
            }

            .topics {
                a:hover {
                    color: var(--primary);
                }
            }
        }

        .avatar-list {
            .avatar-list-item {
                &:hover {
                    background: var(--dark-sidebar-light-8);
                }

                .meta {
                    color: var(--dark-dark-text);
                }
            }
        }
    }

    .video-widget-2 {
        .widget-2-meta {
            >svg {
                color: var(--primary);
            }

            h4 {
                color: var(--dark-dark-text);
            }
        }
    }

    .inbox-widget-2 {
        .sender-block {
            .sender-block-inner {
                .meta {
                    span {
                        color: var(--dark-dark-text);
                    }
                }
            }
        }

        .exerpt {
            background: var(--dark-sidebar-light-4);
            border-color: var(--dark-sidebar-light-12);

            h5 {
                color: var(--primary);
            }

            p {
                color: var(--dark-dark-text);
            }
        }
    }

    .icon-list-widget-2 {
        .icon-list {
            .icon-list-item {
                &:hover {
                    .icon-wrap {
                        background: var(--primary) !important;
                        box-shadow: var(--primary-box-shadow);
                        color: var(--smoke-white);
                    }

                    .item-meta {
                        span {
                            color: var(--primary) !important;
                        }
                    }
                }

                .icon-wrap {
                    background: var(--dark-sidebar-light-3);
                    color: var(--light-text-light-2);
                }
            }
        }
    }

    .stats-widget-2 {
        .stat-number {
            span {
                &:first-child {
                    color: var(--dark-dark-text);
                }
            }
        }
    }

    .icon-toolbar-widget-2 {
        .icon-toolbar {
            .toolbar-icon {
                .inner-icon {
                    color: var(--light-text-dark-12);

                    &:hover,
                    &:focus:not(.is-active) {
                        background: var(--dark-sidebar-light-3);
                    }

                    &.is-active {
                        color: var(--primary);
                    }
                }
            }
        }
    }

    .image-widget-2 {
        .progress-wrap {
            .progress {
                background-image: linear-gradient(to right,
                        var(--primary) 30%,
                        #000 30%) !important;
            }
        }
    }

    .search-widget-2 {
        .field {
            .control {
                .input {
                    background: var(--dark-sidebar-light-3);
                    border-color: var(--dark-sidebar-light-12);
                }

                .searcv-button {
                    background: var(--primary);
                }
            }

            .topics {
                a:hover {
                    color: var(--primary);
                }
            }
        }
    }

    .widget-2-toolbar {
        h3 {
            color: var(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: var(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }

    .call-widget-2 {
        h3 {
            color: var(--dark-dark-text);
        }

        .img-container {
            .badge {
                border-color: var(--dark-sidebar-light-6);
            }
        }

        .actions {
            .action {
                background: var(--dark-sidebar-light-2);
                border: 1px solid var(--dark-sidebar-light-12);
                color: var(--dark-dark-text);

                &:hover,
                &:focus {
                    border-color: var(--primary);
                    color: var(--primary);
                }
            }
        }
    }

    .social-buttons-widget-2 {
        .social-buttons {
            .social-button {
                .inner-button {
                    &:hover:not(.is-active) {
                        background: var(--dark-sidebar-light-2);
                        color: var(--light-text);
                    }
                }
            }
        }
    }

    .picker-widget-2 {
        .calendar {
            color: var(--dark-dark-text);

            thead,
            th {
                color: var(--primary);
            }

            td:hover {
                background: var(--dark-sidebar-light-3);
                color: var(--primary);
            }
        }

        .current-day {
            color: var(--primary);
        }
    }

    .followers-widget-2 {
        .channels {
            .channel {
                .channel-icon {
                    background: var(--dark-sidebar-light-2);
                    border: 1px solid var(--dark-sidebar-light-12);
                }
            }
        }
    }

    .cart-widget-2 {
        .cart-items {
            .cart-item {
                .meta {
                    span {
                        &:nth-child(2) {
                            color: var(--primary);
                        }
                    }
                }
            }
        }
    }

    .text-widget-2 {
        .widget-2-content {
            p {
                span {
                    color: var(--dark-dark-text);
                }
            }
        }

        .hashtags {
            a {
                color: var(--primary);
            }
        }
    }

    .calendar-widget-2 {
        .calendar-widget-2-inner {
            .calendar-square {
                @include vuero-card--dark;

                .date {
                    span {
                        &:first-child {
                            color: var(--dark-dark-text);
                        }
                    }
                }
            }
        }
    }
}
small.is-dangerr{
   color:var(--danger)  !important;
}
</style>
