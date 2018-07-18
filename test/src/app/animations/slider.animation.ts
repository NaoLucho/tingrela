import { group, query, trigger, state, style, animate, transition } from '@angular/animations'

export const slideLeft = [
  query(':leave', style({ position: 'absolute', left: 0, right: 0 ,transform: 'translate3d(0%,0,0)' })),
  query(':enter', style({ position: 'absolute', left: 0, right: 0, transform: 'translate3d(-100%,0,0)' })),
  group([
    query(':leave',
      animate('1s', style({ transform: 'translate3d(100%,0,0)' }))),
    query(':enter',
      animate('1s', style({ transform: 'translate3d(0%,0,0)' })))
  ])
]

export const slideRight = [
  query(':leave', style({ position: 'absolute', left: 0, right: 0 , transform: 'translate3d(0%,0,0)'})),
  query(':enter', style({ position: 'absolute', left: 0, right: 0, transform: 'translate3d(100%,0,0)'})),

  group([
    query(':leave',
      animate('1s', style({ transform: 'translate3d(-100%,0,0)' }))),
    query(':enter', 
      animate('1s', style({ transform: 'translate3d(0%,0,0)' })))
  ])
]
