import { Injectable } from '@angular/core'

@Injectable()

export class GlobalsService {
    readonly serverUrl = 'http://localhost:8000/'
    readonly api = this.serverUrl + 'api/';
    readonly assets = this.serverUrl + 'uploads/'

    getUrl() {
        return this.api
    }

    getAssets() {
        return this.assets
    }
}
