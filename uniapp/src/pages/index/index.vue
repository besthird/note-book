<template>
    <view class="content">
        <view v-for="item in items">
            <uni-card :title="item.user.nickname" :thumbnail="item.user.avatar" :extra="item.created_date" mode="basic" note="true">
                <rich-text :nodes="item.text"></rich-text>
                <template v-slot:footer>
                    <view class="footer-box">
                        <view @click="del" :id="item.id">删除</view>
                    </view>
                </template>
            </uni-card>
            <view style="height:10px"></view>
        </view>

        <uni-fab ref="fab"
                 :pattern="pattern"
                 :content="content"
                 :horizontal="horizontal"
                 :vertical="vertical"
                 :direction="direction"
                 :show="false"
                 @trigger="trigger"
        ></uni-fab>

        <uni-popup ref="popup" type="bottom">
            <button v-if="!isLogin" open-type="getUserInfo" @getuserinfo="regist"
                    withCredentials="true">login
            </button>
            <form v-else @submit="submit" report-submit="true">
                <view class="uni-title uni-common-pl">发布信息</view>
                <view class="uni-textarea">
                    <editor id="editor" name="text" class="ql-container" @input="input" @ready="onEditorReady"></editor>
                </view>
                <view class="uni-btn-v">
                    <button type="warn" @tap="undo" size="mini">Reset</button>
                    <button type="primary" form-type="submit" size="mini">Submit</button>
                </view>
            </form>
        </uni-popup>
    </view>


</template>

<script>
    import request from "../../core/request";
    import { OK, CANCEL } from "../../config/constatns";
    import uniPopup from "@dcloudio/uni-ui/lib/uni-popup/uni-popup.vue"
    import uniCard from "@dcloudio/uni-ui/lib/uni-card/uni-card"
    import uniFab from '@dcloudio/uni-ui/lib/uni-fab/uni-fab.vue'
    import edit from '../../components/wjx-edit/wjx-edit.vue';
    import uParse from '../../components/gaoyia-parse/parse.vue'
    import core from "../../core/core";
    import note from '../../core/note';
    import marked from 'marked'

    export default {
        components: { uniPopup, uniCard, uniFab, edit, uParse },
        data() {
            return {
                isLogin: true,
                limit: 10,
                offset: 0,
                items: [],
                end: false,
                text: "",
                article: "<div>我是HTML代码</div>",
                // 以下为 uniFab 配置
                horizontal: 'right',
                vertical: 'bottom',
                direction: 'horizontal',
                pattern: {
                    color: '#7A7E83',
                    backgroundColor: '#fff',
                    selectedColor: '#007AFF',
                    buttonColor: "#007AFF"
                },
                content: [
                    {
                        iconPath: '/static/edit_line.png',
                        selectedIconPath: '/static/edit_line.png',
                        text: '发布',
                        active: false
                    }
                ]
            }
        },

        async onLoad() {
            await request.login();
            this.isLogin = core.isLogin();
            await this.refresh();
        },

        async onReachBottom() {
            await this.search();
        },

        async onPullDownRefresh() {
            await this.refresh();
        },

        methods: {
            onEditorReady() {
                uni.createSelectorQuery().select('#editor').context((res) => {
                    this.editorCtx = res.context
                }).exec()
            },
            undo() {
                this.editorCtx.undo();
                this.text = "";
            },
            async del(e) {
                let id = e.target.id;

                var [err, res] = await uni.showModal({
                    title: "提示",
                    content: "确定删除么？"
                })

                if (res.confirm) {
                    let [err, data] = await note.del(id);
                    if (err === OK) {
                        uni.showToast({
                            title: "删除成功",
                            duration: 1000
                        })

                        await this.refresh();
                    }
                }

                console.log(res)
            },
            async trigger(e) {
                this.$refs.fab.close();

                switch (e.index) {
                    case 0:
                        this.$refs.popup.open();
                        break;
                }
            },
            async input(e) {
                this.text = e.detail.text;
            },
            async search() {
                if (this.end) {
                    return;
                }

                let [err, data] = await note.search(this.offset, this.limit);
                if (data.length == 0) {
                    this.end = true;
                } else {
                    data = data.map(function (item) {
                        item.text = marked(item.text);
                        return item;
                    });

                    this.offset += this.limit;
                    this.items = [...this.items, ...data];
                }
            },
            async refresh() {
                this.offset = 0;

                let [err, data] = await note.search(this.offset, this.limit);
                data = data.map(function (item) {
                    item.text = marked(item.text);
                    return item;
                });

                this.offset += this.limit;
                this.items = data;
                this.end = false;
            },
            async submit(e) {
                let formId = e.detail.formId;

                this.$refs.popup.close();

                await note.save(this.text, 0);
                await this.refresh();
                this.undo();
            },
            async regist(res) {
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
                    var app = getApp();
                    app.globalData.token = data.token;
                    app.globalData.user = data.user;
                    this.isLogin = core.isLogin();
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
