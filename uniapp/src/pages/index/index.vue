<template>
    <view class="content">
        <image class="logo" src="/static/logo.png"></image>
        <view>
            <text class="title">{{title}}</text>
        </view>
    </view>
</template>

<script>
    import request from "../../request/request";

    export default {
        data() {
            return {
                title: 'Hello'
            }
        },
        async onLoad() {
            var [, res] = await uni.login({
                provider: 'weixin'
            });

            var [code, data] = await request.request('POST', '/login', {
                code: res.code
            })

            if (code == 0) {
                console.log(data)
            }
        },
        methods: {}
    }
</script>

<style>
    .content {
        text-align: center;
        height: 400 upx;
    }

    .logo {
        height: 200 upx;
        width: 200 upx;
        margin-top: 200 upx;
    }

    .title {
        font-size: 36 upx;
        color: #8f8f94;
    }
</style>
