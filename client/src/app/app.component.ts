import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { style, animate, trigger, transition, state, stagger, query } from '@angular/animations'

import { Fader } from './animations/fader.animation'

import { Type } from './objects/type'

import { DataService } from './services/data.service' 



// import { AuthenticationService } from './authentication/authentication.service';

@Component({
	selector: 'app-root',
	templateUrl: './app.component.html',
	animations: [
	]
})

export class AppComponent {
	title = 'app'

	data;
	error: string = ''

	constructor(
		private dataService: DataService
	) {
    		this.dataService.loadData()
			.subscribe(
			data => {
				this.data = data;
			}
			),
			err => alert('il y a eu une erreur');
	}

	ngOnInit(): void {

	}

	/* constructor(){
		   console.log("app.component.ts hastoken = " + (localStorage.getItem('token') !== null));
	   }*/
	// constructor(private authenticationService: AuthenticationService, private router: Router) {}

	//   hasAuthToken() {
	//       return localStorage.getItem('id_token') !== null;
	//   }

	//   logout() {
	//        this.authenticationService.logout();
	//            this.router.navigate(['home']);
	//   }
}
