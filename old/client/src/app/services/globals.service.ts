import { Injectable } from '@angular/core'

@Injectable()

export class GlobalsService {
    readonly serverUrl = 'http://localhost/gjchoc/server/web/api/'

    getUrl() {
        return this.serverUrl
    }
}