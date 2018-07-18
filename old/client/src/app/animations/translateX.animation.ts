import { query, animate, trigger, transition, style, stagger, keyframes } from '@angular/animations'

export function TranslaterX() {
    return trigger('openingAnimation', [
        transition('* => *', [

            query(':enter', style({ opacity: 0 }), { optional: true }),

            query(':enter', stagger('100ms', [
                animate('200ms ease-in', keyframes([
                    style({ opacity: 0, transform: 'translateX(50%)', offset: 0 }),
                    style({ opacity: 1, transform: 'translateX(0)', offset: 1.0 })
                ]))
            ]), { optional: true })
        ])
    ])
}
