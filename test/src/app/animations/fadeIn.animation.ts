import { trigger, state, style, animate, transition } from '@angular/animations'

export function FadeIn(duration: any = '300ms') {	

	return trigger('fadeIn', [
        transition('void => *', [
          style({ opacity: 0}),
          animate(duration, style({opacity: 1}))
        ]),
        transition('* => void', [
          style({opacity: 1}),
          animate(duration, style({opacity: 0}))
        ])
	])
}