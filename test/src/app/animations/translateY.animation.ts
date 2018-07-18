import { query, animate, trigger, transition, style, stagger, keyframes } from '@angular/animations'

export function TranslaterY() {
    return trigger('openingAnimation', [
        transition('* => *', [

            query(':enter', style({ opacity: 0 }), { optional: true }),

            query(':enter', stagger('100ms', [
                animate('200ms ease-in', keyframes([
                    style({ opacity: 0, transform: 'translateY(50%)', offset: 0 }),
                    style({ opacity: 1, transform: 'translateY(0)', offset: 1.0 })
                ]))
            ]), { optional: true })
        ])
    ])
}