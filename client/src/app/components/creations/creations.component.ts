import { Component, OnInit } from '@angular/core'

import { fadeInAnimation } from './../../animations/routerFader.component'
import { Fader } from './../../animations/fader.animation'

import { CreationService } from './../../services/creation.service'

import { Creation } from './../../objects/creation'
import { GlobalsService } from './../../services/globals.service'

@Component({
    selector: 'app-creations',
    templateUrl: 'creations.component.html',
    animations: [
        fadeInAnimation,
        Fader()
    ],
    host: { '[@fadeInAnimation]': '' }
})

export class CreationsComponent implements OnInit {
    creations: any = []
    visibility = 'false'
    loader = 'true'
    private serverUrl: string;

    constructor(
        private creationService: CreationService,
        private globalsService: GlobalsService
    ) {
        this.serverUrl = globalsService.getAssets();
    }

    ngOnInit(): void {
        this.creationService.loadCreations()
            .subscribe(
            data => {
                this.creations = data;
                if(this.creations) {
                    this.visibility = 'true'
                    this.loader = 'false'
                }
            }
            ),
            () => alert('Il y a eu une erreur');
    }


}