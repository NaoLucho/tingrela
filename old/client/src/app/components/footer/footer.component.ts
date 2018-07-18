import { Component } from '@angular/core'

import { DataService } from './../../services/data.service'

import { Fader } from './../../animations/fader.animation'

@Component ({
	selector: 'my-footer',
	templateUrl: 'footer.component.html',
	styleUrls: ['footer.style.scss'],
	animations: [
		Fader()
	]
})

export class FooterComponent {
	private visibility: string = "false"


	constructor(
		private dataService: DataService
	) {
		this.dataService.getDataSubscribed().subscribe(data => {
        if (data) {
          this.visibility = "true";
        }
		}, err => /* console.log(err)); */ alert('Il y a eu une erreur. Réferrez vous à l\'administrateur'))
	}


}
