import { Component, OnInit } from '@angular/core'

import { fadeInAnimation } from './../../animations/routerFader.component'

import { DataService } from './../../services/data.service'

import { Type } from './../../objects/type'

import { Fader } from './../../animations/fader.animation'

@Component ({
	selector: 'my-shop',
	templateUrl: 'shop.component.html',
    animations: [fadeInAnimation, Fader()],
    host: { '[@fadeInAnimation]': '' }
})

export class ShopComponent {
	data: Type[];
	error: string = '';
	type: Type;
	public categories: string[]

	constructor(
		private dataService: DataService
	) {

	}

	ngOnInit() {
		this.dataService.getDataSubscribed().subscribe(data => {
        if (data) {
          this.data = data;
        }
      }, err => console.log(err));

		this.dataService.initData()
	}
}
