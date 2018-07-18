import { trigger, state, style, animate, transition } from '@angular/animations'

export function Fader(duration: any = '300ms', delay: any = '0ms' ) {

	return trigger('visibilityChanged', [
		state('true' , style({ opacity: 1 })),
		state('false', style({ opacity: 0 })),
		transition('* => *', animate(duration+' '+ delay))
	])
}
