import { OK, FAILED, TOKEN_INVALID, USER_NOT_REGIST } from '../config/constatns'
import request from "./request"

export default {
    async save(text, id = 0) {
        let [err, data] = await request.request('POST', '/note/' + id, {
            text: text
        });

        if (err === OK) {
            uni.showModal({
                title: "发布成功"
            });
        }

        return [err, data];
    },

    async search(offset, limit) {
        let [err, data] = await request.request('GET', '/note', {
            offset: offset,
            limit: limit
        });

        return [err, data];
    }
}