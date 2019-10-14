<template>
    <view class="content">
        <image class="logo" src="/static/logo.png"></image>
        <view>
            <text class="title">{{title}}</text>
            <button open-type="getUserInfo" lang="zh_CN" @getuserinfo="login" withCredentials="true">登录</button>
        </view>
    </view>
</template>

<script>
    import request from "../../request/request";
    import { OK } from "../../config/constatns";

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

            var code = res.code;

            var [err, data] = await request.request('POST', '/login', {
                code: code
            })

            if (err == 0) {
                console.log(data)
            }
        },

        methods: {
            async login(res) {
                var encryptedData = res.detail.encryptedData
                var iv = res.detail.iv;

                var [, res] = await uni.login({
                    provider: 'weixin'
                });

                var code = res.code;

                var [err, data] = await request.request('POST', '/regist', {
                    code: code,
                    encrypted_data: encryptedData,
                    iv: iv
                })

                if (err == OK) {
                    console.log(data)
                }
            },
        }
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
