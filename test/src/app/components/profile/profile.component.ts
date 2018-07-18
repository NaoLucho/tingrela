import {Component} from '@angular/core'

import { fadeInAnimation } from './../../animations/routerFader.component';

@Component ({
	selector: 'my-profile',
	templateUrl: 'profile.component.html',
    animations: [fadeInAnimation],
    host: { '[@fadeInAnimation]': '' }
})

export class ProfileComponent {

}