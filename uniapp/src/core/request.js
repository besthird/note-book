import { OK, FAILED, TOKEN_INVALID, USER_NOT_REGIST } from '../config/constatns'
import { BASE_URI } from '../config/env'

export default {
    async request(method, url, data) {
        let that = this;
        let baseUri = BASE_URI;
        let app = getApp();
        let token = '';
        if (app.globalData.token) {
            token = app.globalData.token;
        }

        return uni.request({
            url: baseUri + url,
            method: method,
            data: data,
            header: {
                'X-Token': token
            }
        }).then(res => {
            var [err, res] = res;
            if (err !== null) {
                return that.attempts({ title: '网络连接失败' }, method, url, data);
            }

            if (res.statusCode !== 200) {
                return that.attempts({ title: '网络连接失败' }, method, url, data);
            }

            if (res.data.code !== OK) {
                uni.showModal({
                    title: '提示',
                    content: res.data.message
                });

                if (res.data.code === TOKEN_INVALID || res.data.code === USER_NOT_REGIST) {
                    app.globalData.token = null;
                }

                return [res.data.code, res.data.message];
            }

            return [OK, res.data.data];
        });
    },

    async attempts(model, method, url, data) {
        let that = this;
        return uni.showModal(model).then(res => {
            var [, res] = res;
            if (res.confirm) {
                return that.request(method, url, data);
            }
            return [CANCEL];
        });
    },

    async login() {
        var [, res] = await uni.login({
            provider: 'weixin'
        });

        var [err, data] = await this.request('POST', '/login', {
            code: res.code
        })

        if (err == OK) {
            var app = getApp();
            app.globalData.token = data.token;
            app.globalData.user = data.user;
        }

        return data;
    },
}