import {Component} from '@angular/core'

import { fadeInAnimation } from './../../animations/routerFader.component';

@Component ({
	selector: 'my-contact',
	templateUrl: 'contact.component.html',
    animations: [fadeInAnimation],
    host: { '[@fadeInAnimation]': '' }
})

export class ContactComponent {
	lat: number = 43.697776;
  	lng: number = 2.1973743;
}