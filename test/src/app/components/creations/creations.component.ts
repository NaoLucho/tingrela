import { Component } from '@angular/core'

import { fadeInAnimation } from './../../animations/routerFader.component'
import { Fader } from './../../animations/fader.animation'

import { CreationService } from './../../services/creation.service'

import { Creation } from './../../objects/creation'
import { GlobalsService } from './../../services/globals.service'

@Component({
	selector:'my-creations',
	templateUrl: 'creations.component.html',
    animations: [
        fadeInAnimation,
        Fader()
    ],
    host: { '[@fadeInAnimation]': '' }
})

export class CreationsComponent {
    creations:any = []
    visibility: string = "false"
	loader: string = "true"
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
					this.loader = "false"
				}
			}
			),
			err => alert('Il y a eu une erreur');
	}


}